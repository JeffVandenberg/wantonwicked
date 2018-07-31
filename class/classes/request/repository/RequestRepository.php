<?php
/**
 * Created by JetBrains PhpStorm.
 * User: JeffVandenberg
 * Date: 8/11/13
 * Time: 10:27 AM
 * To change this template use File | Settings | File Templates.
 */

namespace classes\request\repository;


use function array_merge;
use classes\character\data\CharacterStatus;
use classes\core\data\DataModel;
use classes\core\repository\AbstractRepository;
use classes\core\repository\RepositoryManager;
use classes\request\data\Request;
use classes\request\data\RequestCharacter;
use classes\request\data\RequestStatus;
use classes\request\data\RequestStatusHistory;
use classes\request\data\RequestType;

class RequestRepository extends AbstractRepository
{

    public function __construct()
    {
        parent::__construct(Request::class);
    }

    public function save(DataModel $request): bool
    {
        /* @var Request $request */

        $result = parent::save($request);
        if ($result) {
            $requestStatusHistory = new RequestStatusHistory();
            $requestStatusHistory->RequestId = $request->Id;
            $requestStatusHistory->RequestStatusId = $request->RequestStatusId;
            $requestStatusHistory->CreatedById = $request->UpdatedById;
            $requestStatusHistory->CreatedOn = date('Y-m-d H:i:s');
            $requestStatusHistoryRepository = RepositoryManager::getRepository(RequestStatusHistory::class);
            return $requestStatusHistoryRepository->save($requestStatusHistory);
        }
        return $result;
    }

    public function findById($id)
    {
        $id = (int)$id;
        $sql = <<<EOQ
SELECT
    R.*,
    G.name AS group_name,
    U.username
FROM
    requests AS R
    LEFT JOIN groups AS G ON R.group_id = G.id
    LEFT JOIN phpbb_users AS U ON R.created_by_id = U.user_id
WHERE
    R.id = ?
EOQ;
        $params = array($id);

        return $this->query($sql)->single($params);
    }

    public function listByCharacterId($characterId, $page, $pageSize, $sort, $statusId = 0, array $filter = []): array
    {
        $openStatuses = RequestStatus::$Player;
        if ((int)$statusId !== 0) {
            $openStatuses = array($statusId);
        }
        $startIndex = ($page - 1) * $pageSize;

        $sql = <<<EOQ
SELECT
    R.*,
    RT.name AS request_type_name,
    RS.name AS request_status_name,
    UB.username AS updated_by_username
FROM
    requests AS R
    LEFT JOIN request_characters AS RC ON R.id = RC.request_id
    LEFT JOIN request_types AS RT ON R.request_type_id = RT.id
    LEFT JOIN request_statuses AS RS ON R.request_status_id = RS.id
    LEFT JOIN phpbb_users AS UB ON R.updated_by_id = UB.user_id
WHERE
    RC.character_id = ?
    AND RC.is_primary = 1
EOQ;
        $parameters = array($characterId);
        if ($filter['title'] !== '') {
            $sql .= ' AND R.title LIKE ? ';
            $parameters[] = $filter['title'] . '%';
        }

        if ((int)$filter['request_type_id'] !== 0) {
            $sql .= ' AND R.request_type_id = ? ';
            $parameters[] = $filter['request_type_id'];
        } else {
            $sql .= ' AND R.request_type_id != ? ';
            $parameters[] = RequestType::BLUEBOOK;
        }

        if ((int)$filter['request_status_id'] !== 0) {
            $sql .= ' AND R.request_status_id = ? ';
            $parameters[] = $filter['request_status_id'];
        } else {
            $sql .= ' AND R.request_status_id IN (' . implode(',', array_fill(0, count($openStatuses), '?')) . ') ';
            $parameters = array_merge($parameters, $openStatuses);
        }

        $sql .= <<<EOQ
        ORDER BY
    $sort
LIMIT
    $startIndex, $pageSize
EOQ;

        return $this->query($sql)->all($parameters);
    }


