<?php
/**
 * Created by JetBrains PhpStorm.
 * User: JeffVandenberg
 * Date: 8/20/13
 * Time: 9:48 PM
 * To change this template use File | Settings | File Templates.
 */

namespace classes\request\repository;


use classes\core\repository\AbstractRepository;
use classes\request\data\RequestCharacter;

class RequestCharacterRepository extends AbstractRepository
{
    function __construct()
    {
        parent::__construct('classes\request\data\RequestCharacter');
    }

    /**
     * @param $requestId
     * @return RequestCharacter[]s
     */
    public function ListByRequestId($requestId)
    {
        $sql = <<<EOQ
SELECT
    RC.*
FROM
    request_characters AS RC
    LEFT JOIN characters AS C ON RC.character_id = C.id
WHERE
    RC.request_id = ?
ORDER BY
    RC.is_primary DESC,
    C.character_name
EOQ;

        $params = array($requestId);
        $list = array();
        foreach($this->query($sql)->all($params) as $row) {
            $list[] = $this->populateObject($row);
        }
        return $list;
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
        $params = array(
            $isApproved,
            $id
        );
        return $this->query($sql)->execute($params);
    }

    public function FindLinkedCharacterForUser($requestId, $userId)
    {
        $sql = <<<EOQ
SELECT
    RC.*
FROM
    request_characters AS RC
    LEFT JOIN characters AS C on RC.character_id = C.id
WHERE
    RC.request_id = ?
    AND C.user_id = ?
EOQ;
        $params = array($requestId, $userId);

        return $this->populateObject(
            $this->query($sql)->single($params)
        );
    }
}