<?php
#############################################
# Author: Pro Chatrooms
# Software: Pro Chatrooms
# Url: http://www.prochatrooms.com
# Support: support@prochatrooms.com
############################################# 


// INTEGRATION NOTES FOR CUSTOM DEVELOPERS

// You can insert your existing CMS user Global values into the 
// login procedure. Simply replace the values $_FOO['username'] 
// and $_FOO['userid'] with your SESSION, COOKIE or MySQL results.

// Example Code:

// define('C_CUSTOM_LOGIN','1'); // 0 OFF, 1 ON
// define('C_CUSTOM_USERNAME',$_SESSION['username']); // username
// define('C_CUSTOM_USERID',$_SESSION['userid']); // userid

// You will be able to link directly to the chat room by adding 
// an <a href> link to your web pages like shown below and only 
// registered users will be able to auto-login to your chat room.

// <a href="http://yoursite.com/prochatrooms/">Chat Room</a>


## CUSTOM INTEGRATION SETTINGS ##############

// Enable custom login details

// perform required includes
use classes\character\data\Character;
use classes\character\data\CharacterStatus;
use classes\character\nwod2\SheetService;
use classes\core\helpers\Response;
use classes\core\helpers\UserdataHelper;
use classes\log\CharacterLog;
use classes\log\data\ActionType;
use classes\support\repository\SupporterRepository;

define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : '../forum/';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.' . $phpEx);
$request = $phpbb_container->get('request');
/* @var \phpbb\request\request $request */
$request->enable_super_globals();

//
// Start session management
//

$user->session_begin();
$auth->acl($user->data);
$userdata = $user->data;
$user->setup('');

require_once __DIR__ . '/../../webroot/cgi-bin/start_of_page.php';

define('C_CUSTOM_LOGIN','1'); // 0 OFF, 1 ON

