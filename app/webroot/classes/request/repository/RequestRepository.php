<?php
/**
 * Created by JetBrains PhpStorm.
 * User: JeffVandenberg
 * Date: 8/11/13
 * Time: 10:27 AM
 * To change this template use File | Settings | File Templates.
 */

namespace classes\request\repository;


use classes\core\data\DataModel;
use classes\core\repository\AbstractRepository;
use classes\core\repository\RepositoryManager;
use classes\request\data\Request;
use classes\request\data\RequestStatus;
use classes\request\data\RequestStatusHistory;
use classes\request\data\RequestType;

class RequestRepository extends AbstractRepository
{

    function __construct()
    {
        parent::__construct('classes\request\data\Request');
    }

    public function Save(DataModel $request)
    {
        /* @var Request $request */

        $result = parent::Save($request);
        if($result) {
            $requestStatusHistory = new RequestStatusHistory();
            $requestStatusHistory->RequestId = $request->Id;
            $requestStatusHistory->RequestStatusId = $request->RequestStatusId;
            $requestStatusHistory->CreatedById = $request->UpdatedById;
            $requestStatusHistory->CreatedOn = date('Y-m-d H:i:s');
            $requestStatusHistoryRepository = RepositoryManager::GetRepository('classes\request\data\RequestStatusHistory');
            return $requestStatusHistoryRepository->Save($requestStatusHistory);
        }
        return $result;
    }

    public function FindById($id)
    {
        $id = (int) $id;
        $sql = <<<EOQ
SELECT
    R.*,
    C.character_name,
    G.name as group_name
FROM
    requests AS R
    LEFT JOIN groups as G ON R.group_id = G.id
    LEFT JOIN characters AS C on R.character_id = C.id
WHERE
    R.id = $id;
EOQ;
        return ExecuteQueryItem($sql);
    }

    public function ListByCharacterId($characterId, $page, $pageSize, $sort, $statusId = 0, $filter)
    {
        $openStatuses = RequestStatus::$Player;
        if($statusId != 0) {
            $openStatuses = array($statusId);
        }
        $startIndex = ($page - 1) * $pageSize;
        $sort = mysql_real_escape_string($sort);

        $sql = <<<EOQ
SELECT
    R.*,
    RT.name as request_type_name,
    RS.name as request_status_name,
    UB.username AS updated_by_username
FROM
    requests as R
    LEFT JOIN request_types AS RT ON R.request_type_id = RT.id
    LEFT JOIN request_statuses AS RS ON R.request_status_id = RS.id
    LEFT JOIN phpbb_users AS UB ON R.updated_by_id = UB.user_id
WHERE
    R.character_id = ?
EOQ;
        $parameters = array($characterId);
        if($filter['title'] !== '') {
            $sql .= ' AND R.title LIKE ? ';
            $parameters[] = $filter['title'] . '%';
        }

        if($filter['request_type_id'] != 0) {
            $sql .= ' AND R.request_type_id = ? ';
            $parameters[] = $filter['request_type_id'];
        }
        else {
            $sql .= ' AND R.request_type_id != ? ';
            $parameters[] = RequestType::BlueBook;
        }

        if($filter['request_status_id'] != 0) {
            $sql .= ' AND R.request_status_id = ? ';
            $parameters[] = $filter['request_status_id'];
        }
        else {
            $sql .= ' AND R.request_status_id IN (' . implode(',', array_fill(0, count($openStatuses), '?')) . ') ';
            $parameters = array_merge($parameters, $openStatuses);
        }

        $sql .= <<<EOQ
        ORDER BY
    $sort
LIMIT
    $startIndex, $pageSize
EOQ;

        return $this->Query($sql)->All($parameters);
    }


