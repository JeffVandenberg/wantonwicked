<?
include 'cgi-bin/start_of_page.php';

// perform required includes
define('IN_PHPBB', true);
$phpbb_root_path = './forum/';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);

$template = new Template("/templates/");

// check page actions
$page_title = "";
$css_url = "www.wantonwicked.net/wicked.css";
$menu_bar = "";
$top_image = "";
$page_content = "";
$java_script = "";

if(isset($_GET['action']))
{
	//echo $_GET['action']."<br>";
	switch($_GET['action'])
	{
		case 'login':
			include 'cgi-bin/authenticate.php';
			include 'cgi-bin/doLogin.php';
			include 'includes/index_login.php';
			break;
			
		case 'logout':
			include 'includes/index_logout.php';		
			break;
			
		case 'register':
			include 'includes/index_register.php';
			break;
			
		case "submit_account":
			// confirm account information
			include 'cgi-bin/confirmAccount.php';
			include 'includes/index_submit_account.php';
			
			break;
			
		case 'validate':
			include 'includes/index_validate.php';
			break;
			
		default:
			include 'includes/index_default.php';
	}
}
else
{
	include 'includes/index_default.php';
}

// build links

	$menu_bar = <<<EOQ
&nbsp; <a href="$_SERVER[PHP_SELF]" class="linkmenu">Home</a><br>
&nbsp; <a href="utilities.php" class="linkmenu">User Utilities</a><br>
<br>
&nbsp; Links will go Here<br>
&nbsp; Site Content<br>
&nbsp; User Information<br>
<br>
EOQ;

// check if user is logged in
if($_SESSION['is_logged_in'])
{
	$user_panel = <<<EOQ
$_SESSION[user_name] - 
<a href="$_SERVER[PHP_SELF]?action=logout">Logout</a> - 
<a href="forum/index.php">Forums</a> -
(Chat)
EOQ;
}
else
{
	$user_panel = <<<EOQ
<a href="$_SERVER[PHP_SELF]?action=login&goto=$_SERVER[PHP_SELF]">Login</a> - 
<a href="$_SERVER[PHP_SELF]?action=register">Register</a> - 
<a href="forum/index.php">Forums</a> - 
(Chat)
EOQ;
}


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
		'body' => 'templates/main_layout.tpl')
);
$template->pparse('body');
?>