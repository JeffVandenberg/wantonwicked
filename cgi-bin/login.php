<?
include 'dbconnect.php';
include 'authenticate.php';
include 'session_initialize.php';
include 'doLogin.php';

$goto = (isset($_GET['redirect'])) ? $_GET['redirect'] : "/index.html";
$goto = (isset($_POST['redirect'])) ? $_POST['redirect'] : $goto;

define("IN_LOGIN", true);

define('IN_PHPBB', true);
$phpbb_root_path = './../phpBB2/';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);

// test if they are logging in
// get values
$temp_user_name = (isset($_POST['user_name'])) ? $_POST['user_name'] : "";
$temp_password = (isset($_POST['password'])) ? md5($_POST['password']) : "";

$first_login = (isset($_GET['first_login'])) ? true : false;

doLogin($mysqli, $temp_user_name, $temp_password, $goto, $first_login);
?>