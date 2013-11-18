<?php
include 'cgi-bin/start_of_page.php';

// perform required includes
define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './forum/';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
/** @noinspection PhpIncludeInspection */
include($phpbb_root_path . 'common.' . $phpEx);
/** @noinspection PhpIncludeInspection */
include($phpbb_root_path . 'includes/functions_display.' . $phpEx);

//
// Start session management
//
$user->session_begin();
$auth->acl($user->data);
$userdata = $user->data;
//
// End session management
//

$character_id = isset($_POST['character_id']) ? $_POST['character_id'] + 0 : 0;
$character_id = isset($_GET['character_id']) ? $_GET['character_id'] + 0: $character_id;

$log_npc = isset($_POST['log_npc']) ? $_POST['log_npc'] : "n";
$log_npc = isset($_GET['log_npc']) ? $_GET['log_npc'] : $log_npc;

if(($userdata['is_asst'] || $userdata['is_gm'] || $userdata['is_head'] || $userdata['is_admin']) && ($log_npc == 'y'))
{
	$character_query = <<<EOQ
SELECT wod.Character_ID, Character_Name, l.Name
FROM (wod_characters as wod INNER JOIN login_character_index as lci ON wod.character_id = lci.character_id) INNER JOIN login as l on lci.login_id = l.id
WHERE wod.character_id = $character_id
	AND is_npc = 'Y';
EOQ;
}
else
{
	$character_query = <<<EOQ
SELECT wod.Character_ID, Character_Name, l.Name
FROM (wod_characters as wod INNER JOIN login_character_index as lci ON wod.character_id = lci.character_id) INNER JOIN login as l on lci.login_id = l.id
WHERE wod.character_id = $character_id
	AND lci.login_id = $userdata[user_id]
EOQ;
}

$character_result = mysql_query($character_query) or die(mysql_error());

if(mysql_num_rows($character_result))
{
	// found a character
	$character = mysql_fetch_array($character_result, MYSQL_ASSOC);
	
	// if may login character
	$row_width = ($userdata['is_admin'] || ($userdata['user_id'] == 2324)) ? "0px" : 0;
	
	$page = <<<EOQ
<html>
<head>
<title>Character Interface: $character[Character_Name]</title>
</head>
<frameset cols="$row_width,*" border="0" frameborder="0">
  <frame src="character.php?action=interface&character_id=$character_id&log_npc=$log_npc" id="char_home" name="char_home">
</frameset>
</body></html>
EOQ;
	
}
else
{
	// didn't find a character
	$page = <<<EOQ
<html>
<head>
<title></title>
</head>
<script language="JavaScript">
	//window.location.href = "index.php";
</script>
</body></html>
EOQ;
}

echo $page;