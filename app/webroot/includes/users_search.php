<?php
use classes\core\helpers\Request;
use classes\core\helpers\Response;
use classes\core\repository\Database;

Request::preventCache();
$email = Request::getValue('email', false);

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
$term = $_GET['query'];
$users = $db->query($query)->bind('term', strtolower($term) . '%')->all();

$list = array();
foreach ($users as $row) {
    $row_array['data'] = $row['user_id'];
    if ($email) {
        $row_array['value'] = $row['username'] . ' (' . $row['user_email'] . ')';
    }
    else {
        $row_array['value'] = $row['username'];
    }

    $list[] = $row_array;
}

if (count($list) == 0) {
    $list[] = array("value" => '0', 'label' => 'No Matches');
}

$data = [
    'query' => $term,
    'suggestions' => $list
];

Response::sendJson($data);
