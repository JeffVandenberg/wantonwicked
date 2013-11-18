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

if(!isset($_SESSION['username'])) {
    die();
}

list($admin,$mod,$speaker) = adminPermissions();
if(!$admin || !$mod) {
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
    userid = ?
    AND display_name = ?
EOQ;
        $parameters = array(
            $_POST['new_name'],
            $_POST['user_id'],
            $_POST['username']
        );
        break;
    default:
        break;
}

$response = array(
    'status' => false,
    'message' => 'Unknown action'
);

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