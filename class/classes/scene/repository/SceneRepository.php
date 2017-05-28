<?php
/**
 * Created by PhpStorm.
 * User: JeffVandenberg
 * Date: 7/6/2015
 * Time: 12:00 AM
 */

namespace classes\scene\repository;


use classes\character\data\CharacterStatus;
use classes\core\repository\AbstractRepository;

class SceneRepository extends AbstractRepository
{
    function __construct()
    {
        parent::__construct('classes\scene\data\Scene');
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

    public function findStSceneDashboard($userId)
    {
        $sql = <<<EOQ
select
    S.id,
    S.slug,
    S.name,
    S.run_on_date,
    SS.name as scene_status_name,
    count(*) as `participants`
FROM
    scenes AS S
    LEFT JOIN scene_statuses AS SS ON S.scene_status_id = SS.id
    LEFT JOIN scene_characters AS SC ON S.id = SC.scene_id
WHERE
    run_by_id = ?
    AND run_on_date > NOW()
GROUP BY
    S.id,
    S.slug,
    S.name,
    S.run_on_date,
    SS.name
ORDER BY
    run_on_date
EOQ;
        $params = [
            $userId
        ];

        return $this->query($sql)->all($params);
    }

    public function findPlayerSceneDashboard($userId)
    {
        $sql = <<<EOQ
select
    S.id,
    S.slug,
    S.name,
    S.run_on_date,
    SS.name as scene_status_name,
    count(*) as `participants`
FROM
    scenes AS S
    LEFT JOIN scene_statuses AS SS ON S.scene_status_id = SS.id
    LEFT JOIN scene_characters AS SC ON S.id = SC.scene_id
    LEFT JOIN characters as C ON SC.character_id = C.id
WHERE
    C.user_id = ?
    AND C.character_status_id != ?
    AND S.run_on_date >= now()
GROUP BY
    S.id,
    S.slug,
    S.name,
    S.run_on_date,
    SS.name
ORDER BY
    S.run_on_date ASC,
    S.name
LIMIT 5
EOQ;
        $params = [
            $userId,
            CharacterStatus::Deleted
        ];

        return $this->query($sql)->all($params);
    }
}
