<?php
/**
 * Created by JetBrains PhpStorm.
 * User: JeffVandenberg
 * Date: 8/18/13
 * Time: 11:36 PM
 * To change this template use File | Settings | File Templates.
 */

namespace classes\request\repository;


class GroupRepository {

    public function ListAvailableForCharacter($characterId)
    {
        $characterId = (int) $characterId;
        $sql = <<<EOQ
SELECT
    G.id,
    G.name
FROM
    groups AS G
    LEFT JOIN characters AS C ON (G.name = C.character_type AND C.id = $characterId)
WHERE
    (G.group_type_id = 2
    OR C.character_type IS NOT NULL)
ORDER BY
    G.name
EOQ;

        return ExecuteQueryData($sql);
    }

    public function FindDefaultGroupForCharacter($characterId)
    {
        $characterId = (int) $characterId;
        $sql = <<<EOQ
SELECT
    G.id,
    G.name
FROM
    groups AS G
    LEFT JOIN characters AS C ON G.name = C.character_type
WHERE
    C.id = $characterId
EOQ;

        return ExecuteQueryItem($sql);
    }
}