<?php
use classes\character\data\CharacterBeatRecord;

/**
 * Created by PhpStorm.
 * User: JeffVandenberg
 * Date: 2/25/2017
 * Time: 8:57 AM
 */

namespace classes\character\nwod2;


use classes\character\data\BeatStatus;
use classes\character\data\CharacterBeat;
use classes\character\repository\CharacterBeatRecordRepository;
use classes\character\repository\CharacterBeatRepository;
use classes\character\repository\CharacterRepository;
use classes\core\repository\RepositoryManager;

/**
 * Class BeatService
 * @package classes\character\nwod2
 */
class BeatService
{
    private $maxXpPerMonth = 2;

    /**
     * @param CharacterBeat $beat
     * @return bool
     */
    public function addNewBeat(CharacterBeat $beat)
    {
        $beatRepo = RepositoryManager::GetRepository('classes\character\data\CharacterBeat');
        $historyRepo = RepositoryManager::GetRepository('classes\character\data\CharacterBeatRecord');
        /* @var CharacterBeatRecordRepository $historyRepo */

        // load history record
        $beatRecord = $historyRepo->findByCharacterIdAndRecordMonth($beat->CharacterId, date('Y-m-01'));
        /* @var CharacterBeatRecord $beatRecord */

        // check if character is at max award for the month
        if ($beatRecord->ExperienceEarned < $this->maxXpPerMonth) {
            // give beat immediately
            return $this->grantBeat($beat, $beatRecord);
        } else {
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
        $beatRepo = RepositoryManager::GetRepository('classes\character\data\CharacterBeat');
        $historyRepo = RepositoryManager::GetRepository('classes\character\data\CharacterBeatRecord');

        $beatRepo->startTransaction();

        // add XP to the character
        $sheetService = new SheetService();
        $sheetService->grantXpToCharacter(
            $beat->CharacterId,
            $beat->BeatType->NumberOfBeats * .2,
            ($beat->BeatType->NumberOfBeats * .2) . ' XP From Beat Claim',
            $beat->CreatedById
        );

        // add XP to the Beat Record for the month
        $beatRecord->ExperienceEarned += ($beat->BeatType->NumberOfBeats * .2);
        $historyRepo->save($beatRecord);

        // mark beat as granted
        $beat->BeatStatusId = BeatStatus::Applied;
        $beat->AppliedOn = date('Y-m-d H:i:s');
        $beat->BeatsAwarded = $beat->BeatType->NumberOfBeats;

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

        $historyRepo = RepositoryManager::GetRepository('classes\character\data\CharacterBeatRecord');
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
        $beatRepo = RepositoryManager::GetRepository('classes\character\data\CharacterBeat');
        /* @var CharacterBeatRecordRepository $beatRep */
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

        foreach($characters as $character)
        {
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
        foreach($beats as $beat) {
            if(($beatRecord->ExperienceEarned+.1) >= $this->maxXpPerMonth) { // eww... PHP Math. :/
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

        $beatRepo->setStatusForBeatsOlderThan(BeatStatus::NewBeat, date('Y-m-d', strtotime('-1 month')));
    }
}
