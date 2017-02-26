<?php
/**
 * Created by PhpStorm.
 * User: JeffVandenberg
 * Date: 2/25/2017
 * Time: 12:05 PM
 */

namespace classes\character\repository;


use classes\core\repository\AbstractRepository;

class CharacterBeatRepository extends AbstractRepository
{
    public function __construct()
    {
        parent::__construct('classes\character\data\CharacterBeat');
    }

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
        foreach($this->query($sql)->all($params) as $record) {
            $rows[] = $this->populateObject($record);
        }

        return $rows;
    }


}
