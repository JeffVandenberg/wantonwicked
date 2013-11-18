<?php
$character_id = isset($_POST['character_id']) ? $_POST['character_id'] + 0 : 0;
$character_id = isset($_GET['character_id']) ? $_GET['character_id'] + 0: $character_id;

$page_content = "Logging in $character_id";
$may_login = false;

// test if they can log in the character
if(($_SESSION['is_asst'] || $_SESSION['is_gm'] || $_SESSION['is_admin']) && (isset($_GET['log_npc'])))
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
$character = ExecuteQueryItem($character_query);

if($character != null)
{
	$may_login = false;
    $appletCode = buildAddOnChatApplet($character['Character_Name']);

    $page_content = <<<EOQ
Logging in: $character[Character_Name]<br />
$appletCode
EOQ;

}
else
{
	// didn't find a character
	$java_script = <<<EOQ
<script language="JavaScript">
	document.close();
</script>
EOQ;
}
