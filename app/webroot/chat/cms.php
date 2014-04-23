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
// if(!isset($_SESSION['userid']) || empty($_SESSION['userid']))
// {
//	 die("userid value is null");
// }

// You will be able to link directly to the chat room by adding 
// an <a href> link to your web pages like shown below and only 
// registered users will be able to auto-login to your chat room.

// <a href="http://yoursite.com/prochatrooms/">Chat Room</a>


## CUSTOM INTEGRATION SETTINGS ##############

// Enable custom login details

// perform required includes
define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : '../forum/';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.' . $phpEx);
include($phpbb_root_path . 'includes/functions_display.' . $phpEx);

//
// Start session management
//

$user->session_begin();
$auth->acl($user->data);
$userdata = $user->data;

define('C_CUSTOM_LOGIN','1'); // 0 OFF, 1 ON


// Enter your CMS Global values below
$loggedIn = false;
$userTypeId = 0;
if(isset($_GET['character_id'])) {
    $characterId = (int) $_GET['character_id'];
    $query = <<<EOQ
SELECT
    C.character_name,
    C.character_type,
    C.is_sanctioned,
    icon,
    hide_icon
FROM
    characters AS C
WHERE
    C.id = ?
	AND C.user_id = ?
EOQ;

    /* @var PDO $dbh */
    $dbh = db_connect();
    $action = $dbh->prepare($query);
    $action->execute(array($characterId, $userdata['user_id']));
    $character = $action->fetch(PDO::FETCH_ASSOC);

    if($character === false) {
        die('Not allowed');
    }
    define('C_CUSTOM_USERNAME', $character['character_name']); // username
    define('C_CUSTOM_USERID', $characterId); // userid
    define('C_CUSTOM_ACTION', 'CHARACTER LOGIN');

    $icon = 'unsanctioned.png';
    if($character['is_sanctioned'] == 'Y') {
        switch(strtolower($character['character_type'])) {
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

        if((((int) $character['icon']) === 0) && ($character['hide_icon'] == 'N')){
            $icon = $character['icon'];
        }
    }
    if($character['is_sanctioned'] == 'N') {
        $icon = 'desanctioned.png';
    }
    if($character['location'] == 'Side Game') {
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

    $action = $dbh->prepare($query);
    $action->bindValue('userid', C_CUSTOM_USERID, PDO::PARAM_INT);
    $action->bindValue('username', C_CUSTOM_USERNAME);
    $action->bindValue('icon', $icon);
    $action->bindValue('name', C_CUSTOM_USERNAME);
    $action->execute();

    // add login record to character log
    $query = <<<EOQ
INSERT INTO
    log_characters
    (
        character_id,
        action_type_id,
        created_by_id,
        created,
        note
    )
VALUES
    (
        ?,
        ?,
        ?,
        ?,
        'Chat Login'
    )
EOQ;
    $params = array(
        $characterId,
        2, // login
        $userdata['user_id'],
        date('Y-m-d H:i:s')
    );
    $action = $dbh->prepare($query);
    $action->execute($params);

    $loggedIn = true;
}
else if(isset($_GET['st_login']) || ($_GET['action'] == 'st_login')) {
    $query = "SELECT * FROM gm_permissions WHERE id = :id";
    $dbh = db_connect();
    $action = $dbh->prepare($query);
    $action->bindValue('id', $userdata['user_id'], PDO::PARAM_INT);
    $action->execute();
    if($action->rowCount() > 0) {
        define('C_CUSTOM_USERNAME', $userdata['username']);
        define('C_CUSTOM_USERID', $userdata['user_id']);

        $_SESSION['username'] = str_replace('\'', '\\\'', C_CUSTOM_USERNAME);
        $_SESSION['userid'] = C_CUSTOM_USERID;
        $_SESSION['userGroup'] = 3;
        $_SESSION['is_invisible'] = isset($_GET['invisible']);

        $row = $action->fetch(PDO::FETCH_ASSOC);
        $icon = 'st.png';
        $userTypeId = 4; // regular ST
        if($row['Is_Asst'] == 'Y') {
            // $icon = 'asst.png';
            $userTypeId = 5;
        }
        if($row['Is_Admin'] == 'Y') {
            $icon = 'admin.png';
            $userTypeId = 6;
        }
        else if($row['Wiki_Manager'] == 'Y') {
            $icon = 'wiki.png';
            $userTypeId = 7;
        }

        addUser($icon, $userTypeId);

        $admin = ($row['Is_Admin'] == 'Y') ? 1 : 0;
        $mod = 0;
        if(($row['Is_Asst'] == 'Y') || ($row['Is_GM'] == 'Y') || ($row['Is_Head'] == 'Y')) {
            $mod = 1;
        }

        $query = <<<EOQ
UPDATE
    prochatrooms_users
SET
    display_name = :name,
    usergroup = '3',
    guest = '0',
    admin = '$admin',
    moderator = '$mod',
    avatar = '$icon'
WHERE
    username = :username
    AND userid = :userid
EOQ;

        $action = $dbh->prepare($query);
        $action->bindValue('userid', $userdata['user_id'], PDO::PARAM_INT);
        $action->bindValue('username', $userdata['username']);
        $action->bindValue('name', C_CUSTOM_USERNAME);
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
        $action = $dbh->prepare($query);
        $action->bindValue('userid', C_CUSTOM_USERID, PDO::PARAM_INT);
        $action->bindValue('username', C_CUSTOM_USERNAME);
        $action->execute();
        die('You do not have ST Permissions');
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
    $sql = <<<EOQ
SELECT
    user_id
FROM
    supporters
WHERE
    user_id = ?
    AND expires_on > NOW()
EOQ;

    $statement = $dbh->prepare($sql);
    $statement->execute(array($userdata['user_id']));
    if($statement->fetch()) {
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
    avatar = '$icon'
WHERE
    username = :username
    AND userid = :userid
EOQ;

    $action = $dbh->prepare($query);
    $action->bindValue('userid', C_CUSTOM_USERID, PDO::PARAM_INT);
    $action->bindValue('username', C_CUSTOM_USERNAME);
    $action->bindValue('name', C_CUSTOM_USERNAME);
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
    header('location:/login_ooc.php');
    die();
}

if(!$loggedIn)
{
	die("Not Logged In.");
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
$params = array(C_CUSTOM_USERNAME, C_CUSTOM_USERID, $userTypeId);
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

	if($remotely_hosted){

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

	if(!$remotely_hosted && !C_CUSTOM_LOGIN){

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