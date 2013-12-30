<?
// variables required for includes
$page_title="FRO Verify Account";
$add_bars = false;
include 'start_of_page.php';

// make start of page

// script variables
$page = "";
$message = "";
$transaction_mode = "debug";

// test if proper information is provided in the URL
if(!empty($_GET['account_name']) && !empty($_GET['validation_number'] ) && !empty($_GET['action']) && !empty($_GET['id']) )
{
	include 'dbconnect.php';

	//pull informtion out of url
	$name = $_GET['account_name'];
	$valid = $_GET['validation_number'];
	$action = $_GET['action'];
	$id = $_GET['id'];

	if ($action == "activate")
	{
		$confirm_query = "select * from login where ID=$id and confirmation_number='$valid';";
		$confirm_result = $mysqli->query($confirm_query);

		if ($confirm_result->num_rows)
		{
			$confirm_details = $confirm_result->fetch_array(MYSQLI_ASSOC);
			$message = <<<EOQ
Your account has been activated. You may login your account now. <a href="index.html" class="highlight">Login to Five Rings Online.Com</a>
EOQ;
			$query = "update login set is_confirmed='y', confirmation_number=0 where ID=$id;";
			$result = $mysqli->query($query);
		}
		else
		{
			$message = "Your account was not found.  If you followed this from a link in your email, you may have already activated or deleted your account.<br>";
		}
	}

	if ($action == "remove")
	{
    $message = "Your Account is being removed.  Sorry for any inconvience.";

    /*transactionBegin($mysqli);

    $login_delete_query = "delete from login where id=$id;";
    $login_delete_result = $mysqli->query($login_delete_query) or transactionRollback($mysqli, $transaction_mode);

    $permissions_delete_query = "delete from permissions where id='$id_details[ID]';";
    $permissions_delete_result = $mysqli->query($permissions_delete_query) or transactionRollback($mysqli, $transaction_mode);

    transactionCommit($mysqli);
    */
	}
}
else
{
	$message = <<<EOQ
Please follow a link from your email.  There are specific criteria that are looked for. Thank you. <br>
<br>
EOQ;
}
  $page .= buildTextBox( $message, "100%", $border, $border_bgcolor, $border_background, $inner_background, $inner_bgcolor );
echo "$page";

include 'end_of_page.php';
?>