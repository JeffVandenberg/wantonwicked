<?
$goto = (isset($_POST['goto'])) ? $_POST['goto'] : "";
$goto = (isset($_GET['goto'])) ? $_GET['goto'] : $goto;

if(!empty($_POST['login_name']))
{
	$login_query = "select * from login INNER JOIN permissions on login.id = permissions.id where name='$_POST[login_name]';";
	//echo $login_query."<br>";
	$login_result = mysql_query($login_query) or die(mysql_Error());
	
	if(mysql_fetch_array($login_detail, MYSQLI_ASSOC))
	{
		if($login_detail['May_Login'] == 'Y')
		{
		  // generate new password
		  $password_chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
		  $pass_length = 8;
		  
		  $new_pass = "";
		  
		  for($i = 0; $i < $pass_length; $i++)
		  {
  		  $selected_char = mt_rand(0, strlen($password_chars)-1);
  		  
  		  $new_pass .= substr($password_chars, $selected_char, 1);
		  }
		  
  		// add slashes to the user name
  		$temp_name = addslashes($login_detail['Name']);
  		
  		$md5_pass = md5($new_pass);
  		
		  // update login
		  $update_query = "update login set password='$md5_pass' where name='$temp_name';";
		  $update_result = mysql_query($update_query) or die(mysql_error());
		  
		  // update phpbb_users
		  $update_query = "update phpbb_users set user_password = '$md5_pass' where username = '$temp_name';";
		  $update_result = mysql_query($update_query) or die(mysql_error());
		  		  
			$recipient_email = $login_detail['Email'];
			$email_title = "Wanton Wicked Password Reset";
			$email_message = <<<EOQ
Your password was changed to be sent to you in email.
Your Password is: $new_pass.  It is recommended that you change your password after you login.

If you experience any further problems, contact me at jeffv@wantonwicked.net.

Thanks,

Jeff Vandenberg
WontonWicked.Net Administrator
EOQ;
      mail($recipient_email, $email_title, $email_message, "From: Jeff Vandenberg <jeffv@wantonwicked.net>");
		}
		else
		{
			$message = "That Account has been disabled.<br>";
		}
	}
	$message .= <<<EOQ
Notification Email has been sent.<br>
<a href="$_SERVER[PHP_SELF]?action=login&goto=$goto">Return to Login Page</a><br>
EOQ;

	$show_form = false;
}

if ($show_form)
{
	$message = <<<EOQ
<form method="post" action="$_SERVER[PHP_SELF]?action=ps_reset&goto=$goto">
Enter Your Login Name:
<input type="text" name="login_name" id="login_name" size="30" maxlength="40" value=""><br>
<input type="submit" value="Request Password">
</form>
EOQ;
}

$page_content = <<<EOQ
$message
EOQ;
?>