<?php
/**
 * Created by PhpStorm.
 * User: JeffVandenberg
 * Date: 3/30/14
 * Time: 5:09 PM
 */

use classes\core\helpers\Request;
use classes\core\repository\Database;

require_once('cgi-bin/start_of_page.php');

$roomId = Request::GetValue('roomId', 1);
$userId = Request::GetValue('userId');
$startDate = Request::GetValue('start_date');
$endDate = Request::GetValue('end_date');

$query = '';
$params = array();
if($userId) {
    $search1 = <<<EOQ
SELECT
    M.*,
    TU.username AS to_username,
    FU.username AS from_username,
    R.roomname
FROM
    prochatrooms_message_inno AS M
    LEFT JOIN prochatrooms_users AS TU ON M.to_user_id = TU.id
    LEFT JOIN prochatrooms_users AS FU ON M.uid = FU.id
    LEFT JOIN prochatrooms_rooms AS R ON M.room = R.id
WHERE
    M.uid = ?
EOQ;
    $params1 = array($userId);

    $search2 = <<<EOQ
SELECT
    M.*,
    TU.username AS to_username,
    FU.username AS from_username,
    R.roomname
FROM
    prochatrooms_message_inno AS M
    LEFT JOIN prochatrooms_users AS TU ON M.to_user_id = TU.id
    LEFT JOIN prochatrooms_users AS FU ON M.uid = FU.id
    LEFT JOIN prochatrooms_rooms AS R ON M.room = R.id
WHERE
    M.to_user_id = ?
EOQ;
    $params2 = array($userId);

    if($roomId) {
        $search1 .= ' AND M.room = ? ';
        $search2 .= ' AND M.room = ? ';
        $params1[] = $roomId;
        $params2[] = $roomId;
    }

    if($startDate) {
        $search1 .= ' AND M.messtime >= ? ';
        $search2 .= ' AND M.messtime >= ? ';
        $params1[] = strtotime($startDate);
        $params2[] = strtotime($startDate);
    }

    if($endDate) {
        $search1 .= ' AND M.messtime <= ? ';
        $search2 .= ' AND M.messtime <= ? ';
        $params1[] = strtotime($endDate);
        $params2[] = strtotime($endDate);
    }

    $query = <<<EOQ
$search1
UNION
$search2
ORDER BY
    id
LIMIT
    0, 100
EOQ;

    $params = array_merge($params1, $params2);
}
else {
    // non user specific search
    $query = <<<EOQ
SELECT
    M.*,
    TU.username AS to_username,
    FU.username AS from_username,
    R.roomname
FROM
    prochatrooms_message_inno AS M
    LEFT JOIN prochatrooms_users AS TU ON M.to_user_id = TU.id
    LEFT JOIN prochatrooms_users AS FU ON M.uid = FU.id
    LEFT JOIN prochatrooms_rooms AS R ON M.room = R.id
WHERE
    1 = 1
EOQ;

    $params = array();
    if($roomId) {
        $query .= ' AND M.room = ? ';
        $params[] = $roomId;
    }

    if($startDate) {
        $query .= ' AND M.messtime >= ? ';
        $params[] = strtotime($startDate);
    }

    if($endDate) {
        $query .= ' AND M.messtime <= ? ';
        $params[] = strtotime($endDate);
    }


    $query .= <<<EOQ
 ORDER BY
    M.id
LIMIT
    0, 100
EOQ;
}
$startTime = microtime(true);
$result = Database::GetInstance()->Query($query)->All($params);
$totalTime = microtime(true) - $startTime;

?>

<h3><?php echo $totalTime; ?></h3>
<form method="post">
    User
    <input name="userId" value="<?php echo $userId; ?>">
    Room
    <input name="roomId" value="<?php echo $roomId; ?>">
    Start Date
    <input name="start_date" value="<?php echo $startDate; ?>">
    End Date
    <input name="end_date" value="<?php echo $endDate; ?>">
    <input type="submit" value="Search">
</form>
<table border="1">
    <tr>
        <?php foreach($result[0] as $key => $value): ?>
            <th>
                <?php echo $key; ?>
            </th>
        <?php endforeach; ?>
    </tr>
<?php foreach($result as $row): ?>
    <tr>
        <?php foreach($row as $key => $value): ?>
            <td>
                <?php echo $value; ?>
            </td>
        <?php endforeach; ?>
    </tr>
<?php endforeach; ?>
</table>