// Enter your CMS Global values below
$loggedIn = false;
$userTypeId = 0;
$isInvisible = 0;
if(isset($_GET['character_id'])) {
    $characterId = (int) $_GET['character_id'];
    $service = new SheetService();
    $character = $service->loadSheet($characterId);
    /* @var Character $character */

    if(!$character || ($character->UserId != $userdata['user_id'])) {
        Response::endRequest('Not Allowed');
    }

    $encoded = htmlspecialchars($character->CharacterName);
    $cleanName = str_replace("'", '&#39;', $encoded);
    define('C_CUSTOM_USERNAME', $cleanName); // username
    define('C_CUSTOM_USERID', $characterId); // userid
    define('C_CUSTOM_ACTION', 'CHARACTER LOGIN');

    $icon = 'unsanctioned.png';
    if($character->CharacterStatusId == CharacterStatus::Active) {
        switch(strtolower($character->CharacterType)) {
            case 'mortal':
                $icon = 'wodm.png';
                break;
            case 'vampire':
                $icon = 'vtr.png';
                break;
            case 'ghoul':
                $icon = 'vtrg.png';
                break;
            case 'mage':
                $icon = 'mta.png';
                break;
            case 'werewolf':
                $icon = 'wtf.png';
                break;
            case 'changeling':
                $icon = 'ctl.png';
                break;
            case 'sleepwalker':
                $icon = 'mtasw.png';
                break;
            case 'wolfblooded':
                $icon = 'wtfwb.png';
                break;
            default:
                $icon = 'player.png';
                break;
        }

        if((((int) $character->Icon) === 0) && ($character->HideIcon == 'N')){
            $icon = $character->Icon;
        }
    }
    if($character->CharacterStatusId == CharacterStatus::Unsanctioned) {
        $icon = 'desanctioned.png';
    }
    if($character->City == 'Side Game') {
        $icon = 'sidegames.png';
    }

    $_SESSION['username'] = str_replace('\'', '\\\'', C_CUSTOM_USERNAME);
    $_SESSION['userid'] = C_CUSTOM_USERID;
    $_SESSION['userGroup'] = 2;
    $_SESSION['is_invisible'] = 0;
    $userTypeId = 3;
    addUser($icon, $userTypeId); // login type 3 = character

    $query = <<<EOQ
UPDATE
    prochatrooms_users
SET
    display_name = :name,
    usergroup = '2',
    admin = '0',
    moderator = '0',
    avatar = :icon
WHERE
    username = :username
    AND userid = :userid
EOQ;

    $dbh = db_connect();
    $action = $dbh->prepare($query);
    $action->bindValue('userid', C_CUSTOM_USERID, PDO::PARAM_INT);
    $action->bindValue('username', makeSafe(C_CUSTOM_USERNAME));
    $action->bindValue('icon', $icon);
    $action->bindValue('name', C_CUSTOM_USERNAME);
    if(!$action->execute()) {
        return $action->errorInfo();
    }

    // add login record to character log
    CharacterLog::LogAction($characterId, ActionType::Login, 'Chat Login', $userdata['user_id']);

    $loggedIn = true;
}
else if(isset($_GET['st_login']) || ($_GET['action'] == 'st_login')) {
    if(UserdataHelper::IsSt($userdata)) {
        define('C_CUSTOM_USERNAME', $userdata['username']);
        define('C_CUSTOM_USERID', $userdata['user_id']);

        $_SESSION['username'] = str_replace('\'', '\\\'', C_CUSTOM_USERNAME);
        $_SESSION['userid'] = C_CUSTOM_USERID;
        $_SESSION['userGroup'] = 3;
        $_SESSION['is_invisible'] = isset($_GET['invisible']);

        $icon = 'st.png';
        $userTypeId = 4; // regular ST
        $admin = 0;
        $mod = 1;
        if(UserdataHelper::IsAsst($userdata)) {
            $userTypeId = 5;
            $mod = 1;
        }
        if(UserdataHelper::IsAdmin($userdata)) {
            $icon = 'admin.png';
            $userTypeId = 6;
            $admin = 1;
            $mod = 1;
        }
        else if(UserdataHelper::IsWikiManager($userdata)) {
            $icon = 'wiki.png';
            $userTypeId = 7;
            $mod = 0;
        }

        addUser($icon, $userTypeId);

        $isInvisible = 0;//isset($_GET['invisible']) + 0;

        $query = <<<EOQ
UPDATE
    prochatrooms_users
SET
    display_name = :name,
    usergroup = '3',
    guest = '0',
    admin = :admin,
    moderator = :mod,
    avatar = :icon,
    is_invisible = :isInvisible
WHERE
    username = :username
    AND userid = :userid
EOQ;

        $dbh = db_connect();
        $action = $dbh->prepare($query);
        $action->bindValue('userid', $userdata['user_id'], PDO::PARAM_INT);
        $action->bindValue('username', $userdata['username']);
        $action->bindValue('name', C_CUSTOM_USERNAME);
        $action->bindValue('admin', $admin);
        $action->bindValue('mod', $mod);
        $action->bindValue('icon', $icon);
        $action->bindValue('isInvisible', $isInvisible);
        $action->execute();
        $loggedIn = true;
    }
    else {
        // check if they have a profile and remove moderator permissions
        $query = <<<EOQ
UPDATE
    prochatrooms_users
SET
    usergroup = '2',
    guest = '0',
    admin = '0',
    moderator = '0',
    speaker = '0'
WHERE
    username = :username
    AND userid = :userid
EOQ;
        $dbh = db_connect();
        $action = $dbh->prepare($query);
        $action->bindValue('userid', C_CUSTOM_USERID, PDO::PARAM_INT);
        $action->bindValue('username', C_CUSTOM_USERNAME);
        $action->execute();
        Response::endRequest('You do not have ST Permissions');
    }
}
else if($userdata['username'] !== 'Anonymous') {
    define('C_CUSTOM_USERNAME', $userdata['username']); // username
    define('C_CUSTOM_USERID', $userdata['user_id']); // userid
    define('C_CUSTOM_ACTION', 'OOC LOGIN');

    $_SESSION['username'] = str_replace('\'', '\\\'', C_CUSTOM_USERNAME);
    $_SESSION['userid'] = C_CUSTOM_USERID;
    $_SESSION['userGroup'] = 2;
    $_SESSION['is_invisible'] = 0;
    $icon = 'ooc.png';

    $dbh = db_connect();

    // check if they are a supporter
    $supporterRepository = new SupporterRepository();
    if($supporterRepository->CheckIsCurrentSupporter($userdata['user_id'])) {
        $icon = 'supporter.png';
    }

    $userTypeId = 2;
    addUser($icon, $userTypeId); // registered ooc user

    $query = <<<EOQ
UPDATE
    prochatrooms_users
SET
    display_name = :name,
    usergroup = '2',
    admin = '0',
    moderator = '0',
    guest = '0',
    avatar = :icon
WHERE
    username = :username
    AND userid = :userid
EOQ;

    $action = $dbh->prepare($query);
    $action->bindValue('userid', C_CUSTOM_USERID, PDO::PARAM_INT);
    $action->bindValue('username', C_CUSTOM_USERNAME);
    $action->bindValue('name', C_CUSTOM_USERNAME);
    $action->bindValue('icon', $icon);
    $action->execute();
    $loggedIn = true;
}
else if(isset($_POST['username']) && (trim($_POST['username']) !== '')) {
    define('C_CUSTOM_USERNAME', $_POST['username']);
    define('C_CUSTOM_USERID', -1); // userid
    define('C_CUSTOM_ACTION', 'GUEST LOGIN');

    $_SESSION['username'] = str_replace('\'', '\\\'', C_CUSTOM_USERNAME);
    $_SESSION['userid'] = C_CUSTOM_USERID;
    $_SESSION['userGroup'] = 1;
    $_SESSION['is_invisible'] = 0;
    $userTypeId = 1;
    addUser('ooc.png', $userTypeId); // guest user

    $query = <<<EOQ
UPDATE
    prochatrooms_users
SET
    display_name = ?,
    usergroup = '1',
    admin = '0',
    moderator = '0',
    guest = '1',
    avatar = 'ooc.png'
WHERE
    username = ?
    AND userid = ?
EOQ;

    $dbh = db_connect();
    $action = $dbh->prepare($query);
    $parameters = array(C_CUSTOM_USERNAME, C_CUSTOM_USERNAME, C_CUSTOM_USERID);
    $action->execute($parameters);
    $loggedIn = true;
}
else {
    Response::redirect('/login_ooc.php');
}

