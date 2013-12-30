<?php
// check page actions
use classes\core\helpers\UserdataHelper;
use classes\core\repository\Database;

$contentHeader = $page_title = "Icon Add";

$page = "";
$contents = "";
$alert = "";
$js = "";
$show_form = true;
$mode = "debug";

// form variables
$icon_name = "";
$icon_id = "";
$is_asst = "N";
$player_viewable_check = "";
$is_gm = "N";
$gm_viewable_check = "";
$is_head = "N";
$admin_viewable_check = "";
$letters_moderator = "N";
$letters_moderator_check = "";

// test if submitting values
if(isset($_POST['icon_name']) && isset($_POST['icon_id']))
{
	// set variables
  $icon_name = htmlspecialchars($_POST['icon_name']);
  $icon_id = (!empty($_POST['icon_id'])) ? $_POST['icon_id'] : 0;
  $player_viewable = (isset($_POST['player_viewable'])) ? "Y" : "N";
  $gm_viewable = (isset($_POST['gm_viewable'])) ? "Y" : "N";
  $admin_viewable = (isset($_POST['admin_viewable'])) ? "Y" : "N";
  
	$icon_query = "insert into icons values (null, ?, ?, ?, ?, ?);";
    Database::GetInstance()->Query($icon_query)->Execute(array($icon_name, $icon_id, $player_viewable, $gm_viewable, $admin_viewable));
		
	// add js
	$java_script = <<<EOQ
<script language="JavaScript">
window.opener.location.reload();
window.opener.focus();
window.close();
</script>
EOQ;

}

$contents = <<<EOQ
<form method="post" action="$_SERVER[PHP_SELF]?action=icons_add">
    <table border="0" cellspacing="2" cellpadding="2" class="normal_text">
        <tr>
            <td>
                <span class="highlight">Icon Name:</span>
            </td>
            <td>
                <input type="text" name="icon_name" id="icon_name" size="20" maxlength="35" value="$icon_name">
            </td>
        </tr>
        <tr>
            <td>
                <span class="highlight">File:</span>
            </td>
            <td>
                <input type="text" name="icon_id" id="icon_id" size="10" maxlength="20" value="$icon_id">
            </td>
        </tr>
        <tr>
            <td>
                Player Viewable:
            </td>
            <td>
                <input type="checkbox" name="player_viewable" id="player_viewable" value="Y" $player_viewable_check>
            </td>
        </tr>
        <tr>
            <td>
                ST Viewable:
            </td>
            <td>
                <input type="checkbox" name="gm_viewable" id="gm_viewable" value="Y" $gm_viewable_check><br>
            </td>
        </tr>
        <tr>
            <td>
                Head ST Viewable:
            </td>
            <td>
                <input type="checkbox" name="admin_viewable" id="admin_viewable" value="Y" $admin_viewable_check>
            </td>
        </tr>
        <tr>
            <td colspan="2" align="center">
                <input type="submit" value="Submit">
            </td>
        </tr>
    </table>
</form>
EOQ;

$page_content = <<<EOQ
$js
$alert
$contents
EOQ;
?>