    public function listByCharacterIdCount($characterId, $statusId, $filter)
    {
        $characterId = (int)$characterId;

        $openStatuses = RequestStatus::$Player;
        if ((int)$statusId !== 0) {
            $openStatuses = array($statusId);
        }

        $sql = <<<EOQ
SELECT
    COUNT(*) AS `count`
FROM
    requests AS R
    LEFT JOIN request_characters AS RC ON R.id = RC.request_id
WHERE
    RC.character_id = ?
    AND RC.is_primary = 1
EOQ;
        $parameters = array($characterId);
        if ($filter['title'] !== '') {
            $sql .= ' AND R.title LIKE ? ';
            $parameters[] = $filter['title'] . '%';
        }

        if ((int)$filter['request_type_id'] !== 0) {
            $sql .= ' AND R.request_type_id = ? ';
            $parameters[] = $filter['request_type_id'];
        } else {
            $sql .= ' AND R.request_type_id != ? ';
            $parameters[] = RequestType::BLUEBOOK;
        }

        if ((int)$filter['request_status_id'] !== 0) {
            $sql .= ' AND R.request_status_id = ? ';
            $parameters[] = $filter['request_status_id'];
        } else {
            $sql .= ' AND R.request_status_id IN (' . implode(',', array_fill(0, count($openStatuses), '?')) . ') ';
            $parameters = array_merge($parameters, $openStatuses);
        }

        return $this->query($sql)->value($parameters);
    }

    public function mayViewRequest($requestId, $userId): bool
    {
        $requestId = (int)$requestId;
        $userId = (int)$userId;

        $sql = <<<EOQ
SELECT
    count(*) AS `rows`
FROM
    requests AS R
    LEFT JOIN request_characters AS RC ON R.id = RC.request_id
    LEFT JOIN characters AS C ON RC.character_id = C.id
WHERE
    R.id = ?
    AND (
        R.created_by_id = ?
        OR
        C.user_id = ?
    )
EOQ;
        $parameters = array($requestId, $userId, $userId);
        $rows = $this->query($sql)->value($parameters);
        return ($rows > 0);
    }

    public function getOpenRequestsNotAttachedToRequest($requestId, $characterId): array
    {
        $requestId = (int)$requestId;
        $characterId = (int)$characterId;
        $sql = <<<EOQ
SELECT
    R.*
FROM
    requests AS R
    LEFT JOIN request_characters AS RC ON R.id = RC.request_id
    LEFT JOIN request_requests AS RR ON (R.id = RR.from_request_id AND RR.to_request_id = ?)
WHERE
    RR.to_request_id IS NULL
    AND RC.character_id = ?
    AND R.id != ?
    AND R.request_type_id != ?
ORDER BY
    title
EOQ;
        $params = array(
            $requestId,
            $characterId,
            $requestId,
            $bluebook
        );
        return $this->query($sql)->all($params);
    }

    public function attachRequestToRequest($requestId, $fromRequestId): int
    {
        $requestId = (int)$requestId;
        $fromRequestId = (int)$fromRequestId;

        $sql = <<<EOQ
INSERT INTO
    request_requests
    (
        from_request_id,
        to_request_id
    )
VALUES
    (
        ?,
        ?
    )
EOQ;
        $params = array(
            $fromRequestId,
            $requestId
        );
        return $this->query($sql)->execute($params);
    }

    public function listSupportingRequests($requestId): array
    {
        $requestId = (int)$requestId;
        $bluebook = RequestType::BLUEBOOK;

        $sql = <<<EOQ
SELECT
    R.*
FROM
    request_requests AS RR
    LEFT JOIN requests AS R ON RR.from_request_id = R.id
WHERE
    RR.to_request_id = ?
    AND R.request_type_id != ?
ORDER BY
    R.title
EOQ;
        $params = array(
            $requestId,
            $bluebook
        );
        return $this->query($sql)->all($params);
    }

