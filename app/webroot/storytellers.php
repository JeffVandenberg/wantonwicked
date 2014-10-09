<?php
include 'cgi-bin/start_of_page.php';

// perform required includes
define('IN_PHPBB', true);
$phpbb_root_path = './forum/';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);

//
// Start session management
//
$userdata = session_pagestart($user_ip, WEBSITE);
init_userprefs($userdata);
//
// End session management
//

// check page actions
$page_title = "";
$css_url = "wantonwicked.gamingsandbox.com/css/ww4_v2.css";
$menu_bar = "";
$top_image = "";
$page_content = "";
$java_script = "";
$extra_tags = "onLoad='showClock();'";

$page_title = "WantonWicked Storytellers";

$page_image = <<<EOQ
<img src="http://www.wantonwicked.net/img/istorytellers.gif" alt="Wanton Wicked Storytellers"><br><br>
EOQ;

$page_content = <<<EOQ
Below is a list of the current Storytellers for WantonWicked. When able I have email accounts set up for the gamemasters, which are easy to remember.  Feel free to email the GMs any particular questions that you may have regarding the game.<br>
<br>
EOQ;

// build list of Head GMs
$head_gm_query = "select gm_permissions.*, login.Name from gm_permissions inner join login on gm_permissions.id = login.id where gm_permissions.site_id=1004 and gm_permissions.position != 'Hidden' and gm_permissions.is_head='Y' order by login.Name;";
$head_gm_result = mysql_query($head_gm_query) or die(mysql_error());

$page_content .= <<<EOQ
<table border="0" cellpadding="2" cellspacing="2" class="normal_text">
  <tr>
    <th colspan="2">
      Head STs
    </th>
  </tr>
  <tr bgcolor="#000000">
    <th>
      GM Name
    </th>
    <th>
      Email
    </th>
  </tr>
EOQ;

$row = 0;
while($head_gm_detail = mysql_fetch_array($head_gm_result, MYSQL_ASSOC))
{
	$email_address = "";
	if($head_gm_detail['Email_Address'] != "")
	{
		$email_address = "<a href=\"mailto:$head_gm_detail[Email_Address]\" class=\"linkmain\">$head_gm_detail[Email_Address]</a>";
	}
	
	$row_color = (($row++)%2) ? "#443a33" : "";
	
	$page_content .= <<<EOQ
	<tr bgcolor="$row_color">
	  <td>
	    $head_gm_detail[Name]
	  </td>
	  <td>
	    $email_address
	  </td>
	</tr>
EOQ;
}

$page_content .= "</table><br>";

// build list of regular GMs
$gm_query = "select gm_permissions.*, login.Name from gm_permissions inner join login on gm_permissions.id = login.id where gm_permissions.site_id=1004 and gm_permissions.position != 'Hidden' and gm_permissions.is_head='N' and gm_permissions.is_gm = 'Y' order by login.Name;";
$gm_result = mysql_query($gm_query) or die(mysql_error());

$page_content .= <<<EOQ
<table border="0" cellpadding="2" cellspacing="2" class="normal_text">
  <tr>
    <th colspan="4"cl ass="contentheading">
      Full STs
    </th>
  </tr>
  <tr bgcolor="#000000">
    <th>
      GM Name
    </th>
    <th>
      Section
    </th>
    <th>
      Position
    </th>
    <th>
      Email
    </th>
  </tr>
EOQ;

$row = 0;
while($gm_detail = mysql_fetch_array($gm_result, MYSQL_ASSOC))
{
	$email_address = "";
	if($gm_detail['Email_Address'] != "")
	{
		$email_address = "<a href=\"mailto:$gm_detail[Email_Address]\" class=\"linkmain\">$gm_detail[Email_Address]</a>";
	}
	
	$row_color = (($row++)%2) ? "#443a33" : "";
	
	$page_content .= <<<EOQ
	<tr bgcolor="$row_color">
	  <td>
	    $gm_detail[Name]
	  </td>
	  <td>
	    $gm_detail[Game_Area]
	  </td>
	  <td>
	    $gm_detail[Position]
	  </td>
	  <td>
	    $email_address
	  </td>
	</tr>
EOQ;
}

$page_content .= "</table><br>";

// build list of Cell GMs
$asst_gm_query = "select gm_permissions.*, login.Name from gm_permissions inner join login on gm_permissions.id = login.id where gm_permissions.site_id=1004 and gm_permissions.position != 'Hidden' and gm_permissions.is_head='N' and gm_permissions.is_gm='N' order by login.Name;";
$asst_gm_result = mysql_query($asst_gm_query) or die(mysql_error());

$page_content .= <<<EOQ
<table border="0" cellpadding="2" cellspacing="2" class="normal_text">
  <tr>
    <th colspan="4">
      Cell STs
    </th>
  </tr>
  <tr>
    <td colspan="4">
      These are guidelines and the Cell STs are by no means limiting their bounds to these descriptions.
    </td>
  </tr>
  <tr bgcolor="#000000">
    <th>
      GM Name
    </th>
    <th>
    	City
    </th>
    <th>
      Cell ID
    </th>
    <th>
      Cell Description
    </th>
    <th>
      Email
    </th>
  </tr>
EOQ;

$row = 0;
while($wikiMgr = mysql_fetch_array($asst_gm_result, MYSQL_ASSOC))
{
	$email_address = "";
	if($wikiMgr['Email_Address'] != "")
	{
		$email_address = "<a href=\"mailto:$wikiMgr[Email_Address]\" class=\"linkmain\">$wikiMgr[Email_Address]</a>";
	}
	
	$row_color = (($row++)%2) ? "#443a33" : "";
	
	$page_content .= <<<EOQ
	<tr bgcolor="$row_color">
	  <td>
	    $wikiMgr[Name]
	  </td>
	  <td>
	  	$wikiMgr[City]
	  </td>
	  <td>
	    $wikiMgr[Cell_ID]
	  </td>
	  <td>
	    $wikiMgr[Cell_Description]
	  </td>
	  <td>
	    $email_address
	  </td>
	</tr>
EOQ;
}

$page_content .= "</table>";



// build links
include 'user_panel.php';
include 'menu_bar.php';

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
		'body' => 'templates/main_ww4.tpl')
);
$template->pparse('body');
?>

