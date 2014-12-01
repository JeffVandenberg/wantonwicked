<?php
/**
 * Created by JetBrains PhpStorm.
 * User: JeffVandenberg
 * Date: 8/11/13
 * Time: 6:26 PM
 * To change this template use File | Settings | File Templates.
 */

namespace classes\request\repository;


use classes\request\data\RequestNote;

class RequestNoteRepository
{
    public function ListByRequestId($requestId)
    {
        $requestId = (int) $requestId;

        $sql = <<<EOQ
SELECT
    RN.*,
    U.username
FROM
    request_notes AS RN
    LEFT JOIN phpbb_users AS U ON RN.created_by_id = U.user_id
WHERE
    RN.request_id = $requestId
ORDER BY
    created_on ASC
EOQ;

        return ExecuteQueryData($sql);
    }

    public function Save(RequestNote $requestNote)
    {
        $note = mysql_real_escape_string($requestNote->Note);
        $sql = <<<EOQ
INSERT INTO
    request_notes
    (
        request_id,
        note,
        created_by_id,
        created_on
    )
VALUES
    (
        $requestNote->RequestId,
        '$note',
        $requestNote->CreatedById,
        '$requestNote->CreatedOn'
    )
EOQ;

        return ExecuteQuery($sql);
    }
}