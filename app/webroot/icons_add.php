<?php
use classes\core\helpers\Response;
use classes\core\helpers\UserdataHelper;
use classes\core\repository\Database;

include 'cgi-bin/start_of_page.php';
// perform required includes
define('IN_PHPBB', true);
$phpbb_root_path = './forum/';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.' . $phpEx);

//
// Start session management
//
$userdata = session_pagestart($user_ip, WEBSITE);
init_userprefs($userdata);
//
// End session management
//

if (!UserdataHelper::IsHead($userdata)) ;
{
    Response::redirect('/', 'You may not assign icons.');
}

// check page actions
$page_title = "Icon Add";
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
if (isset($_POST['icon_name']) && isset($_POST['icon_id']) && (UserdataHelper::IsHead($userdata))) {
    // set variables
    $icon_name = htmlspecialchars($_POST['icon_name']);
    $icon_id = (!empty($_POST['icon_id'])) ? $_POST['icon_id'] + 0 : 0;
    $player_viewable = (isset($_POST['player_viewable'])) ? "Y" : "N";
    $gm_viewable = (isset($_POST['gm_viewable'])) ? "Y" : "N";
    $admin_viewable = (isset($_POST['admin_viewable'])) ? "Y" : "N";

    $icon_query = "insert into icons values (null, '$icon_name', $icon_id, $userdata[site_id], '$player_viewable', '$gm_viewable', '$admin_viewable');";
    $icon_result = Database::getInstance()->query($icon_query)->execute();

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
<form method="post" action="$_SERVER[PHP_SELF]">
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
      <span class="highlight">Icon ID:</span>
    </td>
    <td>
      <input type="text" name="icon_id" id="icon_id" size="4" maxlength="4" value="$icon_id">
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
