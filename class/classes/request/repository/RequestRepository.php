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
}
