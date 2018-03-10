<?php
/* @var array $userdata */
// check page actions
use classes\core\helpers\Request;
use classes\core\repository\Database;

$page = "";
$contents = "";
$js = "";

// form variables
$icon_name = "";
$icon_id = "";
$player_viewable_check = "";
$gm_viewable_check = "";
$admin_viewable_check = "";

$id = Request::getValue('id', 0);

// test if submitting values
if(isset($_POST['icon_name']) && isset($_POST['icon_id']))
{
	// set variables
  $icon_name = htmlspecialchars($_POST['icon_name']);
  $icon_id = (!empty($_POST['icon_id'])) ? $_POST['icon_id'] : '';
  $player_viewable = (isset($_POST['player_viewable'])) ? "Y" : "N";
  $gm_viewable = (isset($_POST['gm_viewable'])) ? "Y" : "N";
  $admin_viewable = (isset($_POST['admin_viewable'])) ? "Y" : "N";
  
	$icon_query = "update icons set icon_name=?, icon_id=?, player_viewable=?, gm_viewable=?, admin_viewable=? where id=?;";
    Database::getInstance()->query($icon_query)->execute(array($icon_name, $icon_id, $player_viewable, $gm_viewable, $admin_viewable, $id));

	// add js
	$java_script = <<<EOQ
<script language="JavaScript">
window.opener.location.reload();
window.opener.focus();
window.close();
</script>
EOQ;

}

// get details
$icon_query = "select * from icons where id = ?";
$icon = Database::getInstance()->query($icon_query)->single(array($id));

if($icon !== false)
{
    $contentHeader = $page_title = "Icon: " . $icon['Icon_Name'];
    $player_viewable_check = ($icon['Player_Viewable'] == 'Y') ? "checked" : "";
	$gm_viewable_check = ($icon['GM_Viewable'] == 'Y') ? "checked" : "";
	$admin_viewable_check = ($icon['Admin_Viewable'] == 'Y') ? "checked" : "";
	
	$contents = <<<EOQ
<form method="post" action="$_SERVER[PHP_SELF]?action=icons_view">
    <table border="0" cellspacing="2" cellpadding="2" class="normal_text">
        <tr>
            <td>
                <b>Icon Name:</b>
            </td>
            <td>
                <input type="text" name="icon_name" id="icon_name" size="20" maxlength="35" value="$icon[Icon_Name]">
            </td>
        </tr>
        <tr>
            <td>
                <b>File:</b>
            </td>
            <td>
                <input type="text" name="icon_id" id="icon_id" size="10" maxlength="20" value="$icon[Icon_ID]">
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
                <input type="hidden" name="id" id="id" value="$id">
                <input type="submit" value="Submit">
            </td>
        </tr>
    </table>
</form>
EOQ;
}

$page_content = <<<EOQ
$js
$contents
EOQ;
