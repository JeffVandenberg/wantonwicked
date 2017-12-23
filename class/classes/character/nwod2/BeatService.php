<?php

/**
 * Created by PhpStorm.
 * User: JeffVandenberg
 * Date: 2/25/2017
 * Time: 8:57 AM
 */
namespace classes\character\nwod2;

use classes\character\data\BeatStatus;
use classes\character\data\BeatType;
use classes\character\data\CharacterBeat;
use classes\character\data\CharacterBeatRecord;
use classes\character\repository\CharacterBeatRecordRepository;
use classes\character\repository\CharacterBeatRepository;
use classes\character\repository\CharacterRepository;
use classes\core\repository\RepositoryManager;
use function intval;
use function settype;
use function var_dump;

/**
 * Class BeatService
 * @package classes\character\nwod2
 */
class BeatService
{
    private $maxXpPerMonth = 2;
    private $staffId = 8;

    /**
     * @param CharacterBeat $beat
     * @return bool
     */
    public function addNewBeat(CharacterBeat $beat)
    {
        $beatRepo = RepositoryManager::GetRepository(CharacterBeat::class);
        $historyRepo = RepositoryManager::GetRepository(CharacterBeatRecord::class);
        /* @var CharacterBeatRecordRepository $historyRepo */

        // load history record
        $beatRecord = $historyRepo->findByCharacterIdAndRecordMonth($beat->CharacterId, date('Y-m-01'));
        /* @var CharacterBeatRecord $beatRecord */

        // check if character is at max award for the month
        if ($beatRecord->ExperienceEarned < $this->maxXpPerMonth) {
            // give beat immediately
            return $this->grantBeat($beat, $beatRecord);
        } else {
            die('add to queue');
            // add beat to the end of the queue
            return $beatRepo->save($beat);
        }
    }

    /**
     * @param CharacterBeat $beat
     * @param CharacterBeatRecord $beatRecord
     * @return bool
     */
    private function grantBeat(CharacterBeat $beat, CharacterBeatRecord $beatRecord)
    {
        $beatRepo = RepositoryManager::GetRepository(CharacterBeat::class);
        $historyRepo = RepositoryManager::GetRepository(CharacterBeatRecord::class);

        $beatRepo->startTransaction();

        $numberOfBeatsToGrant = $beat->BeatType->NumberOfBeats;
        $numberOfBeatsRemaining = $this->getNumberOfBeatsRemaining($beatRecord);

        $beatSpillOver = 0;
        if($numberOfBeatsToGrant > $numberOfBeatsRemaining) {
            $beatSpillOver = $numberOfBeatsToGrant - $numberOfBeatsRemaining;
            $numberOfBeatsToGrant = $numberOfBeatsRemaining;
        }

        // add XP to the character
        $sheetService = new SheetService();
        $sheetService->grantXpToCharacter(
            $beat->CharacterId,
            $numberOfBeatsToGrant * .2,
            ($numberOfBeatsToGrant * .2) . ' XP From Beat Claim',
            $beat->CreatedById
        );

        // add XP to the Beat Record for the month
        $beatRecord->ExperienceEarned += ($numberOfBeatsToGrant * .2);
        $historyRepo->save($beatRecord);

        if($beatSpillOver > 0) {
            $splitBeat = $this->createSplitBeat($beat->CharacterId, $beatSpillOver);
            $splitBeat->BeatStatusId = $beat->BeatStatusId;
            $beatRepo->save($splitBeat);
        }

        // mark beat as granted
        $beat->BeatStatusId = BeatStatus::Applied;
        $beat->AppliedOn = date('Y-m-d H:i:s');
        $beat->BeatsAwarded = $numberOfBeatsToGrant;

        $beatRepo->save($beat);
        $beatRepo->commitTransaction();
        return true;
    }

    /**
     * @param $characterId
     * @param null $date
     * @return CharacterBeatRecord|mixed
     */
    public function getBeatStatusForCharacter($characterId, $date = null)
    {
        if (!$date) {
            $date = date('Y-m-01');
        }

        $historyRepo = RepositoryManager::GetRepository(CharacterBeatRecord::class);
        /* @var CharacterBeatRecordRepository $historyRepo */

        // load history record
        return $historyRepo->findByCharacterIdAndRecordMonth($characterId, $date);
    }

