<?
$event_id = (isset($_POST['event_id'])) ? $_POST['event_id'] +0 : 0;
$event_id = (isset($_GET['event_id'])) ? $_GET['event_id'] +0 : $event_id;

$page_title = "View Signins for #$event_id";

// page variables
$page = "";
$alert = "";
$js = "";
$form = "";
$show_form = true;
$may_edit = false;
$message = "";
$add_signin = "";
$remove_signin = "";
$remove_any_signin = "";

// get details of event
$event_query = "select * from l5r_events where event_id=$event_id;";
$event_result = mysql_query($event_query) or die(mysql_error());
$event_detail = mysql_fetch_array($event_result, MYSQL_ASSOC);

// test if the viewer can edit who is signed in
if($userdata['is_asst'] || $userdata['is_gm'] || $userdata['is_head'] || $userdata['is_admin'])
{
	// they are a gm
	if($event_detail['Is_Admin_Denied'] == 'Y')
	{
		if ($userdata['is_head'] || $userdata['is_admin'])
		{
			$may_edit = true;
		}
	}
	else
	{
		$may_edit = true;
	}
}
else
{
	// they are a player
	if($userdata['user_id'] == $event_detail['Sponser_ID'])
	{
		$may_edit = true;
	}
}

// test if GET[action] is set
if(isset($_GET['signin_action']) && ($userdata['user_id'] != 1))
{
  // test if they are adding their event
  if($_GET['signin_action'] == 'add_self')
  {
		// check if already at max number of players for event
		$num_of_players_query = "select * from l5r_events_signins where event_id=$event_id;";
		$num_of_players_query = mysql_query($num_of_players_query) or die(mysql_error());
		
		if(($event_detail['Max_Number_Of_Players'] >0) && (mysql_num_rows($num_of_players_query) < $event_detail['Max_Number_Of_Players']))
		{
			// add signin
			$add_self_query = "insert into l5r_events_signins values (null, $userdata[user_id], $event_id, 0);";
	    $add_self_result = mysql_query($add_self_query) or die(mysql_error());
	  }
  }
  // test if they are removing themselves from the event
  if($_GET['signin_action'] == 'remove_self')
  {
	  $remove_self_query = "delete from l5r_events_signins where login_id=$userdata[user_id] and event_id=$event_id;";
	  $remove_self_result = mysql_query($remove_self_query) or die(mysql_error());
  }
}

// test if removing others from the event
if(isset($_POST['signin_action']))
{
	if(($_POST['signin_action'] == 'delete_any') && (!empty($_POST['delete'])))
	{
		$user_list = $_POST['delete'];
		while(list($key, $value) = each($user_list))
		{
			//echo "delete: $key: $value<br>";
			$delete_query = "delete from l5r_events_signins where sign_in_id=$value;";
			$delete_result = mysql_query($delete_query) or die(mysql_error());
		}
	}
}

// test conditions 

// test to see if they can add their signin
$signin_check_query = "select * from l5r_events_signins where event_id=$event_id and login_id=$userdata[user_id];";
$signin_check_result = mysql_query($signin_check_query);

