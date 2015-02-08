<?php
use classes\core\helpers\SessionHelper;

ini_set('display_errors', 1);
include 'cgi-bin/start_of_page.php';
// perform required includes
define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './forum/';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.' . $phpEx);
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

// check page actions
$page_title = "";
$css_url = "wantonwicked.gamingsandbox.com/css/ww4_v2.css";
$menu_bar = "";
$top_image = "";
$page_content = "";
$java_script = "";
$extra_tags = "onLoad='showClock();'";
$template_file = "main_ww4.tpl";

// build links
include 'user_panel.php';
include 'menu_bar.php';
include 'menu_bar_city_book.php';

// common includes

include 'includes/classes/tenacy/abp/abp.php';
include 'includes/classes/tenacy/domain.php';

if(isset($_GET['action']))
{
	switch($_GET['action'])
	{
		case 'list':
			if($userdata['is_asst'] || $userdata['is_gm'] || $userdata['is_head'] || $userdata['is_admin'])
			{
				include 'includes/territory_list.php';
			}
			break;
		case 'update_all':
			if($userdata['is_asst'] || $userdata['is_gm'] || $userdata['is_head'] || $userdata['is_admin'])
			{
				include 'includes/territory_update_all.php';
			}
			break;
		case 'list_territories':
			include 'includes/territory_list_territories.php';
			break;
		case 'edit':
			include 'includes/territory_edit.php';
			break;
		case 'manage':
			include 'includes/territory_manage.php';
			break;
		case 'add':
			include 'includes/territory_add.php';
			$template_file = 'empty_template.tpl';
			break;
		case 'add_post':
			include 'includes/territory_add_post.php';
			$template_file = 'empty_template.tpl';
			break;
		case 'admin_add_character':
			include 'includes/territory_admin_add_character.php';
			$template_file = 'empty_template.tpl';
			break;
		case 'admin_add_character_post':
			include 'includes/territory_admin_add_character_post.php';
			$template_file = 'empty_template.tpl';
			break;
		case 'poach':
			include 'includes/territory_poach.php';
			$template_file = 'empty_template.tpl';
			break;
		case 'feed':
			include 'includes/territory_feed.php';
			$template_file = 'empty_template.tpl';
			break;
		case 'admin_remove_character':
			include 'includes/territory_admin_remove_character.php';
			$template_file = 'empty_template.tpl';
			break;
		case 'edit_post':
			include 'includes/territory_edit_post.php';
			$template_file = 'empty_template.tpl';
			break;
		case 'get_admin_associated_characters':
			include 'includes/territory_get_admin_associated_characters.php';
			$template_file = 'empty_template.tpl';
			break;
		case 'view':
			include 'includes/territory_view.php';
			$template_file = 'empty_template.tpl';
			break;
		default:
			include 'includes/index_default.php';
			break;
	}
}
else
{
	include 'includes/index_default.php';
}

$template->set_custom_template('templates', $template_file);
$template->assign_vars(array(
	"PAGE_TITLE" => $page_title,
	"CSS_URL" => $css_url, 
	"JAVA_SCRIPT" => $java_script,
	"USER_PANEL" => $user_panel, 
	"MENU_BAR" => $menu_bar, 
	"TOP_IMAGE" => $page_image, 
	"PAGE_CONTENT" => $page_content,
	"EXTRA_TAGS" => $extra_tags,
    "FLASH_MESSAGE" => SessionHelper::GetFlashMessage(),
    "SERVER_TIME" => (microtime(true) + date('Z'))*1000,
	)
);

$template->set_filenames(array(
		'body' => ($template_file))
);
$template->display('body');
?>