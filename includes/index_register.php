<?
// display the registration page
$page_title = "Create Account";
$page_content = <<<EOQ
Welcome to account creation! <br>
<br>
By creating an account you are agreeing the the Terms and Conditions (link later) of the site. Long story short, play nice, and we don't have to break any kneecaps.<br>
<br>
<form name="account_creation" method="post" action="$_SERVER[PHP_SELF]?action=submit_account">
<table border="0" cellpadding="0" cellspacing="2" class="normal_text">
	<tr>
		<td>
			User Name:
		</td>
		<td>
			<input type="text" name="user_name" id="user_name" value="" class="normal_text">
		</td>
	</tr>
	<tr>
		<td>
			Date of Birth (M/D/Y):
		</td>
		<td>
			<input type="text" maxlength="2" size="3" name="month" value="" class="normal_text"> /
			<input type="text" maxlength="2" size="3" name="day" value="" class="normal_text"> /
			<input type="text" maxlength="4" size="4" name="year" value="" class="normal_text">
		</td>
	</tr>
	<tr>
		<td>
			Email Address:
		</td>
		<td>
			<input type="text" name="email" size="40" maxlength="40" value="" class="normal_text">
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
?>