<?
$page_title = "Add Wanton Wicked Event";

// page variables
$page = "";
$alert = "";
$js = "";
$form = "";
$show_form = true;

// form variables
$event_name = "";
$event_short_desc = "";
$event_long_desc = "";
$event_prize = "";
$event_date = date ('Y-m-d H:i', mktime (0, 0, 0, date('m'), date('d')+7, date('Y')));
$expected_length = 4;
$min_players = 0;
$max_players = 0;
$group_name = "";
$group_signins = 0;
$signin_type = "Login";
$site_id = 1004;

// check if submitting an event
if(!empty($_POST['event_name']))
{
	// grab values
	$event_name = htmlspecialchars($_POST['event_name']);
	$event_short_desc = (!empty($_POST['event_short_desc'])) ? htmlspecialchars($_POST['event_short_desc']) : "";
	$event_long_desc = (!empty($_POST['event_long_desc'])) ? str_replace("\n", "<br>", htmlspecialchars($_POST['event_long_desc'])) : "";
	$event_date = htmlspecialchars($_POST['event_date']);
	$expected_length = $_POST['expected_length'] +0;
	$min_players = (!empty($_POST['min_players'])) ? $_POST['min_players'] +0 : 0;
	$max_players = (!empty($_POST['max_players'])) ? $_POST['max_players'] +0 : 0;
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
					$alert .= "<span class=\"highlight\">Please put in a valid Hour</span><br>";
				}
				if($minute >60 || $minute<0)
				{
					$validated = false;
					$alert .= "<span class=\"highlight\">Please put in a valid Minutes</span><br>";
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
				$alert .= "<span class=\"highlight\">Please put in a valid Time Format</span><br>";
			}
		}
		else
		{
			// put a space between day and hour
			$validated = false;
			$alert .= "<span class=\"highlight\">Please Have exactly 1 space between day and the Hour</span><br>";
		}
	}
	else
	{
		// put in year, month, day
		$validated = false;
		$alert .= "<span class=\"highlight\">Please put in a valid Year-Month-Day Format</span><br>";
	}
	
	// if valid put into db
	if($validated && ($userdata['user_id'] != 1))
	{
		$lock_query = "lock tables l5r_events write;";
		$lock_result = mysql_query($lock_query) or die(mysql_error());
	
		$event_id = getNextID($connection, "l5r_events", "event_ID");
		$now = date('Y-m-d H:i:s');
		
		$insert_query = "insert into l5r_events values ($event_id, '$event_name', $site_id, '$event_date', $expected_length, '$event_short_desc', '$event_long_desc', '$event_prize', $userdata[user_id], $min_players, $max_players, 'N', 'N', '$group_name', $group_signins, '$signin_type', '$now');";
		//echo "$insert_query<br>";
		$insert_result = mysql_query($insert_query) or die(mysql_error());
		
		$unlock_query = "unlock tables;";
		$unlock_result = mysql_query($unlock_query) or die(mysql_error());
    $show_form = false;
    $java_script = <<<EOQ
<script language="JavaScript">
  window.location.href="$_SERVER[PHP_SELF]?action=event_list";
</script>
EOQ;

    // Create post on the player forum for the event
    $message = <<<EOQ
$userdata[user_name] has just posted a new event.

Title: $event_name
Date & Time: $event_date
Expected Length: $expected_length hours

Description: $event_short_desc

View full details at http://www.wantonwicked.net$_SERVER[PHP_SELF]?action=event_view&event_id=$event_id
EOQ;

		$message = $message;
		include($phpbb_root_path . 'includes/bbcode.'.$phpEx);
		include($phpbb_root_path . 'includes/functions_post.'.$phpEx);

		$mode = "newtopic";
		$username = "JeffV";
		$subject = "New Event: $event_name";
		$poll_title = "";
		$poll_options = "";
		$poll_length = "";
		$bbcode_uid = '';
		$bbcode_on = 1;
		$smilies_on = 1;
		$html_on = true;
		$post_data = array();
  	$post_data['first_post'] = true;
  	$post_data['last_post'] = false;
  	$post_data['has_poll'] = false;
  	$post_data['edit_poll'] = false;
		
		$error_msg = "";
		$return_message = "";
		$return_meta = "";
		$forum_id = 2;
		$topic_id = 0;
		$post_id = 0;
		$topic_type = POST_NORMAL;
		$attach_sig = 0;
		$temp_id = $userdata['user_id'];
		$temp_name = $userdata['username'];
		
		$userdata['user_id'] = 8;
		$userdata['username'] = "JeffV";

		prepare_post($mode, $post_data, $bbcode_on, $html_on, $smilies_on, $error_msg, $username, $bbcode_uid, $subject, $message, $poll_title, $poll_options, $poll_length);
		
		if ( $error_msg == '' )
		{
			submit_post($mode, $post_data, $return_message, $return_meta, $forum_id, $topic_id, $post_id, $poll_id, $topic_type, $bbcode_on, $html_on, $smilies_on, $attach_sig, $bbcode_uid, str_replace("\'", "''", $username), str_replace("\'", "''", $subject), str_replace("\'", "''", $message), str_replace("\'", "''", $poll_title), $poll_options, $poll_length);
			
			update_post_stats($mode, $post_data, $forum_id, $topic_id, $post_id, $userdata['user_id']);
		}
		// restore them back to original values
		$userdata['user_id'] = $temp_id;
		$userdata['username'] = $temp_name;
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
	$java_script = <<<EOQ
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
<form name="event_form" id="event_form" method="post" action="$_SERVER[PHP_SELF]?action=event_add">
<table border="0" cellpadding="2" cellspacing="2" class="normal_text">
  <tr valign="top">
    <td colspan="2">
      <span class="highlight">* Event Name:</span><br>
      <input type="text" name="event_name" id="event_name" size="30" maxlength="100" value="$event_name">
    </td>
  </tr>
  <tr>
    <td colspan="2" height="5">
    </td>
  </tr>
  <tr valign="top">
    <td>
      <span class="highlight">* Event Date/Time:</span> Format: YYYY-MM-DD HH:MM<br>
      <input type="text" name="event_date" id="event_date" size="20" maxlength="20" value="$event_date">
    </td>
    <td>
      <span class="highlight">* Expected Duration:</span><br>
      <input type="text" name="expected_length" id="expected_length" size="3" maxlength="3" value="$expected_length"> Hours
    </td>
  </tr>
  <tr>
    <td colspan="2" height="5">
    </td>
  </tr>
  <tr valign="top">
    <td>
      <span class="highlight">Min/Max Number of Players:</span><br>
      Optional.<br>
      Min: <input type="text" name="min_players" id="min_players" size="3" maxlength="3" value="$min_players"> 
      Max: <input type="text" name="max_players" id="max_players" size="3" maxlength="3" value="$max_players">
    </td>
    <td>
      <span class="highlight">Signin Type:</span><br>
      Login: records their login name<br>
      Character: will ask for a Character for the scene.<br>
      $signin_select
    </td>
  </tr>
  <tr>
    <td colspan="2" height="5">
    </td>
  </tr>
  <tr valign="top">
    <td>
      <span class="highlight">Short Description:</span><br>
      Max 255 Characters<br>
      <input type="text" name="event_short_desc" id="event_short_desc" size="50" maxlength="255" value="$event_short_desc">
    </td>
    <td>
    </td>
  </tr>
  <tr valign="top">
    <td colspan="2">
      <span class="highlight">Long Description:</span><br>
      <textarea name="event_long_desc" id="event_long_desc" rows="6" cols="50" wrap="physical">$event_long_desc</textarea>
    </td>
  </tr>
  <tr>
    <td colspan="2">
      <input type="submit" value="Add Event" onClick="submitForm();return false;">
    </td>
  </tr>
</table>
</form>
EOQ;
}


// build page and output
$page_content = <<<EOQ
$alert
$form
EOQ;

?>