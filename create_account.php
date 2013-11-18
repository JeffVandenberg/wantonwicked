<?
// variables required for includes
$page_title="Create Account";
$add_bars = false;
// script includes
include 'start_of_page.php';
include 'transactions.php';

//if($_SERVER['REMOTE_ADDR'] != "192.168.1.1") die("Do not create accounts right now!");

// script variables
$notes = "";
$first_name = "";
$last_name = "";
$login_name = "";
$email = "";
$year = "";
$month = "";
$day = "";

$transaction_mode = "debug";
$show_form = true;

// start to build page
$page = "";

// test if they have submitted anything.
if (!empty($_POST['first_name']) || !empty($_POST['last_name']) || !empty($_POST['password1']) || !empty($_POST['password2']) || !empty($_POST['email']) || !empty($_POST['login_name']) || !empty($_POST['month']) || !empty($_POST['day'])  || !empty($_POST['year']))
{
	// they have submitted some information / check it for errors
	if (empty($_POST['first_name']) || empty($_POST['last_name']) || empty($_POST['password1']) || empty($_POST['password2']) || empty($_POST['email']) || empty($_POST['login_name']) || empty($_POST['month']) || empty($_POST['day'])  || empty($_POST['year']))
	{
		if (empty($_POST['login_name']))
		{
			$notes .= "<div class=\"red_highlight\">Please enter a Login Name.</div>\n";
		}
		if (empty($_POST['first_name']))
		{
			$notes .= "<div class=\"red_highlight\">Please enter your First Name.</div>\n";
		}
		if (empty($_POST['last_name']))
		{
			$notes .= "<div class=\"red_highlight\">Please enter your Last Name.</div>\n";
		}
		if (empty($_POST['password1']) || empty($_POST['password2']))
		{
			$notes .= "<div class=\"red_highlight\">Please enter both Passwords for authentication purposes.</div>\n";
		}
		if (empty($_POST['email']))
		{
			$notes .= "<div class=\"red_highlight\">Please enter your Email Address.</div>\n";
		}
		if (empty($_POST['month']))
		{
			$notes .= "<div class=\"red_highlight\">Please enter your Birth Month.</div>\n";
		}
		if (empty($_POST['day']))
		{
			$notes .= "<div class=\"red_highlight\">Please enter your Birth Day.</div>\n";
		}
		if (empty($_POST['year']))
		{
			$notes .= "<div class=\"red_highlight\">Please enter your Birth Year.</div>\n";
		}
	}
	else // all relavent information is in
	{
		if ($_POST['password1'] != $_POST['password2'] )
		{
			$notes .= "<div class=\"red_highlight\">Please enter both password for authentication purposes.</div>\n";
		}
		else // passwords are the same
		{
			if (!eregi("^[a-z0-9]+([_.-][a-z0-9]+)*@([a-z0-9]+([.-][a-z0-9]+)*)+\\.[a-z]{2,4}$", $_POST['email']) )
			{
				$notes .= "<div class=\"red_highlight\">Please enter a valid email.</div>\n";
			}
			else // email verifies (basically)
			{
      	$date_check = verifyDate ($_POST['year'], $_POST['month'], $_POST['day']);

      	if (!$date_check['verified'])
      	{
	      	// the date that they put in doesn't check
	      	$notes .= $date_check['message'];
      	}
      	else
      	{
      	  // check if the login name already exists
      	  $name = addslashes($_POST['login_name']);
      	  $login_check_query = "select * from login where name='$name';";
      	  $login_check_result = $mysqli->query($login_check_query);
      	  
      	  if($login_check_result->num_rows)
  				{
    				$notes .= "<div class=\"red_highlight\">Select a different login name.</div>\n";
  				}
  				else
      	  {
  	      	// don't show form
  	      	
  	      	$show_form = false;
  
  	      	// process the information and begin insert
  
  					// generate a random verification number
  	      	$verification_number = mt_rand(1000000,10000000);
  
  	      	// find out what sub directory the file is in
  	        $pos = strrpos($_SERVER['PHP_SELF'], '/');
  	        $sub_dir = substr($_SERVER['PHP_SELF'], 0, $pos );
  
  					// start to insert login information
  	      	$now = date('Y-m-d H:i:s');
  
  	      	$lock_query = "lock tables login write, permissions write, phpbb_users write, phpbb_groups write, phpbb_user_group write;";
  	      	$lock_result = $mysqli->query($lock_query);
  
  	      	transactionBegin($mysqli);
  
  	      	$name = addslashes($_POST['login_name']);
  	      	$pass = md5($_POST['password1']);
  	      	$first_name = addslashes($_POST['first_name']);
  	      	$last_name = addslashes($_POST['last_name']);
  	      	$email = addslashes($_POST['email']);
  	      	
  	      	$login_id = getNextID($mysqli, "login", "ID");
  
  	      	$login_query = "insert into login values ($login_id, '$name', '$date_check[date]', '$pass', '', '$_SERVER[REMOTE_ADDR]', '$_SERVER[REMOTE_ADDR]', '$now', '$now', '$first_name', '$last_name', $verification_number, 'N',  '$email' );";
  
  	      	$login_result = $mysqli->query($login_query) or transactionRollback($mysqli, $transaction_mode);
  
  	      	//echo "1<br>";
  	      	$id_query = "select ID from login where name='$_POST[login_name]';";
  	      	$id_result = $mysqli->query($id_query) or transactionRollback($mysqli, $transaction_mode);
  	      	$id_detail = $id_result->fetch_array(MYSQLI_ASSOC);
  
  	      	//echo "1<br>";
  	      	$permissions_query = "insert into permissions values (null, $id_detail[ID], 'Y', 'N', 1, 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N');";
  	      	$permissions_result = $mysqli->query($permissions_query) or transactionRollback($mysqli, $transaction_mode);
  	      	
  	      	// insert record into PHPBB
            // insert into phpbb_users
            $user_id = getNextID($mysqli, "phpbb_users", "user_id");
            
            $now = time();
            $user_query = "insert into phpbb_users values ( $user_id, 1, '$name', '$pass', 0, 0, 0, " . time() .", 0, 0, 0, 1, 'english', 'D M d, Y g:i a', 0, 0, 0, '', 0, 1, 0, 1, 1, 1, 1, 1, 0, 1, 1, 0, '', 0, '$email', 0, '', '', '', '', '', '', '', '', '', '', '');";
            
            //echo "$user_query<br>";
            
            $mysqli->query($user_query) or die(transactionRollback($mysqli, "debug"));;
            
            //die(transactionRollback($mysqli, "debug"));
          
            // insert into phpbb_group
            $group_id = getNextID($mysqli, "phpbb_groups", "group_id");
            
            $group_query = "insert into phpbb_groups values ($group_id, 1, '$name', 'Personal User', 0, 1);";
          
            $mysqli->query($group_query) or die(transactionRollback($mysqli, "debug"));;
            
            // insert into phpbb_user_group
            $user_group_query = "insert into phpbb_user_group values ($group_id, $user_id, 0);";
            $mysqli->query($user_group_query) or die(transactionRollback($mysqli, "debug"));;
  
  	      	transactionCommit($mysqli);
  
  	      	$unlock_query = "unlock tables;";
  	      	$unlock_result = $mysqli->query($unlock_query);
  	      	
  
  	      	// setup email
  	      	$email = $_POST['email'];
  	      	$title = "EMail Verification";
  	      	$url = str_replace(" ", "%20", "http://www.fiveringsonline.com${sub_dir}/verify_account.fro?account_name=$_POST[login_name]&validation_number=$verification_number&id=$id_detail[ID]");
  
  	      	$message = <<<EOQ
Welcome to Five Rings Online,
This is an email to confirm your email address. To activate your email account, either click the link below or copy and paste it into your web browser.
${url}&action=activate
If you have recieved this email in error, you may either disregard this email or follow the following link to help keep my database clean.
${url}&action=remove

Thanks,

Five Rings Online Administrator
EOQ;

						define('IN_PHPBB', true);
						$phpbb_root_path = './phpBB2/';
						include($phpbb_root_path . 'extension.inc');
						include($phpbb_root_path . 'common.'.$phpEx);
						
						myEmail("admin@fiveringsonline.com", $email, $title, $message);

  
      	
  	        // echo "$email: $title: $message<br>\n";
  	        // mail($email, $title, $message);
  	        //mail ( "jeffv@inav.net", "test", "test");
  	        $confirmation_message = <<<EOQ
An email has been sent to $_POST[email], there will be simple instructions in the email about how to verify your email account.  This is used to help the GMs of the various games on this site, and for future expansions to the site (forums, voting, etc).  You may close this window now.
EOQ;
  	        $page .= buildTextBox( $confirmation_message, "100%", $border, $border_bgcolor, $border_background, $inner_background, $inner_bgcolor );
  					// Echo "Your information verifies and the system will send an email to you with the verification #$verification_number. $sub_dir";
  				}
				}
			}
		}
	}
}

