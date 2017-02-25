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
use classes\character\data\CharacterBeatRecord;
use classes\character\repository\CharacterBeatRecordRepository;
use classes\core\repository\RepositoryManager;

/**
 * Class BeatService
 * @package classes\character\nwod2
 */
class BeatService
{
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
        if($beatRecord->ExperienceEarned < 2) { // todo: move this to a config or something.
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

        // add XP to the character
        $sheetService = new SheetService();
        $sheetService->grantXpToCharacter(
            $beat->CharacterId,
            $beat->BeatType->NumberOfBeats * .2,
            'XP FRom Beat Claim',
            $beat->CreatedById
        );

        // add XP to the Beat Record for the month
        $beatRecord->ExperienceEarned += $beat->BeatType->NumberOfBeats * .2;
        $historyRepo->save($beatRecord);

        // mark beat as granted
        $beat->BeatStatusId = BeatStatus::Applied;
        $beat->AppliedOn = date('Y-m-d H:i:s');
        $beat->BeatsAwarded = $beat->BeatType->NumberOfBeats;

        $beatRepo->save($beat);
        return true;
    }

    /**
     * @param $characterId
     * @param null $date
     * @return CharacterBeatRecord|mixed
     */
    public function getBeatStatusForCharacter($characterId, $date = null)
    {
        if(!$date) {
            $date = date('Y-m-01');
        }

        $historyRepo = RepositoryManager::GetRepository('classes\character\data\CharacterBeatRecord');
        /* @var CharacterBeatRecordRepository $historyRepo */

        // load history record
        return $historyRepo->findByCharacterIdAndRecordMonth($characterId, $date);
    }

    public function listPastBeatsForCharacter($characterId)
    {
        $beatRepo = RepositoryManager::GetRepository('classes\character\data\CharacterBeat');
        /* @var CharacterBeatRecordRepository $beatRep */
        return $beatRepo->listPastBeatsForCharacter($characterId);
    }
}
