<?php

/*
* include files
*
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
function CreateSiteLog($seed, $message, $startTime, $dbh, $data = null)
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

list($admin,$mod,$speaker) = adminPermissions();
//CreateSiteLog($seed, 'Admin Permissions', $startTime, $dbh);
//;.if($_GET['roomID'] == 1) { die(); }
/*
* update user
*
*/
// check room ID
$sql = <<<EOQ
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

if($_SESSION['eCreditsInit'] == '1')
{
	if($_SESSION['eCreditsAwardTo'] != $_SESSION['myProfileID'])
	{
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

if($CONFIG['moderatedChatPlugin'])
{
	if(!getAdmin($_SESSION['username']) && !getModerator($_SESSION['username']) && !getSpeaker($_SESSION['username']))
	{
		$_GET['history'] = 1;
		$showApproved = '';
	}
}

/*
* get messages from database
*
*/

$userLogout = '';

try {

	if($_GET['history'] == 0)
	{
		$params = array(
		'room' => makeSafe($_GET['roomID']),
		'last' => makeSafe($_GET['last']),
		'username' => makeSafe($_SESSION['username'])		
		);
		$query = "SELECT id, uid, mid, username, tousername, message, sfx, room, messtime 
				  FROM prochatrooms_message
				  WHERE room = :room AND id > :last AND tousername = '' AND username != :username 
				  OR room = :room AND id > :last AND tousername = :username
				  OR id > :last AND tousername = :username
				  OR room = :room AND id > :last AND share = '1' AND tousername = :username
				  OR room = :room AND id > :last AND share = '1' AND username = :username
				  ";
	}
	else
	{
		$totalMessages = $CONFIG['dispLastMess'] + 1;
	
		$params = array(
		    'room' => makeSafe($_GET['roomID']),
		    'last' => makeSafe($_GET['last']),
		    'username' => makeSafe($_SESSION['username'])
		);
		$query = "SELECT id, uid, mid, username, tousername, message, sfx, room, messtime 
				  FROM prochatrooms_message
				  WHERE (room = :room AND id > :last AND tousername = '')
				  OR (room = :room AND id > :last AND tousername = :username)
				  OR (id > :last AND tousername = :username)
				  OR (id > :last AND share = '1' AND tousername = :username)
				  OR (id > :last AND share = '1' AND username = :username)
				  LIMIT ".$totalMessages."
				  ";
	}

	$action = $dbh->prepare($query);
	$action->execute($params);
				
	foreach ($action as $i) 
	{
		if(!$i['username'])
		{
			die("error: username value null");
		}

		$xml .= '<usermessage>';
		
		$xml .= $i['id']."}{";
		$xml .= $i['uid']."}{";
		$xml .= $i['mid']."}{";
		$xml .= stripslashes($i['username'])."}{";	
		
		// if tousername is null
		if(!$i['tousername'])
		{
			$i['tousername']='_';
		}	
		
		$xml .= stripslashes($i['tousername'])."}{";
		$xml .= stripslashes(urldecode($i['message']))."}{";	
		$xml .= $i['room']."}{";
		$xml .= $i['sfx']."}{";
		$xml .= $i['messtime']."";
		
		$xml .= '</usermessage>';	

		// check if user has been silenced
		// if so, set silence start time
		if($i['message'] == 'SILENCE' && $i['tousername'] == $_SESSION['username'])
		{
			if(!$_SESSION['silenceStart'] || $_SESSION['silenceStart'] < date("U")-($CONFIG['silent']*60))
			{
				$_SESSION['silenceStart'] = date("U");
			}
		}
	}
}
catch(PDOException $e) 
{
	$error  = "Action: Get Messages\n";
	$error .= "File: ".basename(__FILE__)."\n";	
	$error .= 'PDOException: '.$e->getCode(). '-'. $e->getMessage()."\n\n";

	debugError($error);
}
//CreateSiteLog($seed, 'Get Messages', $startTime, $dbh);
/*
* get users from database
* 
*/

// check users within last 5 mins
$onlineTime = getTime()-300;

// set offline time
$offlineTime = getTime()-$CONFIG['activeTimeout'];

// get users
try {
	if($_REQUEST['s'])
	{ // if single room
		$params = array(
		'active' => $onlineTime,
		'room' => makeSafe($_GET['roomID'])		
		);
		$query = "SELECT id, display_name AS username, userid, prevroom, room, avatar, webcam, active, online, status, watching, eCredits, guest, lastActive, userIP, admin, moderator, speaker
				  FROM prochatrooms_users 
				  WHERE username != '' 
				  AND active > :active
				  AND room = :room
				  GROUP BY room, username ASC
				";		
	}
	else
	{
		$params = array(
		'active' => $onlineTime
		);
		$query = "SELECT id, display_name AS username, userid, prevroom, room, avatar, webcam, active, online, status, watching, eCredits, guest, lastActive, userIP, admin, moderator, speaker
				  FROM prochatrooms_users 
				  WHERE  username != ''
				  AND active > :active
				  ORDER BY room, username ASC
				";			
	}
	$action = $dbh->prepare($query);
	$action->execute($params);
    //CreateSiteLog($seed, 'Execute User Query', $startTime, $dbh);

	foreach ($action as $i)
	{
		$showAllUsers = 1;

		if(invisibleAdmins($i['username']))
		{
			$showAllUsers = 0;
		}

		if($showAllUsers == 1)
		{
			$i['userid'] = empty($i['userid']) ? "0" : $i['userid'];
			$i['room'] = empty($i['room']) ? "0" : $i['room'];

			$xml .= '<userlist>';
			$xml .= $i['id']."||";
			$xml .= stripslashes($i['userid'])."||";
			$xml .= stripslashes($i['username'])."||";
			$xml .= stripslashes($i['avatar'])."||";
			$xml .= $i['webcam']."||";
			$xml .= $i['room']."||";
			$xml .= $i['prevroom']."||";
			$xml .= $i['admin']."||";
			$xml .= $i['moderator']."||";
			$xml .= $i['speaker']."||";
			
			// set user to online
			$onlineStatus = '1';

			// if user hasnt been active within $offlineTime
			if($i['active'] < $offlineTime)
			{
				// set user to offline
				$onlineStatus = '0';

				if($i['online'] == '1')
				{
					// update user status
					logoutUser($i['username'],$i['room']);
				}
			}

			$xml .= $onlineStatus."||";
			$xml .= $i['status']."||";
			
			if(!$i['watching'])
			{
				$i['watching'] ='0';
			}

            $xml .= $i['watching']."||";
			$xml .= $CONFIG['eCreditsOn']."||";
			$xml .= $i['eCredits']."||";
			$xml .= $_SESSION['groupCams']."||";
			$xml .= $_SESSION['groupWatch']."||";
			$xml .= $_SESSION['groupChat']."||";
			$xml .= $_SESSION['groupPChat']."||";
			$xml .= $_SESSION['groupRooms']."||";
			$xml .= $_SESSION['groupVideo']."||";
			$xml .= $i['active']."||";
			$xml .= $i['lastActive']."||";
			
			// if admin or mod, show users IP
			$ip = "0";
			
			if($admin || $mod)
			{
				$ip = $i['userIP'];
			}	
			
			$xml .= $ip."||";

            $xml .= '</userlist>';
		}
	}
}
catch(PDOException $e) 
{
	$error  = "Action: Get Users\n";
	$error .= "File: ".basename(__FILE__)."\n";	
	$error .= 'PDOException: '.$e->getCode(). '-'. $e->getMessage()."\n\n";

	debugError($error);
}

//CreateSiteLog($seed, 'Get Users', $startTime, $dbh);
/*
* get rooms from database
* 
*/

try {
	if($_REQUEST['s'])
	{ // if single room
		$params = array(
		'roomID' => makeSafe($_GET['roomID'])
		);
		$query = "SELECT id, roomid, roomname, roomowner, roomusers, roomcreated     
				  FROM prochatrooms_rooms 
				  WHERE id = :roomID 
				  ORDER BY ABS(id) ASC
				  ";	
	}
	else
	{ // if multi room
		$params = array(
		'userRoom' => 'User Room' 
		);
		$query = "SELECT id, roomid, roomname, roomowner, roomusers, roomcreated     
				  FROM prochatrooms_rooms 
				  WHERE roomname != :userRoom
				  ORDER BY ABS(id) ASC
				  ";
	}

	$action = $dbh->prepare($query);
	$action->execute($params);

	foreach ($action as $i) 
	{
		$xml .= '<userrooms>';
		
		$xml .= $i['id']."||";
		$xml .= $i['id']."||";
		$xml .= stripslashes($i['roomname'])."||";
		$xml .= $i['roomowner']."||";
		$xml .= $i['roomusers']."||";
		
		$deleteRoom = '0';

		if(($i['roomusers'] == '0') && (getTime()-300 >= $i['roomcreated']) && ($i['roomowner'] != '1'))
		{
			// was  - if($_REQUEST['s'] && !$CONFIG['one2onePlugin'])
			// did not delete users created rooms, so we updated it too,

			if(!$CONFIG['one2onePlugin'])
			{
				deleteUserRoom($i['id']);
				$deleteRoom = '1';
			}
		}

		$xml .= $deleteRoom."||";
		$xml .= moderatedChat()."||";
		
		$xml .= '</userrooms>';

	}
}
catch(PDOException $e) 
{
	$error  = "Action: Get Rooms\n";
	$error .= "File: ".basename(__FILE__)."\n";	
	$error .= 'PDOException: '.$e->getCode(). '-'. $e->getMessage()."\n\n";

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