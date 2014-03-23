<?php
/**
 * Created by JetBrains PhpStorm.
 * User: JeffVandenberg
 * Date: 8/20/13
 * Time: 9:48 PM
 * To change this template use File | Settings | File Templates.
 */

namespace classes\request\repository;


class RequestCharacterRepository {

    public function ListByRequestId($requestId)
    {
        $requestId = (int) $requestId;

        $sql = <<<EOQ
SELECT
    RC.*,
    C.character_name
FROM
    request_characters AS RC
    LEFT JOIN characters AS C ON RC.character_id = C.id
WHERE
    RC.request_id = $requestId
ORDER BY
    C.character_name
EOQ;

        return ExecuteQueryData($sql);
    }

    public function FindById($id)
    {
        $id = (int) $id;

        $sql = <<<EOQ
SELECT
    *
FROM
    request_characters
WHERE
    id = $id;
EOQ;

        return ExecuteQueryItem($sql);
    }

    public function SetIsApproved($id, $isApproved)
    {
        $id = (int) $id;
        $isApproved = ($isApproved) ? 1 : 0;

        $sql = <<<EOQ
UPDATE
    request_characters
SET
    is_approved = $isApproved
WHERE
    id = $id
EOQ;

        return ExecuteQuery($sql);
    }
}