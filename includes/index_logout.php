<?
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
$_SESSION['content_mod'] = false;
$_SESSION['site_id'] = "0000";

// log out of phpBB
$userdata = session_pagestart($user_ip, PAGE_LOGIN);
init_userprefs($userdata);

if( $userdata['session_logged_in'] )
{
  session_end($userdata['session_id'], $userdata['user_id']);
}

$page_title = "Logout";
$menu_bar = "Link";
$page_content = <<<EOQ
You have been logged out of WantonWicked.net. Enjoy your stay. <a href="$_SERVER[PHP_SELF]">Return to WantonWicked.net.</a>
EOQ;

$goto = str_replace("|", "&", $_GET['goto']);
$java_script = <<<EOQ
<script language="JavaScript" version="1.2">
	window.document.location.href="$goto";
</script>
EOQ;
?>