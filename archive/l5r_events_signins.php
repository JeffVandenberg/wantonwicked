<?
$event_id = 0;
$event_id = (isset($_POST['event_id'])) ? $_POST['event_id'] +0 : $event_id;
$event_id = (isset($_GET['event_id'])) ? $_GET['event_id'] +0 : $event_id;

$page_title = "View Signins for #$event_id";
$add_bars = false;
include 'start_of_page.php';

// page variables
$page = "";
$alert = "";
$js = "";
$form = "";
$site_id = 1000;
$site_id = (isset($_POST['site_id'])) ? $_POST['site_id'] : $site_id;
$site_id = (isset($_GET['site_id'])) ? $_GET['site_id'] : $site_id;
$show_form = true;
$may_edit = false;
$message = "";
$add_signin = "";
$remove_signin = "";
$remove_any_signin = "";

// get details of event
$event_query = "select * from l5r_events where event_id=$event_id;";
$event_result = $mysqli->query($event_query);
$event_detail = $event_result->fetch_array(MYSQLI_ASSOC);

// test if the viewer can edit who is signed in
if($_SESSION['site_id'] == $site_id)
{
	// they are a gm
	if($event_detail['Is_Admin_Denied'] == 'Y')
	{
		if ($_SESSION['is_head'] || $_SESSION['is_admin'])
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
	if($_SESSION['user_id'] == $event_detail['Sponser_ID'])
	{
		$may_edit = true;
	}
}

// test if GET[action] is set
if(isset($_GET['action']) && $_SESSION['user_id'])
{
  // test if they are adding their event
  if($_GET['action'] == 'add_self')
  {
		// check if already at max number of players for event
		$num_of_players_query = "select * from l5r_events_signins where event_id=$event_id;";
		$num_of_players_query = $mysqli->query($num_of_players_query);
		
		if(($event_detail['Max_Number_Of_Players'] >0) && ($num_of_players_query->num_rows >= $event_detail['Max_Number_Of_Players']))
		{
			// do nothing
		}
		else
		{
			// check if signed into more events than one can in the event group
			$signins_in_group_query = "SELECT l5r_events_signins.* FROM l5r_events LEFT JOIN l5r_events_signins ON l5r_events.event_id = l5r_events_signins.event_id WHERE l5r_events.group_name='$event_detail[Group_Name]' AND l5r_events_signins.login_id = $_SESSION[user_id] and l5r_events.group_name != '';";
			$signins_in_group_result = $mysqli->query($signins_in_group_query);
			
			if($event_detail['Group_Signins'] >0 && ($signins_in_group_result->num_rows >= $event_detail['Group_Signins']))
			{
				// do nothing
			}
			else
			{
				$add_self_query = "insert into l5r_events_signins values (null, $_SESSION[user_id], $event_id, 0);";
		    $add_self_result = $mysqli->query($add_self_query);
		  }
	  }
  }
  // test if they are removing themselves from the event
  if($_GET['action'] == 'remove_self')
  {
	  $remove_self_query = "delete from l5r_events_signins where login_id=$_SESSION[user_id] and event_id=$event_id;";
	  $remove_self_result = $mysqli->query($remove_self_query) or die($mysqli->query());
  }
}

// test if removing others from the event
if(isset($_POST['action']))
{
	if($_POST['action'] == 'delete_any')
	{
		$user_list = $_POST['delete'];
		while(list($key, $value) = each($user_list))
		{
			//echo "delete: $key: $value<br>";
			$delete_query = "delete from l5r_events_signins where sign_in_id=$value;";
			$delete_result = $mysqli->query($delete_query);
		}
	}
}

// test conditions 

// test to see if they can add their signin
$signin_check_query = "select * from l5r_events_signins where event_id=$event_id and login_id=$_SESSION[user_id];";
$signin_check_result = $mysqli->query($signin_check_query);

if(($signin_check_result->num_rows ==0)&& $_SESSION['user_id'])
{
	// they are not signed into the event
	$show_signin = true;
	
	// check if already at max number of players for event
	$num_of_players_query = "select * from l5r_events_signins where event_id=$event_id;";
	$num_of_players_query = $mysqli->query($num_of_players_query);
	
	if(($event_detail['Max_Number_Of_Players'] >0) && ($num_of_players_query->num_rows >= $event_detail['Max_Number_Of_Players']))
	{
		$show_signin = false;
		$message .= "<br>This event has reached capacity.";
	}
	
	if(($event_detail['Min_Number_Of_Players'] >0) && ($num_of_players_query->num_rows < $event_detail['Min_Number_Of_Players']))
	{
		$message .= "<br>This event has not reach minimum capacity.";
	}
	
	// check if signed into more events than one can in the event group
	$signins_in_group_query = "SELECT l5r_events_signins.* FROM l5r_events LEFT JOIN l5r_events_signins ON l5r_events.event_id = l5r_events_signins.event_id WHERE l5r_events.group_name='$event_detail[Group_Name]' AND l5r_events_signins.login_id = $_SESSION[user_id];";
	$signins_in_group_result = $mysqli->query($signins_in_group_query);
	
	if($event_detail['Group_Signins'] >0 && ($signins_in_group_result->num_rows >= $event_detail['Group_Signins']))
	{
		$show_signin = false;
		$message = "<br>You have joined the maximum number of events you may in this group.";
	}
	
	if($show_signin)
	{
		if($event_detail['Signin_Type'] == 'Login')
		{
		  $add_signin = <<<EOQ
	<a href="$_SERVER[PHP_SELF]?action=add_self&site_id=$site_id&event_id=$event_id">Signin</a>&nbsp;&nbsp;&nbsp;
EOQ;
	  }
	  else
	  {
		  $add_signin = <<<EOQ
	<a href="l5r_events_signins_character.fro?site_id=$site_id&event_id=$event_id" onClick="window.open('l5r_events_signins_character.fro?site_id=$site_id&event_id=$event_id', 'signin_character$event_id', 'width=300,height=300,resizable,scrollbars');return false;">Signin</a>&nbsp;&nbsp;&nbsp;
EOQ;
	  }
  }
}
// test if they can remove their signin

if(($signin_check_result->num_rows >0)&& $_SESSION['user_id'])
{
	// they are signed in
	$remove_signin = <<<EOQ
<a href="$_SERVER[PHP_SELF]?action=remove_self&site_id=$site_id&event_id=$event_id">Remove My Signin</a>&nbsp;&nbsp;&nbsp;
EOQ;
}

// test if they can remove any signin
if($may_edit)
{
  $js = <<<EOQ
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
$signin_query = "select l5r_events_signins.*, login.Name, l5rcharacter.Character_Name from (l5r_events_signins LEFT JOIN login ON l5r_events_signins.login_id = login.id) LEFT JOIN l5rcharacter ON l5r_events_signins.character_id = l5rcharacter.character_id WHERE event_id = $event_id ORDER BY Character_Name, Name;";
$signin_result = $mysqli->query($signin_query);

// build list of signins
$form = <<<EOQ
$add_signin
$remove_signin
$remove_any_signin
$message
<form name="signin_list" id="signin_list" action="$_SERVER[PHP_SELF]" method="post">
<input type="hidden" name="event_id" id="event_id" value="$event_id">
<input type="hidden" name="site_id" id="site_id" value="$site_id">
<input type="hidden" name="action" id="action" value="delete_any">
<table border="0" cellpadding="2" cellspacing="2" class="normal_text">
  <tr bgcolor="$info_table_header_color">
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

while($signin_detail = $signin_result->fetch_array(MYSQLI_ASSOC))
{
	$row_color = (($row++)%2) ? $info_table_row_color : "";
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
$form = buildTextBox( $form, "", $border, $border_bgcolor, $border_background, $inner_background, $inner_bgcolor );

$page = <<<EOQ
$js
$alert
$form
EOQ;

echo $page;

include 'end_of_page.php';
?>