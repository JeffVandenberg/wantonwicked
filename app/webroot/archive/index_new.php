<?
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
$css_url = "wicked_new.css";
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
		case 'login':
			include 'cgi-bin/authenticate.php';
			include 'cgi-bin/doLogin.php';
			include 'includes/index_login.php';
			// redo the userpanel and menu_bar
			include 'user_panel.php';
			include 'menu_bar.php';
			include 'menu_bar_player_content.php';
			break;
			
		case 'logout':
			include 'includes/index_logout.php';		
			// redo the userpanel and menu_bar
			include 'user_panel.php';
			include 'menu_bar.php';
			include 'menu_bar_player_content.php';

			break;
			
		case 'register':
			include 'includes/index_register.php';
			break;
			
		case 'pw_reset':
		  include 'includes/index_pw_reset.php';
		  break;
			
		case "submit_account":
			// confirm account information
			include 'cgi-bin/confirmAccount.php';
			include 'includes/index_submit_account.php';
			
			break;
			
		case 'validate':
			include 'includes/index_validate.php';
			break;
			
		case 'storytellers':
			include 'includes/index_storytellers.php';
			break;
			
		default:
			include 'includes/index_default.php';
	}
}
else
{
	include 'includes/index_default.php';
}


//print_r($template);
// initialize template
$template->set_custom_template('templates', 'main_layout');
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

$template->set_filenames(array(
		'body' => 'main_layout_new.tpl')
);
$template->display('body');
//page_footer();
?>