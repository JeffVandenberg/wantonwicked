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
use classes\scene\data\Scene;

class SceneRepository extends AbstractRepository
{
    public function __construct()
    {
        parent::__construct(Scene::class);
    }

    public function findStSceneDashboard($userId): array
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

    public function findPlayerSceneDashboard($userId): array
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
            CharacterStatus::DELETED
        ];

        return $this->query($sql)->all($params);
    }
}
