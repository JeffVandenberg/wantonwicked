<?php
/**
 * Created by JetBrains PhpStorm.
 * User: JeffVandenberg
 * Date: 8/11/13
 * Time: 6:26 PM
 * To change this template use File | Settings | File Templates.
 */

namespace classes\request\repository;


use classes\core\repository\Database;
use classes\request\data\RequestNote;

class RequestNoteRepository
{
    public function listByRequestId($requestId)
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
    RN.request_id = ?
ORDER BY
    created_on ASC
EOQ;
        $params = array(
            $requestId
        );
        return Database::getInstance()->query($sql)->all($params);
    }

    public function save(RequestNote $requestNote)
    {
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
    ( ?, ?, ?, ?)
EOQ;
        $params = array(
            $requestNote->RequestId,
            $requestNote->Note,
            $requestNote->CreatedById,
            $requestNote->CreatedOn

        );
        return Database::getInstance()->query($sql)->execute($params);
    }
}