    public function ListByCharacterIdCount($characterId, $statusId, $filter)
    {
        $characterId = (int) $characterId;

        $openStatuses = RequestStatus::$Player;
        if($statusId != 0) {
            $openStatuses = array($statusId);
        }

        $sql = <<<EOQ
SELECT
    COUNT(*) AS `count`
FROM
    requests as R
WHERE
    R.character_id = ?
EOQ;
        $parameters = array($characterId);
        if($filter['title'] !== '') {
            $sql .= ' AND R.title LIKE ? ';
            $parameters[] = $filter['title'] . '%';
        }

        if($filter['request_type_id'] != 0) {
            $sql .= ' AND R.request_type_id = ? ';
            $parameters[] = $filter['request_type_id'];
        }
        else {
            $sql .= ' AND R.request_type_id != ? ';
            $parameters[] = RequestType::BlueBook;
        }

        if($filter['request_status_id'] != 0) {
            $sql .= ' AND R.request_status_id = ? ';
            $parameters[] = $filter['request_status_id'];
        }
        else {
            $sql .= ' AND R.request_status_id IN (' . implode(',', array_fill(0, count($openStatuses), '?')) . ') ';
            $parameters = array_merge($parameters, $openStatuses);
        }

        $count = 0;
        foreach($this->Query($sql)->All($parameters) as $row) {
            $count = $row['count'];
        }
        return $count;
    }

    public function MayViewRequest($requestId, $userId, $linkedCharacterId = 0)
    {
        $requestId = (int) $requestId;
        $userId = (int) $userId;

        $sql = <<<EOQ
SELECT
    count(*) AS `rows`
FROM
    requests as R
    LEFT JOIN request_characters AS RC ON R.id = RC.request_id
WHERE
    R.id = ?
    AND (
        R.created_by_id = ?
        OR
        RC.character_id = ?
    )
EOQ;
        $parameters = array($requestId, $userId, $linkedCharacterId);
        $rows = $this->Query($sql)->Value($parameters);
        return ($rows > 0);
    }

    public function GetOpenRequestsNotAttachedToRequest($requestId, $characterId)
    {
        $requestId = (int) $requestId;
        $characterId = (int) $characterId;
        $closed = RequestStatus::Closed;
        $bluebook = RequestType::BlueBook;
        $sql = <<<EOQ
SELECT
    R.*
FROM
    requests AS R
    LEFT JOIN request_requests AS RR ON (R.id = RR.from_request_id AND RR.to_request_id = $requestId)
WHERE
    RR.to_request_id IS NULL
    AND R.character_id = $characterId
    AND R.id != $requestId
    AND R.request_type_id != $bluebook
ORDER BY
    title
EOQ;

        return ExecuteQueryData($sql);
    }

    public function AttachRequestToRequest($requestId, $fromRequestId)
    {
        $requestId = (int) $requestId;
        $fromRequestId = (int) $fromRequestId;

        $sql = <<<EOQ
INSERT INTO
    request_requests
    (
        from_request_id,
        to_request_id
    )
VALUES
    (
        $fromRequestId,
        $requestId
    )
EOQ;
        return ExecuteQuery($sql);
    }

    public function ListSupportingRequests($requestId)
    {
        $requestId = (int) $requestId;
        $bluebook = RequestType::BlueBook;

        $sql = <<<EOQ
SELECT
    R.*
FROM
    request_requests AS RR
    LEFT JOIN requests AS R ON RR.from_request_id = R.id
WHERE
    RR.to_request_id = $requestId
    AND R.request_type_id != $bluebook
ORDER BY
    R.title
EOQ;

        return ExecuteQueryData($sql);
    }

    public function ListOpenRequestsForCharacter($characterId)
    {
        $characterId = (int) $characterId;
        $edittable = implode(',', RequestStatus::$Terminal);
        $blueBook = RequestType::BlueBook;

        $sql = <<<EOQ
SELECT
    R.*
FROM
    requests AS R
WHERE
    request_status_id NOT IN ($edittable)
    AND request_type_id != $blueBook
    AND character_id = $characterId
ORDER BY
    title
EOQ;

        return ExecuteQueryData($sql);
    }

    public function AttachRollToRequest($requestId, $rollId)
    {
        $requestId = (int) $requestId;
        $rollId = (int) $rollId;

        $sql = <<<EOQ
INSERT INTO
    request_rolls
    (
        request_id,
        roll_id
    )
VALUES
    (
        $requestId,
        $rollId
    )
EOQ;

        return ExecuteQuery($sql);
    }

