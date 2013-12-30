<?
function buildUserBox ( $add_time, $section_highlight_bgcolor, $section_highlight_background )
{
	$time_section = "";
	$user_alert = "";
	$user_extra = "";
// set function components
if ($add_time)
{
	$time_section = <<<EOQ
            <div id="local_time"></div>
            <div id="server_time"></div>
EOQ;
}
// test logged in variable if it is set
if (!empty($_SESSION['is_logged_in']))
{
	if ($_SESSION['is_logged_in'])
	{
		// they are currently logged in
		$user_title = "Logged In";

		// if is a game master
		if ($_SESSION['is_asst'] || $_SESSION['is_gm'])
		{
			$user_extra .= <<<EOQ
			  -<a href="/gamemaster/" target="_blank">GameMaster Utilities</a><br>
EOQ;
		}
		
		// if is a site admin
		if ($_SESSION['site_admin'])
		{
			$user_extra .= <<<EOQ
			  -<a href="/admin_tools.fro" target="_blank">Site Admin Utilities</a><br>
EOQ;
		}
		
		// if is a news moderator
		if ($_SESSION['news_mod'])
		{
			$user_extra .= <<<EOQ
			  -<a href="/news_tools.fro" target="_blank">News Utilities</a><br>
EOQ;
		}

		// if is a content moderator
		if ($_SESSION['content_mod'])
		{
			$user_extra .= <<<EOQ
			  -<a href="/content_manager.fro" target="_blank">Manage Content</a><br>
EOQ;
		}
		
		$user_extra .= <<<EOQ
		            -<a href="utilities.fro" onClick="open('utilities.fro', 'utilitiesWindow', 'width=400, height=400'); return false;">Utilities</a><br>
                -<a href="/functions/logout.php?redirect=$_SERVER[PHP_SELF]">Log Out</a>
EOQ;
	}
	else
	{
		// they are marked as logged out
		$user_title = "Not Logged In";
	  $user_name = "Guest User";
	  $redirect = "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
	  $user_extra = <<<EOQ
              <form method="post" action="/functions/login.php">
                Name:<br>
                <input name="user_name" type="text" size="15" maxlength="40"><br>
                Pass:<br>
                <input name="password" type="password" size="15" maxlength="40"><br>
                <input type="hidden" name="login" value="y">
                <input type="hidden" name="redirect" value="$redirect">
                <input type="submit" value="Log In">
              </form>
              <a href="create_account.fro" onClick="open('create_account.fro','createAccount', 'width=400,height=400,resizable,scrollbars');return false;">Create An Account</a><br>
EOQ;
	}
}
else
{
	// they have not attempted to log in yet.
	$user_title = "Not Logged In";
	$user_name = "Guest User";
	$redirect = "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
	//$redirect = "http://64.6.88.126".$_SERVER['PHP_SELF'];
  $user_extra = <<<EOQ
               <form method="post" action="/functions/login.php">
               Name:<br>
               <input name="user_name" type="text" size="15" maxlength="40"><br>
               Pass:<br>
               <input name="password" type="password" size="15" maxlength="40"><br>
               <input type="hidden" name="login" value="y">
               <input type="hidden" name="redirect" value="$redirect">
               <input type="submit" value="Log In">
              </form>
              <a href="create_account.fro" onClick="open('create_account.fro','createAccount', 'width=400,height=400,resizable,scrollbars');return false;">Create An Account</a><br>
EOQ;
}

// build user login/information/utilties box
$user_box = <<<EOQ
        <tr bgcolor="$section_highlight_bgcolor">
          <td align="center" class="heading">$user_title</td>
        </tr>
        <tr>
          <td class="normal_text">
            $user_alert
            $_SESSION[user_name]<br>
            <br>
            $time_section
            <br>
            $user_extra
          </td>
        </tr>
        <tr>
          <td>
            &nbsp;
          </td>
        </tr>\n
EOQ;

  return $user_box;
}
?>