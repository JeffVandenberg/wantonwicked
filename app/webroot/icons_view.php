<?
use classes\core\helpers\UserdataHelper;

include 'cgi-bin/start_of_page.php';
// perform required includes
define('IN_PHPBB', true);
$phpbb_root_path = './forum/';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);

//
// Start session management
//
$userdata = session_pagestart($user_ip, WEBSITE);
init_userprefs($userdata);
//
// End session management
//

if(!UserdataHelper::IsHead($userdata))
{
	die();
}

// check page actions
$page_title = "Icon View";
$css_url = "ww4_v2.css";
$menu_bar = "";
$top_image = "";
$page_content = "";
$java_script = "";

include 'user_panel.php';
include 'menu_bar.php';

$page = "";
$contents = "";
$alert = "";
$js = "";

// form variables
$icon_name = "";
$icon_id = "";
$player_viewable_check = "";
$gm_viewable_check = "";
$admin_viewable_check = "";

$id = 0;
$id = (isset($_POST['id'])) ? $_POST['id'] +0 : $id;
$id = (isset($_GET['id'])) ? $_GET['id'] +0 : $id;

// test if submitting values
if(isset($_POST['icon_name']) && isset($_POST['icon_id']) && (UserdataHelper::IsHead($userdata)))
{
	// set variables
  $icon_name = htmlspecialchars($_POST['icon_name']);
  $icon_id = (!empty($_POST['icon_id'])) ? $_POST['icon_id'] +0 : 0;
  $player_viewable = (isset($_POST['player_viewable'])) ? "Y" : "N";
  $gm_viewable = (isset($_POST['gm_viewable'])) ? "Y" : "N";
  $admin_viewable = (isset($_POST['admin_viewable'])) ? "Y" : "N";
  
	$icon_query = "update icons set icon_name='$icon_name', icon_id=$icon_id, player_viewable='$player_viewable', gm_viewable='$gm_viewable', admin_viewable='$admin_viewable' where id=$id;";
	//echo "$icon_query<br>";
	$icon_result = mysql_query($icon_query) || die(mysql_error());
		
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
$icon_query = "select * from icons where id = $id;";
$icon_result = mysql_query($icon_query) || die(mysql_error());

if(mysql_num_rows($icon_result))
{
	$icon_detail = mysql_fetch_array($icon_result, MYSQL_ASSOC);
	$player_viewable_check = ($icon_detail['Player_Viewable'] == 'Y') ? "checked" : "";
	$gm_viewable_check = ($icon_detail['GM_Viewable'] == 'Y') ? "checked" : "";
	$admin_viewable_check = ($icon_detail['Admin_Viewable'] == 'Y') ? "checked" : "";
	
	$contents = <<<EOQ
<form method="post" action="$_SERVER[PHP_SELF]">
<table border="0" cellspacing="2" cellpadding="2" class="normal_text">
  <tr>
    <td>
      <b>Icon Name:</b>
    </td>
    <td>
      <input type="text" name="icon_name" id="icon_name" size="20" maxlength="35" value="$icon_detail[Icon_Name]">
    </td>
  </tr>
  <tr>
    <td>
      <b>Icon ID:</b>
    </td>
    <td>
      <input type="text" name="icon_id" id="icon_id" size="4" maxlength="4" value="$icon_detail[Icon_ID]">
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
$alert
$contents
EOQ;

$template->assign_vars(array(
"PAGE_TITLE" => $page_title,
"CSS_URL" => $css_url, 
"JAVA_SCRIPT" => $java_script,
"USER_PANEL" => $user_panel, 
"MENU_BAR" => $menu_bar, 
"TOP_IMAGE" => $page_content_image, 
"PAGE_CONTENT" => $page_content
)
);


// initialize template
$template->set_filenames(array(
		'body' => 'templates/blank_layout.tpl')
);
$template->pparse('body');

?>