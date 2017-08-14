<?php

namespace classes\character\repository;

/**
 * Created by PhpStorm.
 * User: JeffVandenberg
 * Date: 2/25/2017
 * Time: 9:20 AM
 */


use classes\character\data\CharacterBeatRecord;
use classes\core\repository\AbstractRepository;

class CharacterBeatRecordRepository extends AbstractRepository
{
    public function __construct()
    {
        parent::__construct('classes\character\data\CharacterBeatRecord');
    }

    public function findByCharacterIdAndRecordMonth($characterId, $month)
    {
        // filter to key records on the first of each month
        $month = date('Y-m-01', strtotime($month));

        $sql = <<<SQL
SELECT
  *
FROM
  character_beat_records
WHERE
  character_id = ?
  AND record_month = ?
SQL;
        $params = [
            $characterId,
            $month
        ];

        $record = $this->query($sql)->single($params);

        if ($record) {
            return $this->populateObject($record);
        } else {
            $obj = new $this->ManagedObject();
            /* @var CharacterBeatRecord $obj */
            $obj->CharacterId = $characterId;
            $obj->RecordMonth = $month;
            return $obj;
        }
    }


}
