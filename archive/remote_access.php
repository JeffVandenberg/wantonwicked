<?php
ini_set('display_errors', 1);

include 'cgi-bin/start_of_page.php';
// perform required includes
define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './forum/';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.' . $phpEx);
include($phpbb_root_path . 'includes/functions_display.' . $phpEx);

$mayLogin = 1;
$group = 9;
$character_id = 0;
$permissions_file = 'permissions_desanctioned.txt';
//print_r($_GET);
if($_GET['password'] != 'PlanetStrike0912')
{
	//echo "ST LOGIN<br>";
	// Attempt ST Login
	$st_query = "SELECT * FROM 	phpbb_users WHERE username_clean = '" . utf8_clean_string($_GET['username']) . "'";
	$st_result = mysql_query($st_query) or die(mysql_error());
	$st_detail = mysql_fetch_array($st_result, MYSQL_ASSOC);
	
	if($st_detail)
	{
		//echo "Found ST<br>";
		if(phpbb_check_hash($_GET['password'], $st_detail['user_password']))
		{
			//echo "Passwords match!<br>";
			$permission_query = 'SELECT * FROM gm_permissions WHERE id = ' . $st_detail['user_id'];
			//echo "$permission_query<br>";
			$permission_result = mysql_query($permission_query) or die(mysql_error());
			$permission_detail = mysql_fetch_array($permission_result, MYSQL_ASSOC);
			$character_id = $permission_detail['ID'];
			//print_r($permission_detail);
			if($permission_detail['Is_Admin'] == 'Y')
			{
				$group = 102;
				$permissions_file = 'permissions_admin.txt';
			}
			else if($permission_detail['Is_Head'] == 'Y')
			{
				$group = 103;
				$permissions_file = 'permissions_head.txt';
			}
			else if($permission_detail['Is_GM'] == 'Y')
			{
				$group = 104;
				$permissions_file = 'permissions_st.txt';
			}
			else if($permission_detail['Is_Asst'] == 'Y')
			{
				$group = 105;
				$permissions_file = 'permissions_asst.txt';
			}
			else
			{
				$mayLogin = 0;
			}
		}
		else
		{
			//echo $_GET['password'] . ' did not validate!<br>';
			$mayLogin = 0;
		}
	}
	else
	{
		$mayLogin = 0;
	}
}
else
{
	//die('Character Login');
	$character_name = $_GET['username'];
	if(strpos($character_name, '-- ') > 0)
	{
		$character_name = substr($character_name, 0, strpos($character_name, '-- '));
	}
	$character_query = "select * from wod_characters where character_name = '$character_name';";
	$character_result = mysql_query($character_query) or die(mysql_error());
	$character_detail = mysql_fetch_array($character_result, MYSQL_ASSOC);
	$character_id = $character_detail['Character_ID'];
	
	$permissions_file = 'permissions_unsanctioned.txt';
	
	if($character_detail)
	{
		if($character_detail['Is_Sanctioned'] == 'Y')
		{
			switch($character_detail['Character_Type'])
			{
				case 'Vampire':
					$permissions_file = 'permissions_vampire.txt';
					break;
				case 'Mage':
					$permissions_file = 'permissions_mage.txt';
					break;
				case 'Werewolf':
					$permissions_file = 'permissions_werewolf.txt';
					break;
				case 'Geist':
					$permissions_file = 'permissions_geist.txt';
					break;
				case 'Changeling':
					$permissions_file = 'permissions_changeling.txt';
					break;
				case 'Hunter':
					$permissions_file = 'permissions_hunter.txt';
					break;
				case 'Possessed':
					$permissions_file = 'permissions_possessed.txt';
					break;
				default:
					$permissions_file = 'permissions_mortal.txt';
					break;
			}
		}
		
		if($character_detail['Is_Sanctioned'] == 'N')
		{
			//die('UnSanctioned');
			// ship to the unsanctioned group
			$group = 9;
			$permissions_file = 'permissions_desanctioned.txt';
		}
		
		//print_r($character_detail);
		if($character_detail['City'] == 'Side Game')
		{
			$group = 19;
			$permissions_file = 'permissions_sidegame.txt';
		}
		
		if($character_detail['is_suspended'] == 1) 
		{
			$group = 20;
			$permissions_file = 'permissions_suspended.txt';
		}
	}
	
	/*if($character_detail['Character_ID'] == 8551)
	{
		$group = 106;
		$permissions_file = 'permissions_william_hendricks.txt';
	}*/

	if(strtolower(substr($_GET['username'],-4)) == ' ooc')
	{
		$group = 15;
		$permissions_file = 'permissions_ooc.txt';
	}
	
	if(strpos(strtolower($character_name), 'zombie') === 0) {
		$permissions_file = 'permissions_mortal.txt';
	}
}

header("Content-type: text/plain");
$page_content = <<<EOQ
scras.version = 2.1
user.uid = $character_id
user.usergroup.id = $group
user.usergroup.can_login = $mayLogin\n\n
EOQ;
echo $page_content;
include "includes/permissions_desanctioned.txt";
?>
