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
        foreach($this->query($sql)->bind('id', $characterId)->all() as $row) {
            $items[] = $this->populateObject($row);
        }
        return $items;
    }

    public function ListByCharacterIdPaged($filterOptions, $page, $pageSize)
    {
        $startIndex = ($page - 1) * $pageSize;
        $sql = <<<EOQ
SELECT
    *
FROM
    log_characters
WHERE
    character_id = ?
EOQ;

        $params = [
            $filterOptions['character_id']
        ];

        if($filterOptions['filter_logins']) {
            $sql .= ' AND action_type_id != 2 ';
        }

        if($filterOptions['log_id']) {
            $sql .= ' AND id = ? ';
            $params[]  = $filterOptions['log_id'];
        }

        $sql .= <<<EOQ
ORDER BY
    id DESC
LIMIT
    $startIndex, $pageSize
EOQ;

        $items = array();
        foreach($this->query($sql)->all($params) as $row) {
            $items[] = $this->populateObject($row);
        }
        return $items;
    }

    public function ListByCharacterIdRowRount($filterOptions)
    {
        $sql = <<<EOQ
SELECT
    count(*) AS `count`
FROM
    log_characters
WHERE
    character_id = ?
EOQ;

        $params = [
            $filterOptions['character_id']
        ];

        if($filterOptions['filter_logins']) {
            $sql .= ' AND action_type_id != 2 ';
        }

        if($filterOptions['log_id']) {
            $sql .= ' AND id = ? ';
            $params[]  = $filterOptions['log_id'];
        }

        return $this->query($sql)->value($params);
    }
}
