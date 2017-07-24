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

use classes\core\helpers\Request;
use classes\core\helpers\Response;
use classes\core\helpers\SessionHelper;
use classes\log\CharacterLog;
use classes\log\data\ActionType;

// Prochat room includes
include("includes/ini.php");
include("includes/session.php");
include("includes/config.php");
include("includes/functions.php");
/* @var array $CONFIG */

// PHPBB Integration includes
define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : '../forum/';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.' . $phpEx);
/* @var \phpbb\request\request $request */
$request = $phpbb_container->get('request');
$request->enable_super_globals();

//
// Start session management
//

$user->session_begin();
$auth->acl($user->data);
$userdata = $user->data;
$user->setup();

// Composer includess
require_once '../../vendor/autoload.php';

// these probably can go away soon
if (isset($_SESSION['user_id']) && !$_SESSION['username']) {
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

include_once("lang/" . getLang($_POST['langID'] ?? null));


/*
* Check for user login
*
*/

$userId = $_GET['userId'] ?? null;

if ($CONFIG['CMS'] && !isset($_GET['logout'])) {
    // assign default room login
    if (!isset($_REQUEST['roomID'])) {
        $_REQUEST['roomID'] = '1';
    }

    if (!$userId) {
        // include files
        include("cms.php");

        if($userId) {
            // redirect to starting page
            Response::redirect('/chat/?userId='.$userId.'&roomID='.$_REQUEST['roomID']);
        }
    }
}

// validate user_id
if(!$userId) {
    Response::redirect('/', 'Unable to login user.');
}

// load all user information!
$user = loadUser($userId);

if(!$user) {
    Response::redirect('/', 'Unable to find user.');
}

switch($user['user_type_id']) {
    case 1:
        // ooc login no validation
        break;
    case 3:
        // validate character is associated with the logged in user
        if(!validateCharacter($user['userid'], $userdata['user_id'])) {
            CharacterLog::LogAction($user['userid'], ActionType::InvalidAccess,
                'User ID: ' . $userdata['user_id'] . ' attempted access to chatrooms with character.');
            Response::redirect('/', 'Illegal Character Access.');
        }
        break;
    case 2:
    case 4:
    case 5:
    case 6:
    case 7:
        // validate user ID
        if(!validateStaff($user['userid'], $userdata['user_id'])) {
            Response::redirect('/', 'Invalid user permissions');
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

    $reason = Request::getValue('reason', '');
    switch($reason) {
        case 'kick':
            $message = 'You have kicked out of the chat.';
            break;
        case 'ban':
            $message = 'You have been banned from the chat.';
            break;
        default:
            $message = 'You have successfully logged out of the chat.';
    }
    Response::redirect('/', $message);
}

/*
* check room is set
*
*/

if (!$_REQUEST['roomID'][0]) {
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

$loginError = validateUser($user);

if($loginError) {
    // set session message and redirect home
    SessionHelper::SetFlashMessage($loginError);
    Response::redirect('/');
} else {
    updateUserRoom($user['id'], $roomID);
}

/*
* assign user group
*
*/

$groupInfo = getUserGroup($user['userGroup']);

/*
* assign room owner
*
*/

$roomOwner = '0';

if ($user['id'] == $roomOwnerID) {
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