    public function listOpenRequestsForCharacter($characterId): array
    {
        $characterId = (int)$characterId;
        $blueBook = RequestType::BLUEBOOK;
        $edittablePlaceholders = implode(',', array_fill(0, count(RequestStatus::$Terminal), '?'));
        $sql = <<<EOQ
SELECT
    R.*
FROM
    requests AS R
    LEFT JOIN request_characters AS RC ON R.id = RC.request_id
WHERE
    request_status_id NOT IN ($edittablePlaceholders)
    AND request_type_id != ?
    AND RC.character_id = ?
ORDER BY
    title
EOQ;
        $params = array_merge(RequestStatus::$Terminal, array($blueBook, $characterId));
        return $this->query($sql)->all($params);
    }

    public function attachRollToRequest($requestId, $rollId): int
    {
        $requestId = (int)$requestId;
        $rollId = (int)$rollId;

        $sql = <<<EOQ
INSERT INTO
    request_rolls
    (
        request_id,
        roll_id
    )
VALUES
    (
        ?,
        ?
    )
EOQ;

        $params = array(
            $requestId,
            $rollId
        );
        return $this->query($sql)->execute($params);
    }

    public function listSupportingRolls($requestId): array
    {
        $requestId = (int)$requestId;
        $sql = <<<EOQ
SELECT
    WD.*
FROM
    wod_dierolls AS WD
    INNER JOIN request_rolls AS RR ON WD.roll_id = RR.roll_id
WHERE
    RR.request_id = ?
ORDER BY
    roll_date DESC
EOQ;
        $params = array(
            $requestId
        );
        return $this->query($sql)->all($params);
    }

    public function listBlueBookByCharacterId($characterId, $page, $pageSize, $sort): array
    {
        $startIndex = ($page - 1) * $pageSize;

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

        return $this->query($sql)->all(array($characterId, RequestType::BLUEBOOK));
    }

    public function listBlueBookEntriesNotAttachedToRequest($requestId, $characterId): array
    {
        $requestId = (int)$requestId;
        $characterId = (int)$characterId;
        $bluebook = RequestType::BLUEBOOK;

        $sql = <<<EOQ
SELECT
    R.*
FROM
    requests AS R
    LEFT JOIN request_bluebooks AS RB ON (R.id = RB.bluebook_id AND RB.request_id = ?)
WHERE
    RB.request_id IS NULL
    AND R.character_id = ?
    AND R.id != ?
    AND R.request_type_id = ?
ORDER BY
    title
EOQ;
        $params = array(
            $requestId,
            $characterId,
            $requestId,
            $bluebook
        );
        return $this->query($sql)->all($params);
    }

    public function attachBluebookToRequest($requestId, $bluebookId): int
    {
        $requestId = (int)$requestId;
        $bluebookId = (int)$bluebookId;

        $sql = <<<EOQ
INSERT INTO
    request_bluebooks
    (
        request_id,
        bluebook_id
    )
VALUES
    (
        ?,
        ?
    )
EOQ;
        $params = array(
            $requestId,
            $bluebookId
        );
        return $this->query($sql)->execute($params);
    }

    public function listSupportingBluebookEntries($requestId): array
    {
        $requestId = (int)$requestId;
        $bluebook = RequestType::BLUEBOOK;

        $sql = <<<EOQ
SELECT
    R.*
FROM
    request_bluebooks AS RB
    LEFT JOIN requests AS R ON RB.bluebook_id = R.id
WHERE
    RB.request_id = ?
    AND R.request_type_id = ?
ORDER BY
    R.title
EOQ;
        $params = array(
            $requestId,
            $bluebook
        );
        return $this->query($sql)->all($params);
    }

