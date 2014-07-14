<?php
/**
 * Created by PhpStorm.
 * User: JeffVandenberg
 * Date: 7/13/14
 * Time: 9:38 PM
 */

require_once("ini.php");
require_once("session.php");
require_once("config.php");
require_once("functions.php");

if (!isset($_SESSION['user_id'])) {
    die();
}

list($admin, $mod, $speaker, $userTypeId) = adminPermissions();

if(!$admin || !$mod) {
    die('You are not allowed to ghost');

}

$response = array(
    'status' => false,
    'message' => 'Unknown action'
);

$dbh = db_connect();
$query = <<<EOQ
UPDATE
    prochatrooms_users
SET
    is_invisible = ?
WHERE
    id = ?
EOQ;

switch ($_POST['action']) {
    case 'on':
        $params = array(1, $_SESSION['user_id']);
        break;
    case 'off':
        $params = array(0, $_SESSION['user_id']);
        break;
}

if(isset($params)) {
    $action = $dbh->prepare($query);
    $action->execute($params);

    if($action->rowCount() > 0) {
        $response = array(
            'status' => true
        );
    }
    else {
        $response = array(
            'status' => true
        );
    }
}

header('content-type: application/json');
echo json_encode($response);