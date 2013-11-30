<?php

/*
* include files
*
*/

include("ini.php");
include("session.php");
include("config.php");
include("functions.php");
/* @var array $CONFIG */

/*
* Send headers to prevent IE cache
*
*/

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . "GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-Type: text/xml; charset=utf-8");

$seed = mt_rand(100000, 999999);
$startTime = microtime(true);
$dbh = db_connect();

if (!isset($_SESSION['user_id'])) {
    $response = <<<EOQ
<?xml version="1.0" ?>
<root>
    <redirect>1</redirect>
</root>
EOQ;
    echo $response;
    exit;
}

/**
 * @param $seed
 * @param $message
 * @param $startTime
 * @param $dbh
 * @param null $data
 */
function CreateSiteLog($seed, $message, $startTime, PDO $dbh, $data = null)
{
    $timespan = microtime(true) - $startTime;
    $query = <<<EOQ
INSERT INTO
    site_logs
    (
        `note`,
        `time`,
        `created_on`,
        `extra_data`,
        `remote_ip`
    )
VALUES
    (
        '$seed - $message',
        $timespan,
        now(),
        '$data',
        '$_SERVER[REMOTE_ADDR]'
    )
EOQ;
    $action = $dbh->prepare($query);
    $action->execute();
}

/*
* check users permissions
*
*/

list($admin, $mod, $speaker, $userTypeId) = adminPermissions();
//CreateSiteLog($seed, 'Admin Permissions', $startTime, $dbh);
//;.if($_GET['roomID'] == 1) { die(); }
/*
* update user
*
*/
// check room ID
/*$sql = <<<EOQ
SELECT
    count(*) as `count`
FROM
    prochatrooms_users
WHERE
    room = ?
    AND username = ?
    AND online = 1
EOQ;
$queryParams = array($_GET['roomID'], $_SESSION['username']);
$statement = $dbh->prepare($sql);
$statement->execute($queryParams);
$result = $statement->fetchColumn();
if($result == 0) {
    //mail('jeffvandenberg@gmail.com', 'Chat Abuse', $_SESSION['username'] . ' attempted to be logged into multiple rooms');
    //logoutUser($_SESSION['username'], $_GET['roomID']);
    die();
}
*/
updateUser();

/*
* virtual credits
*
*/

virtualCredits();

/*
* eCredits
*
*/

if ($_SESSION['eCreditsInit'] == '1') {
    if ($_SESSION['eCreditsAwardTo'] != $_SESSION['myProfileID']) {
        eCredits($_SESSION['eCreditsAwardTo']);
    }
}

/*
* start XML file
*
*/

//CreateSiteLog($seed, 'Get Data', date('Y-m-d H:i:s'), $dbh, print_r($_SESSION, true));
$xml = '<?xml version="1.0" ?><root>';

/*
* moderated chat plugin
* hides messages from users
*/

if ($CONFIG['moderatedChatPlugin']) {
    if (!getAdmin($_SESSION['user_id']) && !getModerator($_SESSION['user_id']) && !getSpeaker($_SESSION['user_id'])) {
        $_GET['history'] = 1;
        $showApproved = '';
    }
}

/*
* get users from database
*
*/

// check users within last 5 mins
$onlineTime = getTime() - 300;

// set offline time
$offlineTime = getTime() - $CONFIG['activeTimeout'];

