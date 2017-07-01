<?php
/********************************************************************************************
 *
 *  Software: Pro Chat Rooms
 *  Developer: Pro Chat Rooms
 *  Url: http://prochatrooms.com
 *  Support: http://community.prochatrooms.com
 *
 *  Pro Chat Rooms is NOT free software - For more details visit, http://www.prochatrooms.com
 *  This software and all of its source code/files are protected by Copyright Laws.
 *  The software license permits you to install this software on one domain only. Additional
 *  installations require additional licences (one software licence per installation).
 *  Pro Chat Rooms is unable to provide support if this software is modified by the end user.
 *
 ********************************************************************************************/

/*
* include files
*
*/

include("includes/ini.php");
include("includes/session.php");
include("includes/config.php");
include("includes/functions.php");
/* @var array $CONFIG */

if ($_SESSION['user_id'] && !$_SESSION['username']) {
    unset($_SESSION['user_id']);
}

if (!isset($_SESSION['user_id'])) {
    unset($_SESSION['username']);
    unset($_SESSION['display_name']);
    unset($_SESSION['userid']);
    unset($_SESSION['user_id']);
    unset($_SESSION['user_type_id']);
    unset($_SESSION['room']);
    unset($_SESSION['guest']);
}

/*
* include language file
*
*/

include("lang/" . getLang($_POST['langID']));


/*
* reset login errors
*
*/

$loginError = '';

    /*
* cms integration
*
*/

$userId = isset($_GET['userId'])
    ? $_GET['userId']
    : (isset($_SESSION['user_id'])
        ? $_SESSION['user_id']
        : null
    );

if ($CONFIG['CMS'] && !isset($_GET['logout'])) {
    // session login
    if (!$userId) {
        // include files
        include("cms.php");
        $userId = $_SESSION['user_id'];
    }

    // assign default room login
    if (!isset($_REQUEST['roomID'])) {
        $_REQUEST['roomID'] = '1';
    }
}

// setup and validate user_id
if(!$userId) {
    header('Location: /');
}

// load all user information!
$user = loadUser($userId);

switch($user['user_type_id']) {
    case 1:
        // ooc login no validation
        break;
    case 3:
        // validate character is associated with the logged in user
        if(!validateCharacter($user['userid'], $_SESSION['Auth']['User']['user_id'])) {
            header('Location: /');
        }
        break;
    case 2:
    case 4:
    case 5:
    case 6:
    case 7:
        // validate user ID
        if(!validateStaff($user['userid'], $_SESSION['Auth']['User']['user_id'])) {
            header('Location: /');
        }
        break;
}

/*
* get transcripts
*
*/

if (isset($_GET['transcripts']) && isset($_GET['roomID'])) {
    include("templates/" . $CONFIG['template'] . "/transcripts.php");
    die;
}

/*
* logout user
*
*/

if (isset($_REQUEST['logout']) && isset($user['id'])) {
    logoutUser($user['id'], $user['room']);

    if ($_REQUEST['logout'] == 'kick') {
        banKickUser('KICK', $user['username']);
    }

    unset($_SESSION['username']);
    unset($_SESSION['display_name']);
    unset($_SESSION['userid']);
    unset($_SESSION['user_id']);
    unset($_SESSION['user_type_id']);
    unset($_SESSION['room']);
    unset($_SESSION['guest']);
    header('Location: /');
}

/*
* check room is set
*
*/

if (!$_REQUEST['roomID'][0]) {
    header('location:/');
    die;
}


if (empty($_REQUEST['userName']) && isset($_REQUEST['login'])) {
    $loginError = C_LANG1;

    header('location:/');
    die;
}


$totalRooms = totalRooms();


/*
* get previous room id
* 
*/

$prevRoom = prevRoom($userId, $user['room']);

/*
* get create room details
* 
*/

$roomPass = '';

if (isset($_REQUEST['roomPass'])) {
    $roomPass = $_REQUEST['roomPass'];
}

list($roomID, $roomOwnerID) = chatRoomID($_REQUEST['roomID'], $roomPass, $user);

list($roomBg, $roomDesc) = chatRoomDesc($roomID);

/*
* get user details
*
*/

$guestUser = '0';

list($id, $avatar, $loginError, $blockedList, $guestUser, $userTypeId, $isInvisible) = getUser(
    $prevRoom,
    $roomID,
    $userId
);

/*
* assign user group
*
*/

getUserGroup($user['userGroup']);

/*
* assign room owner
*
*/

$roomOwner = '0';

if ($id == $roomOwnerID) {
    $roomOwner = '1';
}

/*
* silence duration
*
*/

$silent = $CONFIG['silent'];

/*
* get room last message id
*
*/

$lastMessageID = getLastMessageID($roomID);

/*
* alls ok, include main template
*
*/

include("templates/" . $CONFIG['template'] . "/main.php");
