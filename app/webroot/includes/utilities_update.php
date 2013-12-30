<?
$page_title = "Update Profile";

$show_form = true;
$notes = "";

$login_information_query = "select * from login where id=$userdata[user_id];";
$login_information_result = mysql_query($login_information_query) or die(mysql_error());
$login_information_detail = mysql_fetch_array($login_information_result, MYSQL_ASSOC);
	
if(isset($_POST['update_submit']))
{
	// validate information they have submited
	$pass = "";
	$email_addr = "";
	
	// check if passwords are filled in
	if(!empty($_POST['password1']))
	{
		if($_POST['password1'] != $_POST['password2'])
		{
			$notes .= "<b>Passwords didn't match</b>.<br>";
		}
		else
		{
			$pass = md5($_POST['password1']);
		}
	}
	
	// check if email is filled different
	if(!empty($_POST['email']) && ($_POST['email'] != $login_information_detail['Email']))
	{
		if (!eregi("^[a-z0-9]+([_.-][a-z0-9]+)*@([a-z0-9]+([.-][a-z0-9]+)*)+\\.[a-z]{2,4}$", $_POST['email']) )
		{
			$notes .= "<b>Please enter a valid email</b>.<br>";
		}
		else
		{
			$email_addr = $_POST['email'];
		}
	}
	
	// verify birthdate
	$date_check = verifyDate ($_POST['year'], $_POST['month'], $_POST['day']);

	if (!$date_check['verified'])
	{
  	// the date that they put in doesn't check
  	$notes .= "<b>$date_check[message]</b><br>";
	}
	
	if($notes == "")
	{
		// validated
		$show_form = false;
		$confirmation_number = 0;
		
		$login_query = "update login set Birthdate='$date_check[date]'";
		$phpbb_query = "update phpbb_users set ";
		
		// check if updating password
		if($pass != "")
		{
			$login_query .= ", password='$pass'";
			$phpbb_query .= "user_password = '$pass', ";
		}
		
		// check if updating email
		if($email_addr != "")
		{
			$confirmation_number = mt_rand(1000000,10000000);
			$login_query .= ", email = '".htmlspecialchars(addslashes($_POST['email']))."', is_confirmed='N', confirmation_number = '$confirmation_number' ";
			$phpbb_query .= "user_email = '".htmlspecialchars(addslashes($_POST['email']))."', ";
		}
		
		// close off the login query
		$login_query .= "where ID=$userdata[user_id];";
		
		// begin transaction
		$transaction_query = "begin;";
		$transaction_result = mysql_query($transaction_query) or die(mysql_error());
		
		// perform login_query
		//echo $login_query."<br>";
		$login_result = mysql_query($login_query) or die(mysql_error());
		
		// test if any additions were made to the phpbb_query
		if($phpbb_query != "update phpbb_users set ")
		{
			// add user id to end
  		$phpbb_query = substr($phpbb_query, 0, strlen($phpbb_query)-2);
  		
  		$phpbb_query .= " where username = '$_POST[login_name]';";
  		//echo $phpbb_query."<br>";
  		$phpbb_result = mysql_query($phpbb_query) or die(mysql_error());
		}
		
		$transaction_query = "commit;";
		$transaction_result = mysql_query($transaction_query) or die(mysql_error());
		
		if($confirmation_number)
		{
			// send a new confirmation email
			$email = $_POST['email'];
			$title = "EMail Verification";
			$url = str_replace(" ", "%20", "http://www.wantonwicked.net/index.php?action=validate&account_name=$userdata[user_name]&validation_number=$confirmation_number&id=$userdata[user_id]");
			$extra_headers = "from: Wanton Wicked Admin <admin@wantonwicked.net>";
			
			$message = <<<EOQ
Welcome to Wanton Wicked,
This is an email to confirm your email address change. To validate your email account, either click the link below or copy and paste it into your web browser.
${url}&account=activate
If you have recieved this email in error, you may either disregard this email or follow the following link to help keep my database clean.
${url}&account=remove

Thanks,

Wanton Wicked Administrator
EOQ;

			mail($email, $title, $message, $extra_headers);
			
			$page_content .= <<<EOQ
An email has been sent to your updated email address to confirm it.<br>
<br>
EOQ;
		}
		
		$page_content .= <<<EOQ
Your information has been updated in the database. Thanks. 
EOQ;
	}
}

if($show_form)
{
	$login_id = $login_information_detail['ID'];
	$login_name =  (!empty($_POST['login_name'])) ? $_POST['login_name'] : $login_information_detail['Name'];
	$email =  (!empty($_POST['email'])) ? $_POST['email'] : $login_information_detail['Email'];
	$year = (!empty($_POST['year'])) ? $_POST['year']+0: substr($login_information_detail['Birthdate'],0,4);
	$month = (!empty($_POST['month'])) ? $_POST['month']+0: substr($login_information_detail['Birthdate'],5,2);
	$day = (!empty($_POST['day'])) ? $_POST['day']+0: substr($login_information_detail['Birthdate'],8,2);
	

	// show form for updating their profile
	$page_content = <<<EOQ
<form method="post" action="$_SERVER[PHP_SELF]?action=update">
  $notes
  <table width="100%" border="0" cellspacing="2" cellpadding="2" class="normal_text">
    <tr>
      <td width="40%">Login ID:</td>
      <td width="60%">
        <input type="text" name="login_id" size="40" maxlength="40" readonly value="$login_id" style="background=$blocked_input_color">
      </td>
    <tr>
      <td width="40%">Login Name:</td>
      <td width="60%">
        <input type="text" name="login_name" size="40" maxlength="40" readonly value="$login_name" style="background=$blocked_input_color">
      </td>
    </tr>
    <tr>
      <td width="40%">Password:</td>
      <td width="60%">
        <input type="password" name="password1" size="40" maxlength="40">
      </td>
    </tr>
    <tr>
      <td width="40%">Confirm Password: </td>
      <td width="60%">
        <input type="password" name="password2" size="40" maxlength="40">
      </td>
    </tr>
    <tr>
      <td width="40%">Email Address:</td>
      <td width="60%">
        <input type="text" name="email" size="40" maxlength="40" value="$email">
      </td>
    </tr>
    <tr>
      <td width="40%">Birthday (Month/Day/Year)</td>
      <td width="60%">
        <input type="text" maxlength="2" size="3" name="month" value="$month"> /
        <input type="text" maxlength="2" size="3" name="day" value="$day"> /
        <input type="text" maxlength="4" size="4" name="year" value="$year">
      </td>
    </tr>
    <tr>
      <td colspan="2">
      	By Clicking Submit You are agreeing to the Terms &amp; Conditions of Wanton Wicked
      </td>
    </tr>
    <tr>
      <td colspan="2" align="center">
      	<input type="submit" name="update_submit" value="Submit">
      </td>
    </tr>
  </table>
	</form>
EOQ;
}
else
{
	// announce that they have successfully updated their profile
}

?>