if(!$loggedIn)
{
    Response::endRequest('Not Logged In');
}

$sql = <<<EOQ
SELECT
    id
FROM
    prochatrooms_users
WHERE
    `username` = ?
    AND `userid` = ?
    AND `user_type_id` = ?
EOQ;

$statement = $dbh->prepare($sql);
$params = array(makeSafe(C_CUSTOM_USERNAME), C_CUSTOM_USERID, $userTypeId);
$statement->execute($params);
$result = $statement->fetch(PDO::FETCH_ASSOC);

// set chat information
// session login
$_SESSION['username'] = C_CUSTOM_USERNAME;
$_SESSION['userid'] = C_CUSTOM_USERID;
$_SESSION['display_name'] = C_CUSTOM_USERNAME;
$_SESSION['user_id'] = $result['id'];
$_SESSION['user_type_id'] = $userTypeId;

## DO NOT EDIT BELOW THIS LINE ##############


// if remote login via CMS

	if(isset($remotely_hosted) && $remotely_hosted){

		// check username isset
		if(!isset($_COOKIE["uname"])){

			header("Location: error.php");
			die;

		}

		// if userid is null, assign userid
		if(!isset($_COOKIE["uid"])){

			$uid='-1';

		}else{

			$uid=$_COOKIE["uid"];

		}

	}

// if custom login

	if(C_CUSTOM_LOGIN){

		// assign username
		$uname = str_replace('\'', '\\\'', C_CUSTOM_USERNAME);

		if(!C_CUSTOM_USERID){

			// userid empty
			$uid = '-1';

		}else{

			// assign userid
			$uid = C_CUSTOM_USERID;

		}

	}

// if default login

	if(isset($remotely_hosted) && !$remotely_hosted && !C_CUSTOM_LOGIN){

	?>

		<SCRIPT LANGUAGE="JavaScript1.2">
		<!-- 
		function getCookieVal (offset) {
	  		var endstr = document.cookie.indexOf (";", offset);
	  		if (endstr == -1)
	  		endstr = document.cookie.length;
	  		return unescape(document.cookie.substring(offset, endstr));
		}
		function GetCookie (name) {
	  		var arg = name + "=";
	  		var alen = arg.length;
	  		var clen = document.cookie.length;
	  		var i = 0;
	  		while (i < clen) {
	    		var j = i + alen;
	    		if (document.cookie.substring(i, j) == arg)
	    		return getCookieVal (j);
	    		i = document.cookie.indexOf(" ", i) + 1;
	    		if (i == 0) break;
	  		}
	  		return null;
		}
		if(GetCookie("login") == null){ 
			window.location="error.php";
		}
		// -->
		</SCRIPT>

<?php }?>
