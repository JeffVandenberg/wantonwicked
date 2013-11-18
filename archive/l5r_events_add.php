<?
$page_title = "Add Event";
$add_left = false;
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

// form variables
$event_name = "";
$event_short_desc = "";
$event_long_desc = "";
$event_prize = "";
$event_date = date ('Y-m-d H:i', mktime (0, 0, 0, date('m'), date('d')+3, date('Y')));
$expected_length = 4;
$min_players = 0;
$max_players = 0;
$group_name = "";
$group_signins = 0;
$signin_type = "Login";

// check if submitting an event
if(!empty($_POST['event_name']))
{
	// grab values
	$event_name = htmlspecialchars($_POST['event_name']);
	$event_short_desc = (!empty($_POST['event_short_desc'])) ? htmlspecialchars($_POST['event_short_desc']) : "";
	$event_long_desc = (!empty($_POST['event_long_desc'])) ? str_replace("\n", "<br>", htmlspecialchars($_POST['event_long_desc'])) : "";
	$event_prize = (!empty($_POST['event_prize'])) ? htmlspecialchars($_POST['event_prize']) : "";
	$event_date = htmlspecialchars($_POST['event_date']);
	$expected_length = $_POST['expected_length'] +0;
	$min_players = (!empty($_POST['min_players'])) ? $_POST['min_players'] +0 : 0;
	$max_players = (!empty($_POST['max_players'])) ? $_POST['max_players'] +0 : 0;
	$group_name = (!empty($_POST['group_name'])) ? htmlspecialchars($_POST['group_name']) : "";
	$group_signins = (!empty($_POST['group_signins'])) ? $_POST['group_signins'] +0 : 0;
	$signin_type = $_POST['signin_type'];
	$validated = true;
	
	// validate
	// make sure min < max
	if($min_players > $max_players  && ($max_players != 0))
	{
		$temp_var = $min_players;
		$min_players = $max_players;
		$max_players = $temp_var;
	}
	// validate date 
	$event_date = str_replace("/", "-", $event_date);

	$y_m_d_parts = explode("-", $event_date);
	if(sizeof($y_m_d_parts) == 3)
	{
		// appropriate Year, Month, Day parts
		$year = $y_m_d_parts["0"];
		$month = $y_m_d_parts["1"];
		$remainder = $y_m_d_parts["2"];
		
		// seperate day from hours
		$remainder = explode(" ", $remainder);
		if(sizeof($remainder) == 2)
		{
			// only one space between day and Hour
			$day = $remainder["0"];
			$time_left = $remainder["1"];
			
			$h_m_parts = explode(":", $time_left);
			if(sizeof($h_m_parts) == 2)
			{
				$hour = $h_m_parts["0"];
				$minute = $h_m_parts["1"];
				
				if($hour >24 || $hour<0)
				{
					$validated = false;
					$alert .= "<span class=\"red_highlight\">Please put in a valid Hour</span><br>";
				}
				if($minute >60 || $minute<0)
				{
					$validated = false;
					$alert .= "<span class=\"red_highlight\">Please put in a valid Minutes</span><br>";
				}
				
				$valid_date = verifyDate($year, $month, $day);
				if(!$valid_date['verified'])
				{
					$validated = false;
					$alert .= $valid_date['message'];
				}
				else
				{
					$event_date = $valid_date['date'] . " $time_left";
				}
			}
			else
			{
				// put in a valid time (in 24 hour format)
				$validated = false;
				$alert .= "<span class=\"red_highlight\">Please put in a valid Time Format</span><br>";
			}
		}
		else
		{
			// put a space between day and hour
			$validated = false;
			$alert .= "<span class=\"red_highlight\">Please Have exactly 1 space between day and the Hour</span><br>";
		}
	}
	else
	{
		// put in year, month, day
		$validated = false;
		$alert .= "<span class=\"red_highlight\">Please put in a valid Year-Month-Day Format</span><br>";
	}
	
	// if valid put into db
	if($validated && $_SESSION['user_id'])
	{
		$lock_query = "lock tables l5r_events write;";
		$lock_result = $mysqli->query($lock_query) or die(mysql_error);
	
		$event_id = getNextID($mysqli, "l5r_events", "event_ID");
		$now = date('Y-m-d H:i:s');
		
		$insert_query = "insert into l5r_events values ($event_id, '$event_name', $site_id, '$event_date', $expected_length, '$event_short_desc', '$event_long_desc', '$event_prize', $_SESSION[user_id], $min_players, $max_players, 'N', 'N', '$group_name', $group_signins, '$signin_type', '$now');";
		$insert_result = $mysqli->query($insert_query);
		
		$unlock_query = "unlock tables;";
		$unlock_result = $mysqli->query($unlock_query);
    $show_form = false;
    $js = <<<EOQ
<script language="JavaScript">
  window.location.href="l5r_events_list.fro?site_id=$site_id";
</script>
EOQ;

    // send mail to player and GM list to notify that the event has been added 
    $player_list_email = "fiveringsplayers@yahoogroups.com";
    $gm_list_email = "fiveringsgamemasters@yahoogroups.com";
    
    $message_title = stripslashes("New Event: $event_name");
    
    $email_formatted_body = strip_tags(stripslashes($_POST['event_long_desc']));
    
    $message_body = <<<EOQ
$_SESSION[user_name] has just posted a new event.

Title: $event_name
Date & Time: $event_date
Expected Length: $expected_length hours

Description: $email_formatted_body

View full details at http://www.fiveringsonline.com/l5r_events_view.fro?event_id=$event_id
EOQ;

    mail($player_list_email, $message_title, $message_body);
    mail($gm_list_email, $message_title, $message_body);
	}
	else
	{
		$event_name = stripslashes($event_name);
		$event_short_desc = stripslashes($event_short_desc);
		$event_long_desc = stripslashes($event_long_desc);
		$event_prize = stripslashes($event_prize);
		$group_name = stripslashes($group_name);
	}
}