// get users
try {
    if ($_REQUEST['s']) { // if single room
        $params = array(
            'active' => $onlineTime,
            'room' => makeSafe($_GET['roomID'])
        );
        $query = "SELECT id, username, userid, prevroom, room, avatar, webcam, active, online, status, watching, eCredits, guest, lastActive, userIP, admin, moderator, speaker, user_type_id
				  FROM prochatrooms_users
				  WHERE username != ''
				  AND active > :active
				  AND room = :room
				  GROUP BY room, username ASC
				";
    }
    else {
        $params = array(
            'active' => $onlineTime
        );
        $query = <<<EOQ
SELECT
    id,
    username,
    display_name,
    userid,
    prevroom,
    room,
    avatar,
    webcam,
    active,
    online,
    status,
    watching,
    eCredits,
    guest,
    lastActive,
    userIP,
    admin,
    moderator,
    speaker,
    user_type_id
FROM
    prochatrooms_users
WHERE
    username != ''
    AND active > :active
ORDER BY
    room,
    username ASC
EOQ;

    }
    $action = $dbh->prepare($query);
    $action->execute($params);

    foreach ($action as $i) {
        $showAllUsers = 1;

        if (invisibleAdmins($i['id'])) {
            $showAllUsers = 0;
        }

        if ($showAllUsers == 1) {
            $i['userid'] = empty($i['userid']) ? "0" : $i['userid'];
            $i['room'] = empty($i['room']) ? "0" : $i['room'];

            $xml .= '<userlist>';
            $xml .= $i['id'] . "||"; // 0
            $xml .= stripslashes($i['userid']) . "||"; // 1
            $xml .= stripslashes($i['username']) . "||"; // 2
            $xml .= stripslashes($i['display_name']) . "||"; // 3
            $xml .= stripslashes($i['avatar']) . "||"; // 4
            $xml .= $i['webcam'] . "||"; // 5
            $xml .= $i['room'] . "||"; // 6
            $xml .= $i['prevroom'] . "||"; // 7
            $xml .= $i['admin'] . "||"; // 8
            $xml .= $i['moderator'] . "||"; // 9
            $xml .= $i['speaker'] . "||"; // 10

            // set user to online
            $onlineStatus = '1';

            // if user hasnt been active within $offlineTime
            if ($i['active'] < $offlineTime) {
                // set user to offline
                $onlineStatus = '0';

                if ($i['online'] == '1') {
                    // update user status
                    logoutUser($i['id'], $i['room']);
                }
            }

            $xml .= $onlineStatus . "||"; // 11
            $xml .= $i['status'] . "||"; // 12

            if (!$i['watching']) {
                $i['watching'] = '0';
            }

            $xml .= $i['watching'] . "||"; //13
            $xml .= $CONFIG['eCreditsOn'] . "||"; // 14
            $xml .= $i['eCredits'] . "||"; // 15
            $xml .= $_SESSION['groupCams'] . "||"; // 16
            $xml .= $_SESSION['groupWatch'] . "||"; // 17
            $xml .= $_SESSION['groupChat'] . "||"; // 18
            $xml .= $_SESSION['groupPChat'] . "||"; // 19
            $xml .= $_SESSION['groupRooms'] . "||"; // 20
            $xml .= $_SESSION['groupVideo'] . "||"; // 21
            $xml .= $i['active'] . "||"; // 22
            $xml .= $i['lastActive'] . "||"; // 23

            // if admin or mod, show users IP
            $ip = "0";

            if ($admin || $mod) {
                $ip = $i['userIP'];
            }

            $xml .= $ip . "||"; // 24
            $xml .= $i['user_type_id'] . "||"; // 25

            $xml .= '</userlist>';
        }
    }
} catch (PDOException $e) {
    $error = "Action: Get Users\n";
    $error .= "File: " . basename(__FILE__) . "\n";
    $error .= 'PDOException: ' . $e->getCode() . '-' . $e->getMessage() . "\n\n";
    debugError($error);
}

// update room user count
$query = <<<EOQ
UPDATE prochatrooms_rooms AS R set roomusers = (
    SELECT
        count(*)
    FROM
        prochatrooms_users AS U
    WHERE
        U.room = R.id
        AND U.online = 1
)
EOQ;

$dbh->query($query)->execute();

/*
* get messages from database
*
*/

$userLogout = '';