if ($show_form)
{
	$first_name =  (!empty($_POST['first_name'])) ? $_POST['first_name'] : "";
	$last_name =  (!empty($_POST['last_name'])) ? $_POST['last_name'] : "";
	$login_name =  (!empty($_POST['login_name'])) ? $_POST['login_name'] : "";
	$email =  (!empty($_POST['email'])) ? $_POST['email'] : "";
	$year = (!empty($_POST['year'])) ? $_POST['year']+0: "";
	$month = (!empty($_POST['month'])) ? $_POST['month']+0: "";
	$day = (!empty($_POST['day'])) ? $_POST['day']+0: "";

	$form_contents = <<<EOQ
<div class="normal_text">
Please enter all information below, to create a login for yourself on Five Rings Online.</div>
<form name="" method="post" action="$_SERVER[PHP_SELF]">
  $notes
  <table width="100%" border="0" cellspacing="2" cellpadding="2" class="normal_text">
    <tr>
      <td width="40%">Login Name:</td>
      <td width="60%">
        <input type="text" name="login_name" size="40" maxlength="40" value="$login_name">
      </td>
    </tr>
    <tr>
      <td width="40%">First Name:</td>
      <td width="60%">
        <input type="text" name="first_name" size="40" maxlength="40" value="$first_name">
      </td>
    </tr>
    <tr>
      <td width="40%">Last Name:</td>
      <td width="60%">
        <input type="text" name="last_name" size="40" maxlength="50" value="$last_name">
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
  </table>
  <p class="normal_text">By Clicking Submit You are agreeing to the <a href="disclaimer.fro" target="_blank" onClick="open('disclaimer.fro','termsConditions', 'scrollbars,width=600,height=400');return false;" class="highlight">Disclaimer for Five Rings Online</a>.</p>
  <p>
    <input type="submit" name="Submit" value="Submit">
  </p>
</form>
EOQ;

	$page .= buildTextBox( $form_contents, "100%", $border, $border_bgcolor, $border_background, $inner_background, $inner_bgcolor );
}
echo $page;
include 'end_of_page.php';
?>
