<?
$account = confirmAccount($_POST);

// if it validates
if($account['is_valid'])
{
	// generate a random verification number
	$verification_number = mt_rand(1000000,10000000);

	// start to insert login information
	$now = date('Y-m-d H:i:s');

	$lock_query = "lock tables login write, permissions write, phpbb_users write, phpbb_groups write, phpbb_user_group write;";
	$lock_result = mysql_query($lock_query) || die(mysql_error());

	$name = addslashes($_POST['user_name']);
	$pass = md5($_POST['password1']);
	$first_name = addslashes($_POST['first_name']);
	$last_name = addslashes($_POST['last_name']);
	$email = addslashes($_POST['email']);
	
	$trans_query = "begin;";
	$trans_result = mysql_query($trans_query) || die(mysql_error());
	
	$login_id = getNextID($connection, "login", "ID");

	$login_query = "insert into login values ($login_id, '$name', '$account[birth_date]', '$pass', '', '$_SERVER[REMOTE_ADDR]', '$_SERVER[REMOTE_ADDR]', '$now', '$now',  $verification_number, 'N',  '$email' );";
	
	$login_result = mysql_query($login_query) || die(mysql_error()."1");

	//echo "1<br>";
	$permissions_query = "insert into permissions values (null, $login_id, 'Y', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N');";
	$permissions_result = mysql_query($permissions_query) || die(mysql_error());
	
	// insert record into PHPBB
  // insert into phpbb_users
  $user_id = getNextID($connection, "phpbb_users", "user_id");
  
  $now = time();

  $user_query = "insert into phpbb_users values ( $user_id, 1, '$name', '$pass', 0, 0, 0, " . time() .", 0, 0, 0, 3, 'english', 'D M d, Y g:i a', 0, 0, 0, '', 0, 1, 0, 1, 1, 1, 1, 1, 0, 1, 1, 0, '', 0, '$email', 0, '', '', '', '', '', '', '', '', '', '', '', '', '');";
  
  //echo "$user_query<br>";
  
  mysql_query($user_query) || die(mysql_error());
  
  //die(transactionRollback($mysqli, "debug"));

  // insert into phpbb_group
  $group_id = getNextID($connection, "phpbb_groups", "group_id");
  
  $group_query = "insert into phpbb_groups values ($group_id, 1, '', 'Personal User', 0, 1);";

  mysql_query($group_query) || die(mysql_error());
  
  // insert into phpbb_user_group
  $user_group_query = "insert into phpbb_user_group values ($group_id, $user_id, 0);";
  mysql_query($user_group_query) || die(mysql_error());
  
	$trans_query = "commit;";
	$trans_result = mysql_query($trans_query) || die(mysql_error());
	
	$unlock_query = "unlock tables;";
	$unlock_result = mysql_query($unlock_query);
	

	// setup email
	$email = $_POST['email'];
	$title = "EMail Verification";
	$url = str_replace(" ", "%20", "http://www.wantonwicked.net/index.php?action=validate&account_name=$_POST[user_name]&validation_number=$verification_number&id=$login_id");
	$extra_headers = "from: Wanton Wicked Admin <admin@wantonwicked.net>";

	$message = <<<EOQ
Welcome to Wanton Wicked,
This is an email to confirm your email address. To activate your email account, either click the link below or copy and paste it into your web browser.
${url}&account=activate
If you have recieved this email in error, you may either disregard this email or follow the following link to help keep my database clean.
${url}&account=remove

Thanks,

Wanton Wicked Administrator
EOQ;

	mail($email, $title, $message, $extra_headers);

	$page_title = "Account Finalized";
	$menu_bar = "Links";
  $page_content = <<<EOQ
An email has been sent to $_POST[email], there will be simple instructions in the email about how to verify your email account.  Feel free to explore the rest of the site. <a href="$_SERVER[PHP_SELF]">Return to home page</a>.
EOQ;
	
}
else
{
	$page_title = "Create Account";
	$menu_bar = "Links";
	$page_content = <<<EOQ
Welcome to account creation! <br>
<br>
By creating an account you are agreeing the the Terms and Conditions (link later) of the site. Long story short, play nice, and we don't have to break any kneecaps.<br>
<br>
$account[notes]
<br>
<form name="account_creation" method="post" action="$_SERVER[PHP_SELF]?action=submit_account">
<table border="0" cellpadding="0" cellspacing="2" class="normal_text">
<tr>
<td>
User Name:
</td>
<td>
<input type="text" name="user_name" id="user_name" value="$_POST[user_name]" class="normal_text">
</td>
</tr>
<tr>
<td>
Date of Birth (M/D/Y):
</td>
<td>
<input type="text" maxlength="2" size="3" name="month" value="$_POST[month]" class="normal_text"> /
<input type="text" maxlength="2" size="3" name="day" value="$_POST[day]" class="normal_text"> /
<input type="text" maxlength="4" size="4" name="year" value="$_POST[year]" class="normal_text">
</td>
</tr>
<tr>
<td>
Email Address:
</td>
<td>
<input type="text" name="email" size="40" maxlength="40" value="$_POST[email]" class="normal_text">
</td>
</tr>
<tr>
<td>
  Password:
</td>
<td>
	<input type="password" name="password1" size="40" maxlength="40" class="normal_text">
</td>
</tr>
<tr>
<td>
	Confirm Password:
</td>
<td>
  <input type="password" name="password2" size="40" maxlength="40" class="normal_text">
</td>
</tr>
<tr>
<td colspan="2" align="center">
	<input type="submit" value="Create Account">
</td>
</tr>
</table>
</form>
EOQ;
}

?>