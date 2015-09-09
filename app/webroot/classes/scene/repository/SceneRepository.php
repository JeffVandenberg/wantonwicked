<?php
/**
 * Created by PhpStorm.
 * User: JeffVandenberg
 * Date: 7/6/2015
 * Time: 12:00 AM
 */

namespace classes\scene\repository;


use classes\core\repository\AbstractRepository;

class SceneRepository extends AbstractRepository
{
    function __construct()
    {
        parent::__construct();
    }

    public function ListScenesForCharacter($characterId)
    {
        $sql = <<<EOQ
SELECT
    S.id,
    S.name,
    S.run_on_date
FROM
    scenes AS S
    INNER JOIN scene_characters AS SC ON S.id = SC.scene_id
WHERE
    SC.character_id = ?
    AND S.scene_status_id != 3
ORDER BY
    S.name
EOQ;

        $params = array($characterId);

        $list = array();
        foreach($this->query($sql)->all($params) as $row) {
            $list[$row['id']] = $row['name'] . ' (' . date("Y-m-d", strtotime($row['run_on_date'])).')  ';
        }

        return $list;
    }


}