    public function ListSupportingRolls($requestId)
    {
        $requestId = (int) $requestId;
        $sql = <<<EOQ
SELECT
    WD.*
FROM
    wod_dierolls AS WD
    INNER JOIN request_rolls AS RR ON WD.roll_id = RR.roll_id
WHERE
    RR.request_id = $requestId
ORDER BY
    roll_date DESC
EOQ;

        return ExecuteQueryData($sql);
    }

    public function ListBlueBookByCharacterId($characterId, $page, $pageSize, $sort, $filter)
    {
        $startIndex = ($page-1) * $pageSize;

        $sql = <<<EOQ
SELECT
    R.*,
    RT.name as request_type_name,
    RS.name as request_status_name,
    UB.username AS updated_by_username
FROM
    requests as R
    LEFT JOIN request_types AS RT ON R.request_type_id = RT.id
    LEFT JOIN request_statuses AS RS ON R.request_status_id = RS.id
    LEFT JOIN phpbb_users AS UB ON R.updated_by_id = UB.user_id
WHERE
    R.character_id = ?
    AND R.request_type_id = ?
ORDER BY
    $sort
LIMIT
    $startIndex, $pageSize
EOQ;

        return $this->Query($sql)->All(array($characterId, RequestType::BlueBook));
    }

    public function ListBlueBookEntriesNotAttachedToRequest($requestId, $characterId)
    {
        $requestId = (int) $requestId;
        $characterId = (int) $characterId;
        $bluebook = RequestType::BlueBook;

        $sql = <<<EOQ
SELECT
    R.*
FROM
    requests AS R
    LEFT JOIN request_bluebooks AS RB ON (R.id = RB.bluebook_id AND RB.request_id = $requestId)
WHERE
    RB.request_id IS NULL
    AND R.character_id = $characterId
    AND R.id != $requestId
    AND R.request_type_id = $bluebook
ORDER BY
    title
EOQ;

        return ExecuteQueryData($sql);
    }

    public function AttachBluebookToRequest($requestId, $bluebookId)
    {
        $requestId = (int) $requestId;
        $bluebookId = (int) $bluebookId;

        $sql = <<<EOQ
INSERT INTO
    request_bluebooks
    (
        request_id,
        bluebook_id
    )
VALUES
    (
        $requestId,
        $bluebookId
    )
EOQ;
        return ExecuteQuery($sql);
    }

    public function ListSupportingBluebookEntries($requestId)
    {
        $requestId = (int) $requestId;
        $bluebook = RequestType::BlueBook;

        $sql = <<<EOQ
SELECT
    R.*
FROM
    request_bluebooks AS RB
    LEFT JOIN requests AS R ON RB.bluebook_id = R.id
WHERE
    RB.request_id = $requestId
    AND R.request_type_id = $bluebook
ORDER BY
    R.title
EOQ;

        return ExecuteQueryData($sql);
    }

