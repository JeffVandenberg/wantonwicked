<?php
global $userdata;
$get_vars = "";
$is_first = true;
while(list($key, $value) = each($_GET))
{
  if($key != 'sid')
  {
    if($is_first)
    {
    	$get_vars .= "?$key=$value";
    	$is_first = false;
    }
    else
    {
    	$get_vars .= "&$key=$value";
    }
  }
}
reset($_GET);

$get_vars = str_replace("&", "|", $get_vars);
$redirect = $_SERVER['PHP_SELF'].$get_vars;

$up_name = <<<EOQ
<a href="/forum/ucp.php?mode=login&redirect=$redirect">Login</a>
EOQ;

$up_loginout = <<<EOQ
<a href="/forum/ucp.php?mode=register&redirect=$redirect">Register</a>
EOQ;

$userControlPanel = "";
if($userdata['user_id'] != 1)
{
    $logout = append_sid("/forum/ucp.php", "mode=logout&redirect=$redirect", true, $user->session_id);
    $up_name = <<<EOQ
<span>$userdata[username]</span>
EOQ;

    $up_loginout = <<<EOQ
<a href="$logout">Logout</a>
EOQ;

    $userControlPanel = <<<EOQ
-
<a href="forum/ucp.php">User Control Panel</a>
EOQ;
}

$user_panel = <<<EOQ
$up_name - 
$up_loginout
$userControlPanel
<span id="server-time"></span>
EOQ;


// ugly, but such is life.
function getUpName()
{
  global $up_name;
  return $up_name;
}

function getUpLogInOut()
{
  global $up_loginout;
  return $up_loginout;
}

function getUserControlPanel()
{
  global $userControlPanel;
  return $userControlPanel;
}

$server_month = date("m") - 1;
//echo (-substr(date('O'), 2, strlen(date('O')))/100 - 4) . "<br>";
$server_hour = date("H") + $timezone_adjustment;