// build form & js
if($show_form)
{
	// build js
	$js = <<<EOQ
<script language="JavaScript">
function submitForm ( )
{
	var fields = "";
	
	if(window.document.event_form.event_name.value == "")
	{
		fields = fields + "Event Name, ";
	}
	
	if(window.document.event_form.event_date.value == "")
	{
		fields = fields + "Event Date, ";
	}
	
	if(window.document.event_form.expected_length.value == "")
	{
		fields = fields + "Expected Length, ";
	}
	
	// test if validated
	if(fields == "")
	{
		window.document.event_form.submit();
	}
	else
	{
		fields = fields.substring(0, fields.length-2);
		alert("Please enter the following fields : " + fields + " then click Submit");
	}
}
</script>
EOQ;
  $signin_types = array('Login', 'Character');
  $signin_select = buildSelect($signin_type, $signin_types, $signin_types, "signin_type");
	// build form
	$form = <<<EOQ
<form name="event_form" id="event_form" method="post" action="$_SERVER[PHP_SELF]">
<table border="0" cellpadding="2" cellspacing="2" class="normal_text">
  <tr valign="top">
    <td>
      <span class="highlight">* Event Name:</span>
    </td>
    <td>
      <input type="text" name="event_name" id="event_name" size="50" maxlength="100" value="$event_name">
    </td>
    <td>
      <span class="highlight">* Event Date/Time:</span><br>
      Format: YYYY-MM-DD HH:MM
    </td>
    <td>
      <input type="text" name="event_date" id="event_date" size="20" maxlength="20" value="$event_date">
    </td>
  </tr>
  <tr valign="top">
    <td>
      <span class="highlight">Short Description:</span><br>
      Max 255 Characters
    </td>
    <td>
      <input type="text" name="event_short_desc" id="event_short_desc" size="50" maxlength="255" value="$event_short_desc">
    </td>
    <td>
      <span class="highlight">* Expected Duration:</span><br>
    </td>
    <td>
      <input type="text" name="expected_length" id="expected_length" size="3" maxlength="3" value="$expected_length"> Hours
    </td>
  </tr>
  <tr valign="top">
    <td>
      <span class="highlight">Long Description:</span>
    </td>
    <td>
      <textarea name="event_long_desc" id="event_long_desc" rows="6" cols="37" wrap="physical">$event_long_desc</textarea>
    </td>
    <td>
      <span class="highlight">Group Name:</span><br>
      To Associate a group of<br>
      events with each other give<br>
      them all the EXACT same<br>
      group name.
    </td>
    <td>
      <input type="text" name="group_name" id="group_name" size="20" maxlength="100" value="$group_name">
    </td>
  </tr>
  <tr valign="top">
    <td>
      <span class="highlight">Event Prize:</span><br>
      If any
    </td>
    <td>
      <input type="text" name="event_prize" id="event_prize" size="50" maxlength="255" value="$event_prize">
    </td>
    <td>
      <span class="highlight">Group Signins<br>per Player:</span><br>
      How many events in the<br>
      group may a Player sign-up<br>
      for? If 0, it will be ignored
    </td>
    <td>
      <input type="text" name="group_signins" id="group_signins" size="3" maxlength="3" value="$group_signins">
    </td>
  </tr>
  <tr valign="top">
    <td>
      <span class="highlight">Min/Max Number<br>of Players:</span><br>
      Optional.
    </td>
    <td>Min: <input type="text" name="min_players" id="min_players" size="3" maxlength="3" value="$min_players">
        Max: <input type="text" name="max_players" id="max_players" size="3" maxlength="3" value="$max_players">
    </td>
    <td>
      <span class="highlight">Signin Type:</span><br>
      Login, just notes Name<br>
      Character, will also ask<br>
      for a Characte for scene.
    </td>
    <td>
      $signin_select
    </td>
  </tr>
  <tr>
    <td colspan="4">
      <input type="submit" value="Add Event" onClick="submitForm();return false;">
    </td>
  </tr>
</table>
</form>
EOQ;
  $form = buildTextBox( $form, "", $border, $border_bgcolor, $border_background, $inner_background, $inner_bgcolor );
}

// wrap alert if it's not empty
if($alert != "")
{
	$alert = buildTextBox( $alert, "", $border, $border_bgcolor, $border_background, $inner_background, $inner_bgcolor );
}

// build page and output
$page = <<<EOQ
$alert
$js
$form
EOQ;

echo $page;
include 'end_of_page.php'
?>