    public function listByGroups($groups, $page, $pageSize, $sort, $filter): array
    {
        $groupListPlaceholders = implode(',', array_fill(0, count($groups), '?'));

        $startIndex = ($page - 1) * $pageSize;

        $storytellerPlaceholders = implode(',', array_fill(0, count(RequestStatus::$Storyteller), '?'));

        $sql = <<<EOQ
SELECT
    R.*,
    G.name AS group_name,
    RT.name as request_type_name,
    RS.name as request_status_name,
    UB.username AS updated_by_username,
    CB.username as created_by_username
FROM
    requests as R
    LEFT JOIN request_types AS RT ON R.request_type_id = RT.id
    LEFT JOIN request_statuses AS RS ON R.request_status_id = RS.id
    LEFT JOIN phpbb_users AS UB ON R.updated_by_id = UB.user_id
    LEFT JOIN groups as G ON R.group_id = G.id
    LEFT JOIN phpbb_users as CB ON R.created_by_id = CB.user_id
WHERE
    R.group_id IN ($groupListPlaceholders)
EOQ;
        $parameters = $groups;

        if ($filter['username'] !== '') {
            $sql .= ' AND CB.username_clean LIKE ? ';
            $parameters[] = strtolower($filter['username']) . '%';
        }
        if ((int)$filter['request_type_id'] !== 0) {
            $sql .= ' AND R.request_type_id = ? ';
            $parameters[] = $filter['request_type_id'];
        } else {
            $sql .= ' AND R.request_type_id != ? ';
            $parameters[] = RequestType::BLUEBOOK;
        }

        if ($filter['request_group_id']) {
            $sql .= ' AND R.group_id = ? ';
            $parameters[] = $filter['request_group_id'];
        }

        if ($filter['title'] !== '') {
            $sql .= ' AND R.title LIKE ? ';
            $parameters[] = $filter['title'] . '%';
        }

        if ((int)$filter['request_status_id'] !== 0) {
            if ((int)$filter['request_status_id'] !== -1) {
                $sql .= ' AND R.request_status_id = ? ';
                $parameters[] = $filter['request_status_id'];
            }
        } else {
            $sql .= ' AND R.request_status_id IN (' . $storytellerPlaceholders . ') ';
            $parameters = array_merge($parameters, RequestStatus::$Storyteller);
        }


        $sql .= <<<EOQ
ORDER BY
    $sort
LIMIT
    $startIndex, $pageSize
EOQ;

        return $this->query($sql)->all($parameters);
    }

    public function submit($id): int
    {
        $submitted = RequestStatus::SUBMITTED;
        $id = (int)$id;
        $sql = <<<EOQ
UPDATE
    requests
SET
    request_status_id = ?
WHERE
    id = ?;
EOQ;
        $params = array(
            $submitted,
            $id
        );
        return $this->query($sql)->execute($params);
    }

    public function searchCharactersForRequest($onlySanctioned, $characterName): array
    {
        $onlySanctioned = (int)$onlySanctioned;
        $placeholders = $this->buildPlaceholdersForValues(CharacterStatus::Sanctioned);
        $sql = <<<EOQ
SELECT
    C.character_name,
    C.id
FROM
    characters AS C
WHERE
    (
        (C.character_status_id IN ($placeholders) AND (? = 1))
        OR
        (? = 0)
    )
    AND C.character_status_id != ?
    AND C.character_name like ?
ORDER BY
    C.character_name
LIMIT 20
EOQ;

        $params = array_merge(
            CharacterStatus::Sanctioned,
            [
                $onlySanctioned,
                $onlySanctioned,
                CharacterStatus::DELETED,
                $characterName . '%'
            ]);

        return $this->query($sql)->all($params);
    }

    public function addCharacter($requestId, $characterId, $isPrimary): bool
    {
        $requestCharacter = new RequestCharacter();
        $requestCharacter->RequestId = $requestId;
        $requestCharacter->CharacterId = $characterId;
        $requestCharacter->IsPrimary = ($isPrimary) ? 1 : 0;
        $requestCharacter->Note = '';
        $requestCharacter->IsApproved = 0;

        $requestCharacterRepository = new RequestCharacterRepository();
        return $requestCharacterRepository->save($requestCharacter);
    }

    public function updateStatus($requestId, $requestStatusId, $userId): bool
    {
        $request = $this->getById($requestId);
        /* @var \classes\request\data\Request $request */
        $request->RequestStatusId = $requestStatusId;
        $request->UpdatedById = $userId;
        return $this->save($request);
    }

