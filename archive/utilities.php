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

if(isset($_GET['action']))
{
	//echo $_GET['action']."<br>";
	switch($_GET['action'])
	{
		case 'update':
			include 'cgi-bin/confirmAccount.php';
			include 'includes/utilities_update.php';
			break;
		case 'reconfirm_email':
		  include 'includes/utilities_reconfirm_email.php';
		  break;
		default:
			include 'includes/utilities_default.php';
	}
}
else
{
	include 'includes/utilities_default.php';
}

// build links
include 'user_panel.php';
include 'menu_bar.php';

if(!$userdata['user_id'])
{
	$java_script = <<<EOQ
<script language="JavaScript" version="1.2">
	window.document.location.href="index.php";
</script>
EOQ;
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
"EXTRA_TAGS" => $extra_tags
)
);

// initialize template
$template->set_filenames(array(
		'body' => 'main_layout.tpl')
);
$template->display('body');
?>
