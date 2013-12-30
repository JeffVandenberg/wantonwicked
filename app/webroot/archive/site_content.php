<?
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

include 'user_panel.php';
include 'menu_bar.php';
include 'menu_bar_city_book.php';
include 'menu_bar_player_content.php';

if(isset($_GET['action']))
{
	switch($_GET['action'])
	{
		case 'view':
			include 'includes/site_content_view.php';
			break;
			
		case 'edit':
			if($userdata['content_mod'] || $userdata['is_admin'])
			{
				include 'includes/site_content_edit.php';
			}
			else
			{
				include 'includes/index_redirect.php';
			}
			break;
			
		case 'list_all':
			if($userdata['content_mod'] || $userdata['is_admin'])
			{
				include 'includes/site_content_list_all.php';
			}
			else
			{
				include 'includes/index_redirect.php';
			}
			break;
		
			
		default:
			include 'includes/site_content_index.php';
			break;
	}
}
else
{
	include 'includes/site_content_index.php';
}

$template->assign_vars(array(
"PAGE_TITLE" => $page_title,
"CSS_URL" => $css_url, 
"JAVA_SCRIPT" => $java_script,
"USER_PANEL" => $user_panel, 
"MENU_BAR" => $menu_bar, 
"TOP_IMAGE" => $page_image, 
"PAGE_CONTENT" => $page_content,
"EXTRA_TAGS" => $extra_tags
)
);


// initialize template
$template->set_custom_template('templates', 'main_layout');
$template->set_filenames(array(
		'body' => 'main_layout.tpl')
);
$template->display('body');
?>