try {

    if ($_GET['history'] == 0) {
        $params = array(
            'room' => makeSafe($_GET['roomID']),
            'last' => makeSafe($_GET['last']),
            'userid' => makeSafe($_SESSION['user_id'])
        );
        $query = <<<EOQ
SELECT
    id,
    uid,
    mid,
    username,
    tousername,
    to_user_id,
    message,
    sfx,
    room,
    messtime
FROM
    prochatrooms_message
WHERE
    (id > :last)
    AND
    (
        (room = :room AND uid != :userid AND to_user_id = 0)
        OR (to_user_id = :userid)
	    OR (share = '1' AND to_user_id = 0)
    )
EOQ;
    }
    else {
        $totalMessages = $CONFIG['dispLastMess'] + 1;

        $params = array(
            'room' => makeSafe($_GET['roomID']),
            'last' => makeSafe($_GET['last']),
            'userid' => makeSafe($_SESSION['user_id'])
        );

        $query = <<<EOQ
SELECT
    id,
    uid,
    mid,
    username,
    to_user_id,
    message,
    sfx,
    room,
    messtime
FROM
    prochatrooms_message
WHERE
    (id > :last)
    AND
    (
        (room = :room AND to_user_id = 0)
        OR (to_user_id = :userid)
        OR (uid = :userid AND to_user_id > 0)
	    OR (share = '1' and to_user_id = 0)
    )
LIMIT
    $totalMessages
EOQ;
    }

    $action = $dbh->prepare($query);
    $action->execute($params);

    foreach ($action as $i) {
        if (!$i['username']) {
            //die("error: username value null");
        }

        $xml .= '<usermessage>';

        $xml .= $i['id'] . "}{";
        $xml .= $i['uid'] . "}{";
        $xml .= $i['mid'] . "}{";
        $xml .= stripslashes($i['username']) . "}{";

        // if tousername is null
        if (!$i['to_user_id']) {
            $i['to_user_id'] = '_';
        }

        $xml .= stripslashes($i['to_user_id']) . "}{";
        $xml .= stripslashes(urldecode($i['message'])) . "}{";
        $xml .= $i['room'] . "}{";
        $xml .= $i['sfx'] . "}{";
        $xml .= $i['messtime'] . "";

        $xml .= '</usermessage>';

        // check if user has been silenced
        // if so, set silence start time
        if ($i['message'] == 'SILENCE' && $i['to_user_id'] == $_SESSION['user_id']) {
            if (!$_SESSION['silenceStart'] || $_SESSION['silenceStart'] < date("U") - ($CONFIG['silent'] * 60)) {
                $_SESSION['silenceStart'] = date("U");
            }
        }
    }
} catch (PDOException $e) {
    $error = "Action: Get Messages\n";
    $error .= "File: " . basename(__FILE__) . "\n";
    $error .= 'PDOException: ' . $e->getCode() . '-' . $e->getMessage() . "\n\n";
    debugError($error);
}

/*
* get rooms from database
* 
*/

try {
    if ($_REQUEST['s']) { // if single room
        $params = array(
            'roomID' => makeSafe($_GET['roomID'])
        );
        $query = "SELECT id, roomid, roomname, roomowner, roomusers, roomcreated
				  FROM prochatrooms_rooms 
				  WHERE id = :roomID 
				  ORDER BY ABS(id) ASC
				  ";
    }
    else { // if multi room
        $params = array(
            'userRoom' => 'User Room'
        );
        $query = <<<EOQ
SELECT
    R.id,
    R.roomid,
    R.roomname,
    R.roomowner,
    R.roomusers,
    R.roomcreated,
    R.room_type_id,
    RT.room_icon
FROM
    prochatrooms_rooms AS R
    INNER JOIN prochatrooms_room_types AS RT ON R.room_type_id = RT.id
WHERE
    is_active = 1
ORDER BY
    ABS(R.id) ASC
EOQ;
    }

    $action = $dbh->prepare($query);
    $action->execute($params);

    foreach ($action as $i) {
        $xml .= '<userrooms>';

        $xml .= $i['id'] . "||";
        $xml .= $i['id'] . "||";
        $xml .= stripslashes($i['roomname']) . "||";
        $xml .= $i['roomowner'] . "||";
        $xml .= $i['roomusers'] . "||";
        $xml .= $i['room_type_id'] . "||";
        $xml .= $i['room_icon'] . "||";

        $deleteRoom = '0';

        if (($i['roomusers'] == '0') && (getTime() - 300 >= $i['roomcreated']) && ($i['roomowner'] != '1')) {
            // was  - if($_REQUEST['s'] && !$CONFIG['one2onePlugin'])
            // did not delete users created rooms, so we updated it too,

            if (!$CONFIG['one2onePlugin']) {
                deleteUserRoom($i['id']);
                $deleteRoom = '1';
            }
        }

        $xml .= $deleteRoom . "||";
        $xml .= moderatedChat() . "||";

        $xml .= '</userrooms>';

    }
} catch (PDOException $e) {
    $error = "Action: Get Rooms\n";
    $error .= "File: " . basename(__FILE__) . "\n";
    $error .= 'PDOException: ' . $e->getCode() . '-' . $e->getMessage() . "\n\n";
    debugError($error);
}

//CreateSiteLog($seed, 'Get Rooms', $startTime, $dbh);
//CreateSiteLog($seed, 'Finish Page', $startTime, $dbh);
$dbh = null;

/*
* end XML file
*
*/

$xml .= '</root>';

/*
* show XML output
*
*/

echo $xml;

/*
* write/close session
* http://php.net/manual/en/function.session-write-close.php
*/

session_write_close();