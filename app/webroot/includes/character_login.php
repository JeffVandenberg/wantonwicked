<?
$character_id = isset($_POST['character_id']) ? $_POST['character_id'] + 0 : 0;
$character_id = isset($_GET['character_id']) ? $_GET['character_id'] + 0: $character_id;

$log_npc = isset($_POST['log_npc']) ? $_POST['log_npc'] : "n";
$log_npc = isset($_GET['log_npc']) ? $_GET['log_npc'] : $log_npc;

if(($userdata['is_asst'] || $userdata['is_gm'] || $userdata['is_head'] || $userdata['is_admin']) && ($log_npc == 'y'))
{
	$character_query = <<<EOQ
SELECT wod.*, l.Name
FROM (wod_characters as wod INNER JOIN login_character_index as lci ON wod.character_id = lci.character_id) INNER JOIN login as l on lci.login_id = l.id
WHERE wod.character_id = $character_id
	AND is_npc = 'Y';
EOQ;
}
else
{
	$character_query = <<<EOQ
SELECT wod.*, l.Name
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
	
	// record the login
	$login_query = "update wod_characters set last_login = '" . date('Y-m-d H:i:s') . "', logged_today='Y' where character_id = $character[Character_ID];";
  
  $login_result = mysql_query($login_query) or die(mysql_error());
  
	// prepare app
	$login_name = $character['Character_Name'];
	
	// check status
	if($character['Status'] != 'Ok')
	{
  	$login_name = "$login_name -- " . $character['Status'];
	}
	
	/*$site = 1001;
	$description = <<<EOQ
DESCRIPTION: $character_detail[Description]&nbsp;&nbsp;
PUBLIC EFFECTS: [$character_detail[City]] $character_detail[Public_Effects]&nbsp;&nbsp;
EQUIPMENT: $character_detail[Equipment_Public]
EOQ;

	$exit_line = $character_detail['Exit_Line'];
	$age = $character_detail['Age'];
	if(($character_detail['Character_Type'] == 'Vampire') || ($character_detail['Character_Type'] == 'Ghoul'))
	{
  	$age = $character_detail['Apparent_Age'];
	}
	
	$sex = $character_detail['Sex'];// ($character_detail['Sex'] == 'Male') ? "M" : "F";
	$url = $character_detail['URL'];
	
	// set icon
	// default to X
	$icon = 1000;
	
	// icons for PCs
	if($character_detail['Is_Sanctioned'] == 'Y' )
	{
   	$icon = $character_detail['Icon'];
		// determine if they are hiding their icon and are approved
		if(($character_detail['Hide_Icon'] == 'Y') && ($character_detail['Is_NPC'] == 'N'))
		{
	  	switch($character_detail['Character_Type'])
	  	{
	    	case 'Vampire':
	    	  $icon = 1044;
	    	  break;
	    	case 'Werewolf':
	    	  $icon = 1045;
	    	  break;
	    	case 'Mage':
	    	  $icon = 1042;
	    	  break;
	  	}
		}
	}
	
	if($character_detail['Is_Sanctioned'] == '')
  {
  	$icon = 1057;
    	
  	$ooc_pos = strpos($character_detail['Character_Name'], " OOC");
  	if((strlen($character_detail['Character_Name']) - 4) == $ooc_pos)
  	{
    	$icon = 1006;
  	}
	}
	
	// icons for NPCs
	if(($character_detail['Head_Sanctioned'] == 'Y' ) && ($character_detail['Is_NPC'] == 'Y'))
	{
  	$icon = $character_detail['Icon'];
	}
	
	// icons for side game characters
	if($character_detail['City'] == 'Side Game')
	{
  	$icon = 1075;
	}
	
	// get buddy list
	$buddy_query = "select Character_Name from buddies where login_id = $userdata[user_id] order by character_name;";
	$buddy_result = mysql_query($buddy_query) or die(mysql_error());
	
	$buddy_list = "";
	while($buddy_detail = mysql_fetch_array($buddy_result, MYSQL_ASSOC))
	{
  	if($buddy_list != "")
  	{
    	$buddy_list .= ",";
  	}
  	
  	$buddy_list .= $buddy_detail['Character_Name'];
	}*/
	
	//$page_content = buildDigiApplet($login_name, $site, $description, $exit_line, $icon, $age, $sex, $url, $userdata['username'], $buddy_list);
	$page_content = buildAddOnChatApplet($login_name);
}

?>