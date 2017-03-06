<?php
use classes\core\helpers\Request;
use classes\core\helpers\Response;
use classes\core\helpers\UserdataHelper;
use classes\core\repository\Database;
use classes\core\repository\RepositoryManager;
use classes\request\repository\RequestRepository;

/* @var array $userdata */

if(!UserdataHelper::IsAdmin($userdata)) {
    Response::redirect('/', 'You do not have permission to this page.');
}

$page_title = $contentHeader = "ST Activity Report";

// get list of STs
$users = array();

$sql = <<<EOQ
SELECT
    user_id,
    username
FROM
    phpbb_users AS U
WHERE
    U.user_id IN (
        SELECT DISTINCT user_id FROM permissions_users
    )
ORDER BY
    username
EOQ;

$users = array();
foreach(Database::getInstance()->query($sql)->all() as $row) {
    $users[$row['user_id']] = $row;
}

$userIds = array_keys($users);
$userIdPlaceholders = implode(',', array_fill(0, count($userIds), '?'));

// get count of requests processed in the interval
$requestRepository = RepositoryManager::GetRepository('classes\request\data\Request');
/* @var RequestRepository $requestRepository */

$startDate = Request::getValue('start_date', date('Y-m-d', strtotime('-7 days')));
$endDate   = Request::getValue('end_date', date('Y-m-d'));
$requestRows = $requestRepository->GetSTActivityReport(null, $startDate, $endDate);

foreach($requestRows as $row) {
    $users[$row['user_id']]['number_of_requests'] += $row['total'];
}

// get scenes run in the interval
$sql = <<<EOQ
SELECT
    run_by_id AS user_id,
    count(*) as total
FROM
    scenes
WHERE
    run_by_id IN ($userIdPlaceholders)
    AND run_on_date >= ?
GROUP BY
    run_by_id
EOQ;

$monthStart = date('Y-m-01', strtotime('-1 month'));
$params = array_merge($userIds, array($monthStart));
foreach(Database::getInstance()->query($sql)->all($params) as $row) {
    $users[$row['user_id']]['number_of_scenes'] += $row['total'];
}

// output
ob_start();
?>
<table>
    <thead>
    <tr>
        <th>
            Username
        </th>
        <th>
            Requests (1 week)
        </th>
        <th>
            Scenes Run (since <?php echo $monthStart; ?>)
        </th>
    </tr>
    </thead>
    <?php foreach($users as $user): ?>
        <tr>
            <td>
                <?php echo $user['username']; ?>
            </td>
            <td>
                <?php echo $user['number_of_requests']; ?>
            </td>
            <td>
                <?php echo $user['number_of_scenes']; ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
<?php
$page_content = ob_get_clean();
