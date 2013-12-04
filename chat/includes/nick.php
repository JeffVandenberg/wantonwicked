<?php
/**
 * Created by JetBrains PhpStorm.
 * User: JeffVandenberg
 * Date: 11/14/13
 * Time: 10:05 PM
 * To change this template use File | Settings | File Templates.
 */

include("ini.php");
include("session.php");
include("config.php");
include("functions.php");

/*
* Send headers to prevent IE cache
*
*/

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT" );
header("Cache-Control: no-cache, must-revalidate" );
header("Pragma: no-cache" );
header("Content-Type: text/xml; charset=utf-8");

$seed = mt_rand(100000,999999);
$startTime = microtime(true);
$dbh = db_connect();

if(!isset($_SESSION['user_id'])) {
    die('No ID');
}

$response = array(
    'status' => false,
    'message' => 'Unknown action'
);

list($admin,$mod,$speaker,$userTypeId) = adminPermissions();
if(!$admin && !$mod) {
    header('content-type: application/json');
    $response['message'] = 'Not admin or moderator. User ID: ' . $_SESSION['user_id'];
    echo json_encode($response);
    die();
}

$sql = '';
$parameters = null;
switch($_POST['action']) {
    case 'change':
        $sql = <<<EOQ
UPDATE
    prochatrooms_users
SET
    display_name = ?
WHERE
    id = ?
EOQ;
        $parameters = array(
            $_POST['new_name'],
            $_SESSION['user_id']
        );
        break;
    default:
        break;
}

if($sql !== '') {
    $statement = $dbh->prepare($sql);
    $result = $statement->execute($parameters);
    if($result) {
        $_SESSION['display_name'] = $_POST['new_name'];
        $response = array(
            'status' => true,
            'message' => 'Updated Nick'
        );
    }
    else {
        $response = array(
            'status' => false,
            'message' => implode(',', $dbh->errorInfo())
        );
    }
}

header('content-type: application/json');
echo json_encode($response);