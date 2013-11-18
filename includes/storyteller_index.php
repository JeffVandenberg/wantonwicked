<?php
/* @var array $userdata */

$page_title = "Storyteller Utilities";
$contentHeader = $page_title;
$head_st_tools = "";

if($userdata['is_head'])
{
	$head_st_tools = <<<EOQ
<h3>Head ST tools:</h3>
<a href="http://72.167.98.223/DigiChat/DigiClasses/Resources/WantonWicked/Transcripts/"Name>View Chat Logs</a> (Login: wawGMs  Password: WODarkness)<br>
<br>
<a href="$_SERVER[PHP_SELF]?action=permissions">Manage Permissions</a><br>
<a href="st_tools.php?action=icons_list">Manage Icons</a><br>
<a href="st_tools.php?action=profile_transfer">Transfer a Character to a new Profile</a><br>
EOQ;
}

if($userdata['cell_id'] == '')
{
	$mainSiteTools = <<<EOQ
<br />
<h3>Extra ST Tools:</h3><br /><br />
<a href="chat/?st_login" target="_blank">ST Login (Visible)</a><br>
<!--<a href="abp.php">ABP & Domain Management</a><br>--->
EOQ;

}

ob_start();
?>

<a href="/chat/?st_login" target="_blank">Chat Login (Visible)</a><br>
<a href="/view_sheet.php?action=st_view_xp"Name>View Characters</a><br>
<a href="/request.php?action=st_list">View Requests</a><br>
<a href="/storyteller_index.php?action=profile_lookup"Name>Find out what characters are connected to a profile</a><br>
<br>
<a href="/dieroller.php?action=ooc">OOC Roller</a><br />
<a href="/st_tools.php?action=character_name_lookup"Name>Do a partial search of character Names</a><br>
<a href="/st_tools.php?action=character_search"Name>Character Type Search</a><br>
<a href="/st_tools.php?action=power_search"Name>Power and Merit Search</a><br>
<a href="/st_tools.php?action=character_activity"Name>Character Activity</a><br>
<br />

<?php echo $head_st_tools; ?>

<?php
$page_content = ob_get_clean();
