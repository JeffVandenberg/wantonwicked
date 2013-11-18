<?
// display login page
$page_title = "Login";
$menu_bar = "Links";
$user_name = "";
$show_form = true;
$alert = "";

if(isset($_POST['login']))
{
	//echo "validating login";
	
	$temp_user_name = (isset($_POST['user_name'])) ? $_POST['user_name'] : "";
	$temp_password = (isset($_POST['password'])) ? md5($_POST['password']) : "";
	
	//echo "trying to login in<br>";
	$validated = doLogin($temp_user_name, $temp_password);
	
	if($validated)
	{
		$show_form = false;
		//die("you're validated<br>");
	}
	else
	{
		$alert = <<<EOQ
That User Name and Password are invalid.<br>
<a href="$_SERVER[PHP_SELF]?action=pw_reset&goto=$_GET[goto]">Request a new password.</a><br>
EOQ;
	}

}

if($show_form)
{
	$page_content = <<<EOQ
Login to Wanton Wicked:<br>
$alert
<form name="login" method="post" action="$_SERVER[PHP_SELF]?action=login&goto=$_GET[goto]">
	<table border="0" cellpadding="0" cellspacing="2" class="normal_text">
		<tr>
			<td>
			User Name:
			</td>
			<td>
				<input type="text" name="user_name" id="user_name" value="$user_name" width="20" maxlength="40">
			</td>
		</tr>
		<tr>
			<td>
				Password:
			</td>
			<td>
				<input type="password" name="password" id="password" value="" width="20" maxlength="40">
			</td>
		</tr>
		<tr>
			<td>
				Remember Login?
			</td>
			<td>
				<input type="checkbox" name="autologin" value="y">
			</td>
		</tr>
		<tr>
			<td colspan="2" align="center">
				<input type="submit" name="login" id="login" value="Log In">
			</td>
		</tr>
	</table>
</form>
EOQ;
}
else
{
	$goto = str_replace("|", "&", $_GET['goto']);
	
	$java_script = <<<EOQ
<script language="JavaScript" version="1.2">
	window.document.location.href="$goto";
</script>
EOQ;

	$page_content = <<<EOQ
You have been logged into WantonWicked.net. Enjoy your stay. <a href="$_SERVER[PHP_SELF]">Return to WantonWicked.net.</a>
EOQ;
}
?>