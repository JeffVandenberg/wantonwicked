<?php
	$character_query = "select * from wod_characters where character_name = '$_GET[username]';";
  $character_result = mysql_query($character_query) or die(mysql_error());
  $character_detail = mysql_fetch_array($character_result, MYSQL_ASSOC);
	$mayLogin = 0;
	
	if($character_detail)
	{
		$mayLogin = 1;
		if($character_detail['Is_Sanctioned'] == 'Y')
		{
			$group = 11;
		}
		
		if($character_detail['Is_Sanctioned'] == '')
		{
			// ship to the unsanctioned group
			$group = 9;
		}
		
		if($character_detail['City'] == 'Side Game')
		{
	  	$group = 19;
		}
	}

	//header("Content-type: text/plain");
	/*$page_content = <<<EOQ
scras.version = 2.1
user.usergroup.id = 0
user.uid = 1
user.usergroup.can_login = 1
user.usergroup.can_msg = 1
user.usergroup.idle_kick = 1
user.usergroup.allow_pm = 1
EOQ;*/
    /* This script will allow any user to log-in using RAS v2.1 */

    $user_name     = $_REQUEST['username'];
    $user_ip       = $_REQUEST['ip'];
    $user_password = $_REQUEST['password'];

    header("Content-type: text/plain");
    
    /* Required: 
        1. Specify the version of RAS that you're using. 
        2. Set user.usergroup.id to 0 to have the chat server 
           automatically assign a unique user group ID.
    */
    print "scras.version = 2.1\n";
    print "user.usergroup.id = 0\n";

    /* Recommended: Specify a unique ID number for this user. */
    print "user.uid = 243\n";

    /* Here we specify the permissions for this user / user group */
    print "user.usergroup.can_login = 1\n";
    print "user.usergroup.allow_pm = 1\n";

?>