    public function ListByGroups($groups, $characterId, $page, $pageSize, $sort, $filter)
    {
        $groupListPlaceholders = implode(',', array_fill(0, count($groups), '?'));

        $startIndex = ($page - 1) * $pageSize;
        $sort = mysql_real_escape_string($sort);

        $storytellerPlaceholders = implode(',', array_fill(0, count(RequestStatus::$Storyteller), '?'));

        $sql = <<<EOQ
SELECT
    R.*,
    G.name AS group_name,
    C.character_name,
    RT.name as request_type_name,
    RS.name as request_status_name,
    UB.username AS updated_by_username
FROM
    requests as R
    LEFT JOIN request_types AS RT ON R.request_type_id = RT.id
    LEFT JOIN request_statuses AS RS ON R.request_status_id = RS.id
    LEFT JOIN phpbb_users AS UB ON R.updated_by_id = UB.user_id
    LEFT JOIN characters AS C ON R.character_id = C.id
    LEFT JOIN groups as G ON R.group_id = G.id
WHERE
    R.group_id IN ($groupListPlaceholders)
    AND C.is_deleted = 'N'
    AND ((C.id = ?) OR (? = 0))
EOQ;
        $parameters = $groups;
        $parameters[] = $characterId;
        $parameters[] = $characterId;

        if($filter['character_name'] != '') {
            $sql .= ' AND C.character_name LIKE ? ';
            $parameters[] = $filter['character_name'] . '%';
        }
        if($filter['request_type_id'] != '0') {
            $sql .= ' AND R.request_type_id = ? ';
            $parameters[] = $filter['request_type_id'];
        }
        else {
            $sql .= ' AND R.request_type_id != ? ';
            $parameters[] = RequestType::BlueBook;
        }

        if($filter['title'] != '') {
            $sql .= ' AND R.title LIKE ? ';
            $parameters[] = $filter['title'] . '%';
        }

        if($filter['request_status_id'] != '0') {
            if($filter['request_status_id'] != -1) {
                $sql .= ' AND R.request_status_id = ? ';
                $parameters[] = $filter['request_status_id'];
            }
        }
        else {
            $sql .= ' AND R.request_status_id IN (' . $storytellerPlaceholders . ')';
            $parameters = array_merge($parameters, RequestStatus::$Storyteller);
        }


        $sql .= <<<EOQ
ORDER BY
    $sort
LIMIT
    $startIndex, $pageSize
EOQ;

        return $this->Query($sql)->All($parameters);
    }

    public function Submit($id)
    {
        $submitted = RequestStatus::Submitted;
        $id = (int)$id;
        $sql = <<<EOQ
UPDATE
    requests
SET
    request_status_id = $submitted
WHERE
    id = $id;
EOQ;

        return ExecuteQuery($sql);
    }

    public function SearchCharactersForRequest($requestId, $onlySanctioned, $characterName)
    {
        $requestId = (int) $requestId;
        $onlySanctioned = (int) $onlySanctioned;
        $characterName = mysql_real_escape_string($characterName);

        $sql = <<<EOQ
SELECT
    C.id,
    C.character_name
FROM
    requests AS R
    LEFT JOIN characters AS C2 ON R.character_id = C2.id
    LEFT JOIN characters AS C ON C2.city = C.city
WHERE
    R.id = $requestId
    AND (
        (C.is_sanctioned = 'Y' AND ($onlySanctioned = 1))
        OR
        ($onlySanctioned = 0)
    )
    AND C.is_deleted = 'N'
    AND C.character_name like '$characterName%'
ORDER BY
    C.character_name
LIMIT 20
EOQ;

        return ExecuteQueryData($sql);
    }

    public function AddCharacter($requestId, $characterId, $note)
    {
        $requestId = (int) $requestId;
        $characterId = (int) $characterId;
        $note = mysql_real_escape_string($note);

        $sql = <<<EOQ
INSERT INTO
    request_characters
    (
        request_id,
        character_id,
        note
    )
VALUES
    (
        $requestId,
        $characterId,
        '$note'
    )
EOQ;

        return ExecuteQuery($sql);
    }

    public function UpdateStatus($requestId, $requestStatusId, $userId)
    {
        $request = $this->GetById($requestId);
        /* @var \classes\request\data\Request $request */
        $request->RequestStatusId = $requestStatusId;
        $request->UpdatedById = $userId;
        return $this->Save($request);
    }

    public function ListRequestAssociatedWith($characterId)
    {
        $characterId = (int) $characterId;
        $blueBook = RequestType::BlueBook;
        $terminal = implode(',', RequestStatus::$Terminal);

        $sql = <<<EOQ
SELECT
    R.id as request_id,
    R.title,
    C.character_name,
    RC.id AS request_character_id,
    RC.note,
    RC.is_approved
FROM
    requests as R
    LEFT JOIN characters AS C ON R.character_id = C.id
    LEFT JOIN request_characters AS RC ON R.id = RC.request_id
WHERE
    R.request_type_id != $blueBook
    AND R.request_status_id NOT IN ($terminal)
    AND RC.character_id = $characterId
    AND C.is_sanctioned = 'Y'
    AND C.is_deleted = 'N'
ORDER BY
    R.title
EOQ;
        return ExecuteQueryData($sql);
    }