    public function listRequestAssociatedWith($characterId): array
    {
        $characterId = (int)$characterId;
        $blueBook = RequestType::BLUEBOOK;
        $terminalPlaceholders = $this->buildPlaceholdersForValues(RequestStatus::$Terminal);
        $sanctionedPlaceholders = $this->buildPlaceholdersForValues(CharacterStatus::Sanctioned);
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
    LEFT JOIN request_characters AS RC ON R.id = RC.request_id
    LEFT JOIN characters AS C ON RC.character_id = C.id
WHERE
    R.request_status_id NOT IN ($terminalPlaceholders)
    AND R.request_type_id != ?
    AND RC.character_id = ?
    AND C.character_status_id IN ($sanctionedPlaceholders)
    AND RC.is_primary = 0
ORDER BY
    R.title
EOQ;
        $params = array_merge(RequestStatus::$Terminal, [$blueBook, $characterId], CharacterStatus::Sanctioned);
        return $this->query($sql)->all($params);
    }

    public function listByByGroupsCount($groups, $filter)
    {
        $groupListPlaceholders = implode(',', array_fill(0, count($groups), '?'));

        $sql = <<<EOQ
SELECT
    COUNT( * ) AS `count`
FROM
    requests as R
    LEFT JOIN phpbb_users AS U ON R.created_by_id = U.user_id
WHERE
    R.group_id IN ($groupListPlaceholders)
EOQ;
        $parameters = $groups;
        if ($filter['username'] != '') {
            $sql .= ' AND U.username_clean LIKE ? ';
            $parameters[] = strtolower($filter['username']) . '%';
        }
        if ($filter['request_type_id'] != '0') {
            $sql .= ' AND R.request_type_id = ? ';
            $parameters[] = $filter['request_type_id'];
        } else {
            $sql .= ' AND R.request_type_id != ? ';
            $parameters[] = RequestType::BLUEBOOK;
        }

        if ($filter['request_status_id'] > 0) {
            $sql .= ' AND R.request_status_id = ? ';
            $parameters[] = $filter['request_status_id'];
        } else if ($filter['request_status_id'] != -1) {
            $storytellerPlaceholders = implode(',', array_fill(0, count(RequestStatus::$Storyteller), '?'));
            $sql .= ' AND R.request_status_id IN (' . $storytellerPlaceholders . ')';
            $parameters = array_merge($parameters, RequestStatus::$Storyteller);
        }

        return $this->query($sql)->value($parameters);
    }

    public function listBlueBookByCharacterIdCount($characterId)
    {
        $sql = <<<EOQ
SELECT
    COUNT(*) AS `count`
FROM
    requests AS R
WHERE
    R.character_id = ?
    AND R.request_type_id = ?
EOQ;

        $count = 0;
        foreach ($this->query($sql)->all(array($characterId, RequestType::BLUEBOOK)) as $row) {
            $count = $row['count'];
        }
        return $count;
    }

    public function touchRecord($requestId, $userId): int
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
        return $this->query($sql)->execute(array($userId, $requestId));
    }


    public function getTimeReport(): array
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
        ) AS created,
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
        ) AS first_view,
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
        ) AS terminal_status,
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
        ) AS closed
    FROM
        requests AS R
        INNER JOIN characters AS C ON R.character_id = C.id
    ) AS A
WHERE
    created IS NOT NULL
GROUP BY
    character_type