if((mysql_num_rows($signin_check_result) == 0) && ($userdata['user_id'] != 1))
{
	// they are not signed into the event
	$show_signin = true;
	
	// check if already at max number of players for event
	$num_of_players_query = "select * from l5r_events_signins where event_id=$event_id;";
	$num_of_players_query = mysql_query($num_of_players_query) or die(mysql_error());
	
	if(($event_detail['Max_Number_Of_Players'] >0) && (mysql_num_rows($num_of_players_query) >= $event_detail['Max_Number_Of_Players']))
	{
		$show_signin = false;
		$message .= "<br>This event has reached capacity.";
	}
	
	if(($event_detail['Min_Number_Of_Players'] >0) && (mysql_num_rows($num_of_players_query) < $event_detail['Min_Number_Of_Players']))
	{
		$message .= "<br>This event has not reach minimum capacity.";
	}
	
	if($show_signin)
	{
		if($event_detail['Signin_Type'] == 'Login')
		{
		  $add_signin = <<<EOQ
	<a href="$_SERVER[PHP_SELF]?action=event_signins&signin_action=add_self&event_id=$event_id">Signin</a>&nbsp;&nbsp;&nbsp;
EOQ;
	  }
	  else
	  {
		  $add_signin = <<<EOQ
	<a href="$_SERVER[PHP_SELF]?action=event_signins_character&event_id=$event_id" onClick="window.open('$_SERVER[PHP_SELF]?action=event_signins_character&event_id=$event_id', 'signin_character$event_id', 'width=300,height=300,resizable,scrollbars');return false;">Signin</a>&nbsp;&nbsp;&nbsp;
EOQ;
	  }
  }
}
// test if they can remove their signin

if((mysql_num_rows($signin_check_result) >0) && ($userdata['user_id'] != 1))
{
	// they are signed in
	$remove_signin = <<<EOQ
<a href="$_SERVER[PHP_SELF]?action=event_signins&signin_action=remove_self&event_id=$event_id">Remove My Signin</a>&nbsp;&nbsp;&nbsp;
EOQ;
}

// test if they can remove any signin
if($may_edit)
{
  $java_script = <<<EOQ
<script language="JavaScript">
function submitForm ( )
{
	window.document.signin_list.submit();
}
</script>
EOQ;

	$remove_any_signin = <<<EOQ
<a href="#" onClick="submitForm();return false;">Delete Signin(s)</a>
EOQ;
}


// get list of signins for event
$signin_query = "select l5r_events_signins.*, login.Name, wod_characters.Character_Name from (l5r_events_signins LEFT JOIN login ON l5r_events_signins.login_id = login.id) LEFT JOIN wod_characters ON l5r_events_signins.character_id = wod_characters.character_id WHERE event_id = $event_id ORDER BY Character_Name, Name;";
$signin_result = mysql_query($signin_query) or die(mysql_error());

// build list of signins
$form = <<<EOQ
$add_signin
$remove_signin
$remove_any_signin
$message
<form name="signin_list" id="signin_list" action="$_SERVER[PHP_SELF]?action=event_signins" method="post">
<input type="hidden" name="event_id" id="event_id" value="$event_id">
<input type="hidden" name="signin_action" id="action" value="delete_any">
<table border="0" cellpadding="2" cellspacing="2" class="normal_text">
  <tr bgcolor="#000000">
EOQ;

if($may_edit)
{
	$form .= <<<EOQ
	  <td>
	    Remove
	  </td>
EOQ;
}

$form .= <<<EOQ
    <td>
      Login Name
    </td>
EOQ;

if($event_detail['Signin_Type'] == 'Character')
{
	$form .= <<<EOQ
	  <td>
	    Character Name
	  </td>
EOQ;
}

$form .= <<<EOQ
  </tr>
EOQ;

$row = 0;

while($signin_detail = mysql_fetch_array($signin_result, MYSQL_ASSOC))
{
	$row_color = (($row++)%2) ? "#443a33" : "";
	$form .= "<tr bgcolor=\"$row_color\">";
	if($may_edit)
	{
		$form .= <<<EOQ
    <td>
      <input type="checkbox" name="delete[]" id="delete[]" value="$signin_detail[Sign_In_ID]">
    </td>
EOQ;
	}
	
	$form .= <<<EOQ
	  <td>
	    $signin_detail[Name]
	  </td>
EOQ;

  if($event_detail['Signin_Type'] == 'Character')
  {
	  $form .= <<<EOQ
	  <td>
	    $signin_detail[Character_Name]
	  </td>
EOQ;
  }
  $form .= "</tr>";
}

$form .= "</table></form>";

$page_content = <<<EOQ
$alert
$form
EOQ;
?>