    public function ListByByGroupsCount($groups, $filter)
    {
        $groupListPlaceholders = implode(',', array_fill(0, count($groups), '?'));

        $sql = <<<EOQ
SELECT
    COUNT(*) AS `count`
FROM
    requests as R
    LEFT JOIN characters AS C ON R.character_id = C.id
WHERE
    R.group_id IN ($groupListPlaceholders)
    AND C.is_deleted = 'N'
EOQ;
        $parameters = $groups;
        if($filter['character_name'] != '') {
            $sql .= ' AND C.character_name LIKE ? ';
            $parameters[] = $filter['character_name'] . '%';
        }
        if($filter['request_type_id'] != '0') {
            $sql .= ' AND R.request_type_id = ? ';
            $parameters[] = $filter['request_type_id'];
        }
        else {
            $sql .= ' AND R.request_type_id != ? ';
            $parameters[] = RequestType::BlueBook;
        }

        if($filter['request_status_id'] != '0') {
            $sql .= ' AND R.request_status_id = ? ';
            $parameters[] = $filter['request_status_id'];
        }
        else {
            $storytellerPlaceholders = implode(',', array_fill(0, count(RequestStatus::$Storyteller), '?'));
            $sql .= ' AND R.request_status_id IN (' . $storytellerPlaceholders . ')';
            $parameters = array_merge($parameters, RequestStatus::$Storyteller);
        }

        $count = 0;
        foreach($this->Query($sql)->All($parameters) as $row) {
            $count = $row['count'];
        }
        return $count;
    }

    public function ListBlueBookByCharacterIdCount($characterId)
    {
        $sql = <<<EOQ
SELECT
    COUNT(*) as `count`
FROM
    requests as R
WHERE
    R.character_id = ?
    AND R.request_type_id = ?
EOQ;

        $count = 0;
        foreach($this->Query($sql)->All(array($characterId, RequestType::BlueBook)) as $row) {
            $count = $row['count'];
        }
        return $count;
    }

    public function TouchRecord($requestId, $userId)
    {
        $sql = <<<EOQ
UPDATE
    requests
SET
    updated_by_id = ?,
    updated_on = now()
WHERE
    id = ?
EOQ;
        return $this->Query($sql)->Execute(array($userId, $requestId));
    }


    public function GetTimeReport()
    {
        $sql = <<<EOQ
SELECT
    character_type,
    AVG(UNIX_TIMESTAMP(first_view)-UNIX_TIMESTAMP(created)) AS first_view,
    AVG(UNIX_TIMESTAMP(terminal_status)-UNIX_TIMESTAMP(created)) AS terminal_status,
    AVG(UNIX_TIMESTAMP(closed)-UNIX_TIMESTAMP(created)) AS closed
FROM
    (
    SELECT
        C.character_type,
        (
            SELECT
                min(created_on)
            FROM
                request_status_histories AS RSH
            WHERE
                RSH.request_id = R.id
                AND RSH.request_status_id = 1
            GROUP BY
                RSH.request_id
        ) as created,
        (
            SELECT
                min(created_on)
            FROM
                request_status_histories AS RSH
            WHERE
                RSH.request_id = R.id
                AND RSH.request_status_id = 2
            GROUP BY
                RSH.request_id
        ) as first_view,
        (
            SELECT
                min(created_on)
            FROM
                request_status_histories AS RSH
            WHERE
                RSH.request_id = R.id
                AND RSH.request_status_id IN (4,5)
            GROUP BY
                RSH.request_id
        ) as terminal_status,
        (
            SELECT
                min(created_on)
            FROM
                request_status_histories AS RSH
            WHERE
                RSH.request_id = R.id
                AND RSH.request_status_id = 7
            GROUP BY
                RSH.request_id
        ) as closed
    FROM
        requests AS R
        INNER JOIN characters as C ON R.character_id = C.id
    ) AS A
WHERE
    created IS NOT NULL
GROUP BY
    character_type
EOQ;

        return $this->Query($sql)->All();
    }

