<?php

namespace classes\character\repository;

/**
 * Created by PhpStorm.
 * User: JeffVandenberg
 * Date: 2/25/2017
 * Time: 12:05 PM
 */

use classes\character\data\BeatStatus;
use classes\character\data\CharacterBeat;
use classes\core\repository\AbstractRepository;

/**
 * Class CharacterBeatRepository
 * @package classes\character\repository
 */
class CharacterBeatRepository extends AbstractRepository
{
    /**
     * CharacterBeatRepository constructor.
     */
    public function __construct()
    {
        parent::__construct('classes\character\data\CharacterBeat');
    }

    /**
     * @param $characterId
     * @return CharacterBeat[]
     */
    public function listPastBeatsForCharacter($characterId)
    {
        $sql = <<<SQL
SELECT 
  *
FROM
  character_beats
WHERE
  character_id = ?
ORDER BY
  id DESC
SQL;
        $params = [
            $characterId
        ];

        $rows = [];
        foreach ($this->query($sql)->all($params) as $record) {
            $rows[] = $this->populateObject($record);
        }

        return $rows;
    }

    /**
     * @param $characterId
     * @return CharacterBeat[]
     */
    public function listOpenByCharacterId($characterId)
    {
        $sql = <<<SQL
SELECT
  *
FROM
  character_beats AS cb
WHERE
  character_id = ? 
  AND beat_status_id IN (?, ?)
ORDER BY
  cb.beat_status_id,
  cb.created 
SQL;
        $params = [
            $characterId,
            BeatStatus::NEW_BEAT,
            BeatStatus::STAFF_AWARDED
        ];

        $list = [];
        foreach ($this->query($sql)->all($params) as $data) {
            $list[] = $this->populateObject($data);
        }

        return $list;
    }

    /**
     * @param $newBeatStatusId
     * @param $cutoffDate
     * @return int
     */
    public function setStatusForBeatsOlderThan($newBeatStatusId, $cutoffDate)
    {
        $sql = <<<SQL
UPDATE
  character_beats
SET
  beat_status_id = ?
WHERE
  beat_status_id = ?
  AND created <= ?
SQL;
        $params = [
            $newBeatStatusId,
            BeatStatus::NEW_BEAT,
            $cutoffDate
        ];

        return $this->query($sql)->execute($params);
    }


}
