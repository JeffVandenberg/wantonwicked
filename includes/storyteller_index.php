<?php
/* @var array $userdata */

use classes\core\helpers\UserdataHelper;

$contentHeader = $page_title = "Storyteller Utilities";

ob_start();
?>

    <h3>Characters</h3>
    <a href="/view_sheet.php?action=st_view_xp" Name>View Characters</a><br>
    <a href="/st_tools.php?action=character_name_lookup" Name>Do a partial search of character Names</a><br>

    <h3>Requests</h3>
    <a href="/request.php?action=st_list">View Requests</a><br>
    <a href="/storyteller_index.php?action=profile_lookup" Name>Find out what characters are connected to a profile</a>

    <h3>Chat</h3>
    <a href="/chat/?st_login" target="_blank">Chat Login (Visible)</a><br>
    <a href="/chat/includes/clean_rooms.php">Clean Temp Rooms</a><br />
<?php if(UserdataHelper::IsHead($userdata)): ?>
    <a href="/chat/admin/">Prochat Administration</a><br />
<?php endif; ?>

    <h3>Tools</h3>
    <a href="/dieroller.php?action=ooc">OOC Roller</a><br/>
<?php if(UserdataHelper::IsHead($userdata)): ?>
    <a href="/storyteller_index.php?action=permissions">Manage Permissions</a><br>
    <a href="/st_tools.php?action=icons_list">Manage Icons</a><br>
    <a href="/st_tools.php?action=profile_transfer">Transfer a Character to a new Profile</a><br>
<?php endif; ?>

    <h3>Reports</h3>
    <a href="/st_tools.php?action=character_search" Name>Character Type Search</a><br>
    <a href="/st_tools.php?action=power_search" Name>Power and Merit Search</a><br>
    <a href="/st_tools.php?action=character_activity" Name>Character Activity</a><br>
<?php if (UserdataHelper::IsHead($userdata)): ?>
    <a href="/request.php?action=admin_time_report">Request Time Report</a><br />
<?php endif; ?>

<?php echo $head_st_tools; ?>

<?php
$page_content = ob_get_clean();