    /**
     * @param $characterId
     * @return CharacterBeat[]
     */
    public function listPastBeatsForCharacter($characterId)
    {
        $beatRepo = RepositoryManager::GetRepository(CharacterBeat::class);
        /* @var CharacterBeatRepository $beatRep */
        return $beatRepo->listPastBeatsForCharacter($characterId);
    }

    /**
     * @param $beatId
     * @return CharacterBeat
     */
    public function findBeatById($beatId)
    {
        $beatRepo = RepositoryManager::GetRepository('classes\character\data\CharacterBeat');
        /* @var CharacterBeatRepository $beatRep */
        return $beatRepo->getById($beatId);
    }

    public function awardOutstandingBeats()
    {
        $characterRepo = RepositoryManager::GetRepository('classes\character\data\Character');
        /* @var CharacterRepository $characterRepo */
        $characters = $characterRepo->listCharactersWithOutstandingBeats();

        foreach ($characters as $character) {
            $this->awardOutstandingBeatsToCharacter($character['id']);
        }
    }

    public function awardOutstandingBeatsToCharacter($characterId)
    {
        $beatRepo = RepositoryManager::GetRepository('classes\character\data\CharacterBeat');
        /* @var CharacterBeatRepository $beatRepo */
        $beatRecordRepo = RepositoryManager::GetRepository('classes\character\data\CharacterBeatRecord');
        /* @var CharacterBeatRecordRepository $beatRecordRepo */

        // list beats for character
        $beats = $beatRepo->listOpenByCharacterId($characterId);
        /* @var CharacterBeat[] $beats */

        // load beast record for month
        $beatRecord = $beatRecordRepo->findByCharacterIdAndRecordMonth($characterId, date('Y-m-d'));
        // loop through beats to award them
        foreach ($beats as $beat) {
            // convert to int for clean comparisons
            $integerExpEarned = (int) ($beatRecord->ExperienceEarned * 10);

            if ($integerExpEarned >= ($this->maxXpPerMonth * 10)) {
                // stop processing more beats6
                break;
            }
            $this->grantBeat($beat, $beatRecord);
        }
    }

    public function expireOldBeats()
    {
        $beatRepo = RepositoryManager::GetRepository('classes\character\data\CharacterBeat');
        /* @var CharacterBeatRepository $beatRepo */

        $beatRepo->setStatusForBeatsOlderThan(BeatStatus::Expired, date('Y-m-d', strtotime('-1 month')));
    }

    /**
     * @param CharacterBeatRecord $beatRecord
     * @return int
     */
    private function getNumberOfBeatsRemaining(CharacterBeatRecord $beatRecord): int
    {
        $difference = ($this->maxXpPerMonth* 5) - ($beatRecord->ExperienceEarned * 5);
        return (int) $difference;
    }

    private function createSplitBeat($characterId, $beatSpillOver)
    {
        $beatTypeId = $this->findSplitBeatTypeIdForAmount($beatSpillOver);

        $beat = new CharacterBeat();
        $beat->BeatTypeId = $beatTypeId;
        $beat->CharacterId = $characterId;
        $beat->BeatStatusId = BeatStatus::StaffAwarded;
        $beat->Note = "Split Beat for: $beatSpillOver";
        $beat->CreatedById = $this->staffId;
        $beat->Created = date('Y-m-d H:i:s');
        $beat->UpdatedById = $this->staffId;
        $beat->Updated = date('Y-m-d H:i:s');
        return $beat;
    }

    private function findSplitBeatTypeIdForAmount($beatSpillOver)
    {
        $beatTypeRepository = RepositoryManager::GetRepository(BeatType::class);
        $beatTypeName = "Split Beat ($beatSpillOver)";
        $beatType = $beatTypeRepository->FindByNameAndNumberOfBeats($beatTypeName, $beatSpillOver);
        /* @var BeatType $beatType */
        if(!$beatType->Id) {
            $beatType->Name = $beatTypeName;
            $beatType->NumberOfBeats = $beatSpillOver;
            $beatType->AdminOnly = true;
            $beatType->CreatedById = $this->staffId;
            $beatType->UpdatedById = $this->staffId;
            $beatType->MayRollover = true;
            $beatType->Created = date("Y-m-d H:i:s");
            $beatType->Updated = date("Y-m-d H:i:s");
            $beatTypeRepository->save($beatType);
        }
        return $beatType;
    }
}
