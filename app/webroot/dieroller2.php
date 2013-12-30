<?
include 'cgi-bin/start_of_page.php';
include 'cgi-bin/rollWoDDice.php';

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

$template = new Template("/templates/");

// check page actions
$page_title = "";
$css_url = "ww4_v2.css";
$menu_bar = "";
$top_image = "";
$page_content = "";
$java_script = "";
$template_layout = "main_ww4.tpl";

if(isset($_GET['action']))
{
  echo $_GET['action']."<br>";
	switch($_GET['action'])
	{
		case 'character':
			include 'includes/dieroller_character.php';
			break;
		case 'ooc':
			$page_content = "OOC Die Roller";
			include 'includes/dieroller_ooc.php';
			break;
		case 'st':
			$page_content = "Storyteller Die Roller";
			break;
		default:
			$page_content = "OOC Die Roller";
	}
}
else
{
	$page_content = "OOC Die Roller";
}

$template->set_custom_template('templates', substr($template_layout,0,strlen($template_layout)-4));
$template->assign_vars(array(
"PAGE_TITLE" => $page_title,
"CSS_URL" => $css_url, 
"JAVA_SCRIPT" => $java_script,
"USER_PANEL" => $user_panel, 
"MENU_BAR" => $menu_bar, 
"TOP_IMAGE" => $page_image, 
"PAGE_CONTENT" => $page_content
)
);

// initialize template
$template->set_filenames(array(
		'body' => $template_layout)
);
$template->display('body');
?>