    public function CountRequestsByCharacterIdAndStatus($characterId, $requestStatuses)
    {
        if(!is_array($requestStatuses)) {
            $requestStatuses = array($requestStatuses);
        }
        $statusPlaceholders = implode(',',array_fill(0, count($requestStatuses), '?'));

        $sql = <<<EOQ
SELECT
    count(*) as `total`
FROM
    requests
WHERE
    request_type_id != ?
    AND character_id = ?
    AND request_status_id IN ($statusPlaceholders)
EOQ;

        $params = array_merge(array(RequestType::BlueBook, $characterId), $requestStatuses);
        return $this->Query($sql)->Value($params);
    }

    public function GetStatusReport()
    {
        $statusParamsHolders = implode(
            ',',
            array_fill(0, count(RequestStatus::$Terminal), '?')
        );

        $sql = <<<EOQ
SELECT
    G.name as `group_name`,
    RS.name as `status_name`,
    count(*) as `total`
FROM
    requests AS R
    LEFT JOIN groups AS G ON R.group_id = G.id
    LEFT JOIN request_statuses AS RS ON R.request_status_id = RS.id
WHERE
    R.request_status_id NOT IN ($statusParamsHolders)
    AND R.request_type_id != ?
GROUP BY
  G.name,
  RS.name
EOQ;
        $params = array_merge(RequestStatus::$Terminal, array(RequestType::BlueBook));

        return $this->Query($sql)->All($params);
    }

    public function CloseRequestsForCharacter($characterId)
    {
        $characterId = (int) $characterId;
        $sql = <<<EOQ
UPDATE
    requests
SET
    request_status_id = ?
WHERE
    character_id = ?
EOQ;
        $params = array(RequestStatus::Closed, $characterId);

        return $this->Query($sql)->Execute($params);
    }

    /**
     * @param $userId
     * @param $page
     * @param $pageSize
     * @param $sort
     * @param int $statusId
     * @param $filter
     * @return \classes\request\data\Request[]
     */
    public function ListByUserId($userId, $page, $pageSize, $sort, $statusId = 0, $filter)
    {
        $openStatuses = RequestStatus::$Player;
        if($statusId != 0) {
            $openStatuses = array($statusId);
        }
        $startIndex = ($page - 1) * $pageSize;
        $sort = mysql_real_escape_string($sort);

        $sql = <<<EOQ
SELECT
    R.*,
    RT.name as request_type_name,
    RS.name as request_status_name,
    UB.username AS updated_by_username
FROM
    requests as R
    LEFT JOIN request_types AS RT ON R.request_type_id = RT.id
    LEFT JOIN request_statuses AS RS ON R.request_status_id = RS.id
    LEFT JOIN phpbb_users AS UB ON R.updated_by_id = UB.user_id
WHERE
    R.created_by_id = ?
EOQ;
        $parameters = array($userId);
        if($filter['title'] !== '') {
            $sql .= ' AND R.title LIKE ? ';
            $parameters[] = $filter['title'] . '%';
        }

        if($filter['request_type_id'] != 0) {
            $sql .= ' AND R.request_type_id = ? ';
            $parameters[] = $filter['request_type_id'];
        }
        else {
            $sql .= ' AND R.request_type_id != ? ';
            $parameters[] = RequestType::BlueBook;
        }

        if($filter['request_status_id'] != 0) {
            $sql .= ' AND R.request_status_id = ? ';
            $parameters[] = $filter['request_status_id'];
        }
        else {
            $sql .= ' AND R.request_status_id IN (' . implode(',', array_fill(0, count($openStatuses), '?')) . ') ';
            $parameters = array_merge($parameters, $openStatuses);
        }

        $sql .= <<<EOQ
        ORDER BY
    $sort
LIMIT
    $startIndex, $pageSize
EOQ;

        $list = array();
        foreach($this->Query($sql)->All($parameters) as $row) {
            $list[] = $this->PopulateObject($row);
        }

        return $list;
    }
}