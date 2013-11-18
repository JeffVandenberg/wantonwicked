<?php
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
$css_url = "www.wantonwicked.net/wicked.css";
$menu_bar = "";
$top_image = "";
$page_content = "";
$java_script = "";
$extra_tags = "onLoad='showClock();'";

// build links
include 'user_panel.php';
include 'menu_bar.php';
include 'menu_bar_city_book.php';
include 'menu_bar_player_content.php';

if(isset($_GET['action']))
{
	//echo $_GET['action']."<br>";
	switch($_GET['action'])
	{
		case 'list':
			$page_content = "List Inputs";
			break;
			
		case 'view':
			include 'includes/monthly_input_view.php';
			break;
			
		default:
			include 'includes/index_default.php';
	}
}
else
{
	include 'includes/index_default.php';
}


$template->set_custom_template('templates', 'main_layout');
$template->assign_vars(array(
        "PAGE_TITLE" => $page_title,
        "CSS_URL" => $css_url,
        "JAVA_SCRIPT" => $java_script,
        "USER_PANEL" => $user_panel,
        "MENU_BAR" => $menu_bar,
        "TOP_IMAGE" => $page_image,
        "PAGE_CONTENT" => $page_content,
        "EXTRA_TAGS" => $extra_tags,
        "CONTENT_HEADER" => $contentHeader
    )
);

$template->set_filenames(array(
        'body' => 'main_layout4.tpl')
);
$template->display('body');
