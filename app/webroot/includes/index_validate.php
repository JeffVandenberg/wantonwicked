<?
$page_title = "Verify Account";
$menu_bar = "Links";

// validate the account
if(!empty($_GET['account_name']) && !empty($_GET['validation_number'] ) && !empty($_GET['account']) && !empty($_GET['id']) )
{
	//pull informtion out of url
	$name = $_GET['account_name'];
	$valid = $_GET['validation_number'];
	$account = $_GET['account'];
	$id = $_GET['id'];

	if ($account== "activate")
	{
		$confirm_query = "select * from login where ID=$id and confirmation_number='$valid';";
		$confirm_result = mysql_query($confirm_query) || die(mysql_error());

		if (mysql_num_rows($confirm_result))
		{
			$confirm_details = mysql_fetch_array($confirm_result, MYSQL_ASSOC);
			$page_content = <<<EOQ
Your account has been activated. You may login your account now. <a href="$_SERVER[PHP_SELF]">Continue to WantonWicked.net.</a>
EOQ;
			$query = "update login set is_confirmed='y', confirmation_number=0 where ID=$id;";
			$result = mysql_query($query) || die(mysql_error());
		}
		else
		{
			$page_content = "Your account was not found.  If you followed this from a link in your email, you may have already activated or deleted your account.<br><a href=\"$_SERVER[PHP_SELF]\">Click here to return to WantonWicked.net.</a>";
		}
	}
	
	if ($account == "remove")
	{
    $page_content = "Your Account is being removed.  Sorry for any inconvience.";
  }
	
}
else
{
	$page_content = "Invalid Request for account validation. Please click the link in your email. <a href=\"$_SERVER[PHP_SELF]\">Click here to return to Wanton Wicked.</a>";
}	
?>