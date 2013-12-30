<?php
/**
 * Created by JetBrains PhpStorm.
 * User: jvandenberg
 * Date: 9/13/13
 * Time: 3:51 PM
 * To change this template use File | Settings | File Templates.
 */

namespace classes\character\repository;


use classes\core\repository\AbstractRepository;

class LogCharacterRepository extends AbstractRepository 
{
    function __construct()
    {
        parent::__construct('classes\character\data\LogCharacter');
    }

    public function ListByCharacterId($characterId)
    {
        $table = $this->ManagedObject->getTableName();
        $sql = <<<EOQ
SELECT
    *
FROM
    $table
WHERE
    character_id = :id
ORDER BY
    created DESC
EOQ;

        $items = array();
        foreach($this->Query($sql)->Bind('id', $characterId)->All() as $row) {
            $items[] = $this->PopulateObject($row);
        }
        return $items;
    }

    public function ListByCharacterIdPaged($characterId, $page, $pageSize)
    {
        $startIndex = ($page - 1) * $pageSize;
        $sql = <<<EOQ
SELECT
    *
FROM
    log_characters
WHERE
    character_id = :id
ORDER BY
    created DESC
LIMIT
    $startIndex, $pageSize
EOQ;

        $items = array();
        foreach($this->Query($sql)->Bind('id', $characterId)->All() as $row) {
            $items[] = $this->PopulateObject($row);
        }
        return $items;
    }

    public function ListByCharacterIdRowRount($characterId)
    {
        $sql = <<<EOQ
SELECT
    count(*) AS `count`
FROM
    log_characters
WHERE
    character_id = :id
EOQ;

        $count = 0;
        foreach($this->Query($sql)->Bind('id', $characterId)->All() as $row) {
            $count = $row['count'];
        }
        return $count;
    }
}