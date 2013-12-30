<?
function doLogin($username, $password)
{
// perform authentication
$permissions = authenticate($username, $password);
$return = "";
if ($permissions['authenticated'])
{
	//session_regenerate_id();
	$return = true;
	
	// they are authenticated
	$temp_user_id = $permissions['ID'];
		
	$now = date('Y-m-d H:i:s');
	
	$temp_ip = $_SERVER['REMOTE_ADDR'];
	
	$update_login_query = "update login set Last_IP='$temp_ip', Last_Login='$now' where ID = '$temp_user_id';";
	$update_result = mysql_query($update_login_query) or die(mysql_error());


	//echo "setting up PHPBB stuff<br>";
	global $db, $template, $user_ip;
	$userdata = session_pagestart($user_ip, PAGE_LOGIN);
	init_userprefs($userdata);
	//
	// End session management
	//
	
	// session id check
	if (!empty($_POST['sid']) || !empty($_GET['sid']))
	{
		$sid = (!empty($_POST['sid'])) ? $_POST['sid'] : $_GET['sid'];
	}
	else
	{
		$sid = '';
	}
	
	if(!$userdata['session_logged_in'])
	{
  	//echo "user isn't logged in<br>";
		$username = phpbb_clean_username($username);
	
		$sql = "SELECT user_id, username, user_password, user_active, user_level
			FROM " . USERS_TABLE . "
			WHERE username = '" . str_replace("\\'", "''", $username) . "'";
		if ( !($result = $db->sql_query($sql)) )
		{
  		//echo "couldn't get the user's information<br>";
			message_die(GENERAL_ERROR, 'Error in obtaining userdata', '', __LINE__, __FILE__, $sql);
		}
	
		if( $row = $db->sql_fetchrow($result) )
		{
			//echo "there is a row of information<br>";

			if( $row['user_level'] != ADMIN && $board_config['board_disable'] )
			{
				redirect(append_sid("index.$phpEx", true));
			}
			else
			{
				if( $password == $row['user_password'] && $row['user_active'] )
				{
					$autologin = ( isset($_POST['autologin']) ) ? TRUE : 0;
					
					$session_id = session_begin($row['user_id'], $user_ip, PAGE_INDEX, FALSE, $autologin);
	
					if( $session_id )
					{
						$url = ( !empty($_POST['redirect']) ) ? str_replace('&amp;', '&', htmlspecialchars($_POST['redirect'])) : "index.$phpEx";
						//redirect(append_sid($url, true));
					}
					else
					{
						message_die(CRITICAL_ERROR, "Couldn't start session : login", "", __LINE__, __FILE__);
					}
				}
				else
				{
					$redirect = ( !empty($_POST['redirect']) ) ? str_replace('&amp;', '&', htmlspecialchars($_POST['redirect'])) : '';
					$redirect = str_replace('?', '&', $redirect);
	
					if (strstr(urldecode($redirect), "\n") || strstr(urldecode($redirect), "\r"))
					{
						message_die(GENERAL_ERROR, 'Tried to redirect to potentially insecure url.');
					}
	
					$template->assign_vars(array(
						'META' => "<meta http-equiv=\"refresh\" content=\"3;url=login.$phpEx?redirect=$redirect\">")
					);
	
					$message = $lang['Error_login'] . '<br /><br />' . sprintf($lang['Click_return_login'], "<a href=\"login.$phpEx?redirect=$redirect\">", '</a>') . '<br /><br />' .  sprintf($lang['Click_return_index'], '<a href="' . append_sid("index.$phpEx") . '">', '</a>');
	
					message_die(GENERAL_MESSAGE, $message);
				}
			}
		}
		else
		{
			$redirect = ( !empty($_POST['redirect']) ) ? str_replace('&amp;', '&', htmlspecialchars($_POST['redirect'])) : "";
			$redirect = str_replace("?", "&", $redirect);
	
			if (strstr(urldecode($redirect), "\n") || strstr(urldecode($redirect), "\r"))
			{
				message_die(GENERAL_ERROR, 'Tried to redirect to potentially insecure url.');
			}
	
			$template->assign_vars(array(
				'META' => "<meta http-equiv=\"refresh\" content=\"3;url=login.$phpEx?redirect=$redirect\">")
			);
	
			$message = $lang['Error_login'] . '<br /><br />' . sprintf($lang['Click_return_login'], "<a href=\"login.$phpEx?redirect=$redirect\">", '</a>') . '<br /><br />' .  sprintf($lang['Click_return_index'], '<a href="' . append_sid("index.$phpEx") . '">', '</a>');
	
			message_die(GENERAL_MESSAGE, $message);
		}
	}

}
else
{
	$return = false;
}
  
	return $return;
  // login to phpbb
}
?>
