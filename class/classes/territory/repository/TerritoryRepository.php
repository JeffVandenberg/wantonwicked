<?php
/**
 * Created by PhpStorm.
 * User: JeffVandenberg
 * Date: 5/2/2017
 * Time: 9:09 AM
 */

namespace classes\territory\repository;


use classes\core\repository\AbstractRepository;

class TerritoryRepository extends AbstractRepository
{
    public function __construct()
    {
        parent::__construct('classes\territory\data\Territory');
    }

    public function listTerritoriesWithPopulation()
    {
        $sql = <<<SQL
SELECT
	T.territory_name,
	T.id,
	C.character_name,
	(
		SELECT
			COUNT(*)
		FROM
			characters_territories AS CT
			LEFT JOIN characters as C2 ON CT.character_id = C2.id
		WHERE
			CT.is_active = 1
			AND (CT.updated_on IS NULL OR CT.updated_on > NOW())
			AND C2.is_sanctioned = 'Y'
			AND C2.is_deleted = 'N'
			AND CT.territory_id = T.id
	) AS pc_count,
	T.npc_population,
	T.quality,
	T.current_quality,
	T.max_quality,
	T.security,
	T.optimal_population
FROM
	territories as T
	LEFT JOIN characters AS C ON T.character_id = C.id
WHERE
	T.is_active = 1
	AND C.is_sanctioned = 'Y'
	AND C.is_deleted = 'N'
GROUP BY
	T.id
ORDER BY
	T.territory_name
SQL;
        return $this->query($sql)->all();
    }
}
