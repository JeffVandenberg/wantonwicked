<?
// log out of fro
include 'session_initialize.php';
$_SESSION['user_name']="Guest User";
$_SESSION['user_pass']="-------";
$_SESSION['is_logged_in'] = false;
$_SESSION['user_id'] = 0;
$_SESSION['letters_moderator'] = false;
$_SESSION['is_asst'] = false;
$_SESSION['is_gm'] = false;
$_SESSION['is_head'] = false;
$_SESSION['is_admin'] = false;
$_SESSION['site_admin'] = false;
$_SESSION['news_mod'] = false;
$_SESSION['fiction_mod'] = false;
$_SESSION['site_id'] = "0000";
$_SESSION['theme_id'] = 0;

// log out of phpBB
define("IN_LOGIN", true);

define('IN_PHPBB', true);
$phpbb_root_path = './../phpBB2/';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);
$userdata = session_pagestart($user_ip, PAGE_LOGIN);
init_userprefs($userdata);

if( $userdata['session_logged_in'] )
{
  session_end($userdata['session_id'], $userdata['user_id']);
}

$goto = "";

if(!empty($_GET['redirect']))
{
	$goto = $_GET['redirect'];
}

if(!empty($_POST['redirect']))
{
	$goto = $_POST['redirect'];
}

header("Location: http://".$_SERVER['HTTP_HOST'].$goto);

include 'start_of_page2.php';
$page = <<<EOQ
You are being logged out. If you are not redirected, click <a href="$goto">HERE</a>. To continue on.
EOQ;
echo $page;
include 'end_of_page2.php';
?>