EOQ;

        return $this->query($sql)->all();
    }

    public function countRequestsByCharacterIdAndStatus($characterId, $requestStatuses)
    {
        if (!\is_array($requestStatuses)) {
            $requestStatuses = array($requestStatuses);
        }
        $statusPlaceholders = implode(',', array_fill(0, count($requestStatuses), '?'));

        $sql = <<<EOQ
SELECT
    count(*) as `total`
FROM
    requests AS R
    LEFT JOIN request_characters AS RC ON R.id = RC.request_id
WHERE
    request_type_id != ?
    AND RC.character_id = ?
    AND RC.is_primary = 1
    AND request_status_id IN ($statusPlaceholders)
EOQ;

        $params = array_merge(array(RequestType::BLUEBOOK, $characterId), $requestStatuses);
        return $this->query($sql)->value($params);
    }

    public function getStatusReport(): array
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
        $params = array_merge(RequestStatus::$Terminal, array(RequestType::BLUEBOOK));

        return $this->query($sql)->all($params);
    }

    public function closeRequestsForCharacter($characterIds): int
    {
        if (!$characterIds || count($characterIds) === 0) {
            return 0;
        }
        if (!\is_array($characterIds)) {
            $characterIds = array($characterIds);
        }
        $characterIdPlaceholders = $this->buildPlaceholdersForValues($characterIds);

        $sql = <<<EOQ
UPDATE
    requests AS R
    LEFT JOIN request_characters AS RC ON R.id = RC.request_id
SET
    request_status_id = ?
WHERE
    RC.character_id IN ($characterIdPlaceholders)
    AND RC.is_primary = 1
EOQ;
        $params = array_merge(array(RequestStatus::CLOSED), $characterIds);

        return $this->query($sql)->execute($params);
    }

    /**
     * @param $userId
     * @param $page
     * @param $pageSize
     * @param $sort
     * @param $filter
     * @return \classes\request\data\Request[]
     */
    public function listByUserId($userId, $page, $pageSize, $sort, $filter): array
    {
        $openStatuses = RequestStatus::$Player;
        $startIndex = ($page - 1) * $pageSize;

        $sql = <<<EOQ
SELECT
    R.*,
    RT.name AS request_type_name,
    RS.name AS request_status_name,
    UB.username AS updated_by_username
FROM
    requests AS R
    LEFT JOIN request_types AS RT ON R.request_type_id = RT.id
    LEFT JOIN request_statuses AS RS ON R.request_status_id = RS.id
    LEFT JOIN phpbb_users AS UB ON R.updated_by_id = UB.user_id
WHERE
    R.created_by_id = ?
EOQ;
        $parameters = array($userId);
        if (isset($filter['title']) && $filter['title'] !== '') {
            $sql .= ' AND R.title LIKE ? ';
            $parameters[] = $filter['title'] . '%';
        }

        if (isset($filter['request_type_id']) && $filter['request_type_id'] != 0) {
            $sql .= ' AND R.request_type_id = ? ';
            $parameters[] = $filter['request_type_id'];
        } else {
            $sql .= ' AND R.request_type_id != ? ';
            $parameters[] = RequestType::BLUEBOOK;
        }

        if (isset($filter['request_status_id']) && $filter['request_status_id'] != 0) {
            $sql .= ' AND R.request_status_id = ? ';
            $parameters[] = $filter['request_status_id'];
        } else {
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
        foreach ($this->query($sql)->all($parameters) as $row) {
            $list[] = $this->populateObject($row);
        }

        return $list;
    }

    public function getOpenByUserId($userId)
    {
        $statusPlaceholders = implode(',', array_fill(0, count(RequestStatus::$Player), '?'));
        $sql = <<<EOQ
SELECT
    count(*)
FROM
    requests
WHERE
    request_type_id != ?
    AND created_by_id = ?
    AND request_status_id IN ($statusPlaceholders)
EOQ;
        $params = array_merge(array(
            RequestType::BLUEBOOK,
            $userId
        ), RequestStatus::$Player);
        return $this->query($sql)->value($params);
    }

    /**
     * @param $userId
     * @param $startDate
     * @param $endDate
     * @return array
     */
    public function getSTActivityReport($userId, $startDate, $endDate): array
    {
        $sql = <<<EOQ
SELECT
    U.user_id,
    U.username,
    RS.name AS status_name,
    COUNT(*) AS total
FROM
    phpbb_users AS U
    INNER JOIN gm_permissions AS P ON U.user_id = P.ID
    LEFT JOIN request_status_histories AS RSH ON U.user_id = RSH.created_by_id
    LEFT JOIN request_statuses AS RS ON RSH.request_status_id = RS.id
WHERE
    RSH.created_on >= ?
    AND RSH.created_on <= ?
EOQ;
        $params = array($startDate, $endDate);

        if ($userId) {
            $sql .= ' AND U.user_id = ? ';
            $params[] = $userId;
        }

        $sql .= <<<EOQ
 GROUP BY
    U.user_id,
    RS.id
ORDER BY
    U.username,
    RS.name
EOQ;

        return $this->query($sql)->all($params);
    }

    public function requestHasPrimaryCharacter($requestId): bool
    {
        $sql = <<<EOQ
SELECT
    count(*)
FROM
    request_characters
WHERE
    request_id = ?
    AND is_primary = 1
EOQ;

        $params = array($requestId);

        return ($this->query($sql)->value($params) > 0);
    }

    public function listRequestsLinkedByCharacterForUser($userId): array
    {
        $requestStatusPlaceholders = implode(',', array_fill(0, count(RequestStatus::$Player), '?'));
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
    LEFT JOIN request_characters AS RC ON R.id = RC.request_id
    LEFT JOIN characters AS C ON RC.character_id = C.id
WHERE
    C.user_id = ?
    AND RC.is_primary = 0
    AND R.request_type_id != ?
    AND R.request_status_id IN ($requestStatusPlaceholders)
ORDER BY
    R.updated_on DESC
EOQ;

        $params = array_merge(array(
            $userId,
            RequestType::BLUEBOOK
        ), RequestStatus::$Player);

        $list = array();

        foreach ($this->query($sql)->all($params) as $row) {
            $list[] = $this->populateObject($row);
        }

        return $list;
    }

    public function mayEditRequest($requestId, $userId): bool
    {
        $requestId = (int)$requestId;
        $userId = (int)$userId;

        $sql = <<<EOQ
SELECT
    count(*) AS `rows`
FROM
    requests AS R
WHERE
    R.id = ?
    AND R.created_by_id = ?
EOQ;
        $parameters = array($requestId, $userId);
        $rows = $this->query($sql)->value($parameters);
        return ($rows > 0);
    }

    public function attachSceneToRequest($requestId, $sceneId, $note): int
    {
        $sql = <<<EOQ
INSERT INTO
  scene_requests
  (scene_id, request_id, note, added_on)
VALUES
  (?, ?, ?, ?)
EOQ;

        $params = array(
            $sceneId,
            $requestId,
            $note,
            date('Y-m-d H:i:s')
        );

        return $this->query($sql)->execute($params);
    }

    public function listSupportingScenes($requestId): array
    {
        $sql = <<<EOQ
SELECT
    S.name,
    S.slug,
    SR.note
FROM
    scene_requests AS SR
    INNER JOIN scenes AS S ON SR.scene_id = S.id
WHERE
    SR.request_id = ?
ORDER BY
    S.name
EOQ;
        $params = array($requestId);

        return $this->query($sql)->all($params);
    }

    public function getNewStRequests($userId)
    {
        $sql = <<<EOQ
SELECT
	count(*) AS `total`
FROM
	requests AS R
	LEFT JOIN groups AS G ON R.group_id = G.id
	LEFT JOIN st_groups AS SG ON G.id = SG.group_id
WHERE
	SG.user_id = ?
	AND R.request_status_id IN (?, ?)
    AND R.request_type_id != ?
EOQ;
        $params = array(
            $userId,
            RequestStatus::SUBMITTED,
            RequestStatus::IN_PROGRESS,
            RequestType::BLUEBOOK
        );
        return $this->query($sql)->value($params);
    }

    public function listEmailsForUsersInGroup($groupId): array
    {
        $sql = <<<EOQ
SELECT
 U.user_email
FROM
 phpbb_users AS U
 LEFT JOIN st_groups AS SG ON U.user_id = SG.user_id
WHERE
 SG.group_id = ?
EOQ;
        $params = [
            $groupId
        ];

        return $this->query($sql)->all($params);
    }

    public function findStRequestDashboard($user_id): array
    {
        $sql = <<<EOQ
SELECT
    G.name AS group_name,
    RS.id AS request_status_id,
    RS.name AS request_status_name,
	count(*) AS `total`
FROM
	requests AS R
	LEFT JOIN groups AS G ON R.group_id = G.id
	LEFT JOIN st_groups AS SG ON G.id = SG.group_id
	LEFT JOIN request_statuses AS RS ON R.request_status_id = RS.id
WHERE
	SG.user_id = ?
	AND R.request_status_id IN (2,5)
GROUP BY
    G.name,
    RS.id,
    RS.name
ORDER BY
    G.name,
    RS.name
EOQ;
        $params = [
            $user_id
        ];

        return $this->query($sql)->all($params);
    }

    public function countByUserId($userId, $filter)
    {
        $openStatuses = RequestStatus::$Player;

        $sql = <<<EOQ
SELECT
    count(*)
FROM
    requests AS R
WHERE
    R.created_by_id = ?
EOQ;
        $parameters = array($userId);
        $sql .= ' AND R.request_type_id != ? ';
        $parameters[] = RequestType::BLUEBOOK;

        $sql .= ' AND R.request_status_id IN (' . implode(',', array_fill(0, count($openStatuses), '?')) . ') ';
        $parameters = array_merge($parameters, $openStatuses);

        return $this->query($sql)->value($parameters);
    }

    public function listForDashboard($id): array
    {
        $statuses = [
            RequestStatus::NEW_REQUEST,
            RequestStatus::SUBMITTED,
            RequestStatus::IN_PROGRESS,
            RequestStatus::RETURNED
        ];
        $placeholders = implode(',', array_fill(0, count($statuses), '?'));
        $sql = <<<SQL
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
    AND R.request_type_id != ?
    AND R.request_status_id IN ($placeholders)
ORDER BY R.title
SQL;
        $params = array_merge([
            $id,
            RequestType::BLUEBOOK
        ], $statuses);

        return $this->query($sql)->all($params);
    }

    public function countOpenForUser($userId)
    {
        $sql = <<<SQL
SELECT
  count(*)
FROM
  requests
WHERE
  created_by_id = ?
  AND request_type_id != 4
  AND requests.request_status_id IN (1,2,3,4,5,6)
SQL;
        $params = [
            $userId
        ];

        return $this->query($sql)->value($params);
    }

    public function countNewStRequests($userId)
    {
        $sql = <<<SQL
SELECT
	count(*) AS `total`
FROM
	requests AS R
	LEFT JOIN groups AS G ON R.group_id = G.id
	LEFT JOIN st_groups AS SG ON G.id = SG.group_id
WHERE
	SG.user_id = ?
	AND R.request_status_id IN (2,6)
    AND R.request_type_id != 4
SQL;
        $params = [
            $userId
        ];

        return $this->query($sql)->value($params);
    }

    public function summaryOfRequestTypesByCharacterId($characterId): array
    {
        $inProgressStatuses = [
            RequestStatus::SUBMITTED,
            RequestStatus::IN_PROGRESS,
            RequestStatus::RETURNED,
        ];
        $openPlaceHolders = $this->buildPlaceholdersForValues($inProgressStatuses);

        $sql = <<<SQL
SELECT
    RT.name AS request_type_name,
    count(R.id) as total
FROM
    requests AS R
    LEFT JOIN request_characters AS RC ON R.id = RC.request_id
    LEFT JOIN request_types AS RT ON R.request_type_id = RT.id
    LEFT JOIN request_statuses AS RS ON R.request_status_id = RS.id
WHERE
    RC.character_id = ?
    AND RC.is_primary = 1
    AND R.request_status_id IN ($openPlaceHolders)
GROUP BY 
    request_type_name
SQL;
        $params = array_merge([$characterId], $inProgressStatuses);

        return $this->query($sql)->all($params);

    }
}
