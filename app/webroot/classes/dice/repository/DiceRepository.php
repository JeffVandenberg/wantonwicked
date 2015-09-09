<?php
/**
 * Created by PhpStorm.
 * User: jvandenberg
 * Date: 8/17/15
 * Time: 3:16 PM
 */

namespace classes\dice\repository;


use classes\core\repository\AbstractRepository;

class DiceRepository extends AbstractRepository
{
    /**
     */
    function __construct()
    {
        parent::__construct('classes\dice\data\Dice');
    }

    public function findCountOfRolls($showOnlyMyRolls, $characterId)
    {
        $sql = <<<EOQ
SELECT
	COUNT(*) AS count
FROM
	wod_dierolls
EOQ;
        $params = array();
        if($showOnlyMyRolls) {
            $sql .= ' WHERE character_id = ? ';
            $params[] = $characterId;
        }

        return $this->query($sql)->single($params);
    }

    public function loadRolls($showOnlyMyRolls, $characterId, $page, $pageSize)
    {
        $currentRow = ($page - 1) * $pageSize;
        $sql = <<<EOQ
SELECT
	*
FROM
	wod_dierolls
EOQ;
        $params = array();
        if($showOnlyMyRolls) {
            $sql .= ' WHERE character_id = ? ';
            $params[] = $characterId;
        }

        $sql .=  <<<EOQ

ORDER BY
	roll_id DESC
LIMIT
	$currentRow, $pageSize;
EOQ;

        return $this->query($sql)->all($params);
    }

}