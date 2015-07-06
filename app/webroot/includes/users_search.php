<?php
use classes\core\helpers\Request;
use classes\core\helpers\Response;
use classes\core\repository\Database;

Request::PreventCache();
$email = Request::GetValue('email', false);

if ($email) {
    $query = <<<EOQ
SELECT
    user_id,
    username,
    user_email
FROM
    phpbb_users
WHERE
    username_clean LIKE :term
    or user_email LIKE :term
ORDER BY
    username
LIMIT 20;
EOQ;
}
else {
    $query = <<<EOQ
SELECT
    user_id,
    username
FROM
    phpbb_users
WHERE
    username_clean LIKE :term
ORDER BY
    username
LIMIT 20;
EOQ;
}
$db    = new Database();
$users = $db->Query($query)->Bind('term', strtolower($_GET['term']) . '%')->All();

$list = array();
foreach ($users as $row) {
    $row_array['value'] = $row['user_id'];
    if ($email) {
        $row_array['label'] = $row['username'] . ' (' . $row['user_email'] . ')';
    }
    else {
        $row_array['label'] = $row['username'];
    }

    $list[] = $row_array;
}

if (count($list) == 0) {
    $list[] = array("value" => '0', 'label' => 'No Matches');
}

Response::SendJson($list);