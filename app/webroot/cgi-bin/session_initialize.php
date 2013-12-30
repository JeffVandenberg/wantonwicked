<?
// start session
ini_set("session.use_only_cookies", "1");

if(!session_id())
{
	session_start();
}

/*// help prevent session fixation
if(!isset($_SESSION['last_ip']))
{
	// set the IP
	$_SESSION['last_ip'] = $_SERVER['REMOTE_ADDR'];
}
else
{
	// compare IP
	if($_SESSION['last_ip'] != $_SERVER['REMOTE_ADDR'])
	{
		// IP change in middle of session is probably an attack, destroy the session
		session_destroy();
		session_start();
	}
}*/

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");    // Date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate");  // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache"); // HTTP/1.0

if (!isset($_SESSION['user_name']) || !isset($_SESSION['is_logged_in']))
{
	//echo "initializing session variables<br>";
	$_SESSION['user_name']="Guest User";
	$_SESSION['user_pass']="-------";
	$_SESSION['is_logged_in'] = false;
	$_SESSION['user_id'] = 0;
	$_SESSION['is_asst'] = false;
	$_SESSION['is_gm'] = false;
	$_SESSION['is_head'] = false;
	$_SESSION['is_admin'] = false;
	$_SESSION['site_admin'] = false;
	$_SESSION['content_mod'] = false;
	$_SESSION['site_id'] = "0000";
}

?>
