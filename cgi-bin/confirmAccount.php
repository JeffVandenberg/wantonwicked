<?
function confirmAccount($settings)
{
	$validated = true;
	$return = "";
	$notes = "";
	
	if (empty($settings['user_name']))
	{
		$notes .= "<font color=\"red\">Please enter a Login Name.</font>\n";
		$validated = false;
	}
	
	if (empty($settings['password1']) || empty($settings['password2']))
	{
		$notes .= "<font color=\"red\">Please enter both Passwords for authentication purposes.</font>\n";
		$validated = false;
	}
	
	if ($settings['password1'] != $settings['password2'] )
	{
		$notes .= "<font color=\"red\">Please enter both password for authentication purposes.</font>\n";
		$validated = false;
	}
	
	if (empty($settings['email']))
	{
		$notes .= "<font color=\"red\">Please enter your Email Address.</font>\n";
		$validated = false;
	}
	
	if (empty($settings['month']))
	{
		$notes .= "<font color=\"red\">Please enter your Birth Month.</font>\n";
		$validated = false;
	}
	
	if (empty($settings['day']))
	{
		$notes .= "<font color=\"red\">Please enter your Birth Day.</font>\n";
		$validated = false;
	}
	
	if (empty($settings['year']))
	{
		$notes .= "<font color=\"red\">Please enter your Birth Year.</font>\n";
		$validated = false;
	}
	
	if (!eregi("^[a-z0-9]+([_.-][a-z0-9]+)*@([a-z0-9]+([.-][a-z0-9]+)*)+\\.[a-z]{2,4}$", $settings['email']) )
	{
		$notes .= "<font color=\"red\">Please enter a valid email.</font>\n";
		$validated = false;
	}
	
	$date_check = verifyDate ($settings['year'], $settings['month'], $settings['day']);

	if (!$date_check['verified'])
	{
  	// the date that they put in doesn't check
  	$notes .= $date_check['message'];
  	$validated = false;
	}
  	
 	$name = addslashes($settings['user_name']);
  $login_check_query = "select * from login where name='$name';";
  $login_check_result = mysql_query($login_check_query) or die(mysql_error());
  
  if(mysql_num_rows($login_check_result))
	{
		$notes .= "<font color=\"red\">Select a different login name.</font>\n";
		$validated = false;
	}
	
	if($validated)
	{
		$return['is_valid'] = $validated;
		$return['user_name'] = $settings['user_name'];
		$return['password'] = $settings['password1'];
		$return['email_address'] = $settings['email_address'];
		$return['birth_date'] = $date_check['date'];
	}
	else
	{
		// put together return value for invalid account
		$return['is_valid'] = $validated;
		$return['notes'] = $notes;
		$return['user_name'] = $settings['user_name'];
		$return['password'] = $settings['password1'];
		$return['email_address'] = $settings['email_address'];
		$return['birth_date'] = $date_check['date'];
		
	}
	
	return $return;
}
?>