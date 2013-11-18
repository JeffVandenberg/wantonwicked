<?
$event_id = (isset($_POST['event_id'])) ? $_POST['event_id'] +0 : 0;
$event_id = (isset($_GET['event_id'])) ? $_GET['event_id'] +0 : $event_id;

// page variables
$page = "";
$alert = "";
$js = "";
$form = "";
$show_form = true;
$may_edit = false;

// test if updating
if(!empty($_POST['event_name']))
{
	// get event details real quick
	$event_query = "select * from l5r_events where event_id = $event_id;";
	$event_result = mysql_query($event_query) or die(mysql_error());
	$event_detail = mysql_fetch_array($event_result, MYSQL_ASSOC);
	
	// grab values
	$event_name = htmlspecialchars($_POST['event_name']);
	$event_short_desc = (!empty($_POST['event_short_desc'])) ? htmlspecialchars($_POST['event_short_desc']) : "";
	$event_long_desc = (!empty($_POST['event_long_desc'])) ? str_replace("\n", "<br>", htmlspecialchars($_POST['event_long_desc'])) : "";
	$event_date = htmlspecialchars($_POST['event_date']);
	$expected_length = $_POST['expected_length'] +0;
	$min_players = (!empty($_POST['min_players'])) ? $_POST['min_players'] +0 : 0;
	$max_players = (!empty($_POST['max_players'])) ? $_POST['max_players'] +0 : 0;
	$signin_type = $_POST['signin_type'];
	$is_denied = (isset($_POST['is_denied'])) ? $_POST['is_denied'] : $event_detail['Is_Denied'];
	$is_admin_denied = (isset($_POST['is_admin_denied'])) ? $_POST['is_admin_denied'] : $event_detail['Is_Admin_Denied'];
	
	$validated = true;
	
	// validate
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
	if($validated)
	{
		$update_query = "update l5r_events set event_name='$event_name', event_date='$event_date', expected_length=$expected_length, event_short_description='$event_short_desc', event_long_description='$event_long_desc', min_number_of_players=$min_players, max_number_of_players=$max_players, is_denied='$is_denied', is_admin_denied='$is_admin_denied', signin_type='$signin_type' where event_id = $event_id;";
		//echo $update_query."<br>";
		$update_result = mysql_query($update_query) or die(mysql_error());
	}
}

// verify looking up a valid event
$event_query = "select l5r_events.*, login.Name  FROM (l5r_events LEFT JOIN login ON l5r_events.sponser_id = login.id) WHERE event_id=$event_id;";
$event_result = mysql_query($event_query) or die(mysql_error());

// if valid pull information from database and construct into form
if(mysql_num_rows($event_result))
{
	// get details
	$event_detail = mysql_fetch_array($event_result, MYSQL_ASSOC);
	
  $page_title = "View Event: $event_detail[Event_Name]";

	// test if the viewer is a person who can edit the fields
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
	
	// get values from detail
	$event_name = $event_detail['Event_Name'];
	$event_date = substr($event_detail['Event_Date'], 0, strlen($event_detail['Event_Date']) - 3);
	$expected_length = $event_detail['Expected_Length'];
	$event_short_desc = $event_detail['Event_Short_Description'];
	$event_long_desc = $event_detail['Event_Long_Description'];
	$sponser = $event_detail['Name'];
	$min_players = $event_detail['Min_Number_Of_Players'];
	$max_players = $event_detail['Max_Number_Of_Players'];
	$is_denied = $event_detail['Is_Denied'];
	$is_admin_denied = $event_detail['Is_Admin_Denied'];
	$signin_type = $event_detail['Signin_Type'];
	$is_denied = $event_detail['Is_Denied'];
	$is_admin_denied = $event_detail['Is_Admin_Denied'];
	$submit = "";
	$event_list = "";
	
	// test if may edit, if they may, then set regions as editable
  if($may_edit)
  {
	  // add js 
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

	  // set editable regions
	  $event_name = <<<EOQ
<input type="text" name="event_name" id="event_name" size="30" maxlength="100" value="$event_name">
EOQ;

    $event_short_desc = <<<EOQ
<input type="text" name="event_short_desc" id="event_short_desc" size="50" maxlength="255" value="$event_short_desc">
EOQ;

    $event_long_desc = str_replace("<br>", "\n", $event_long_desc);
    $event_long_desc = <<<EOQ
<textarea name="event_long_desc" id="event_long_desc" rows="8" cols="60" wrap="physical">$event_long_desc</textarea>
EOQ;

    $event_date = <<<EOQ
<input type="text" name="event_date" id="event_date" size="16" maxlength="16" value="$event_date">
EOQ;

    $expected_length = <<<EOQ
<input type="text" name="expected_length" id="expected_length" size="3" maxlength="3" value="$expected_length">
EOQ;

    $min_players = <<<EOQ
<input type="text" name="min_players" id="min_players" size="3" maxlength="3" value="$min_players">
EOQ;

    $max_players = <<<EOQ
<input type="text" name="max_players" id="max_players" size="3" maxlength="3" value="$max_players">
EOQ;
    
    $signin_types = array('Login', 'Character');
    $signin_type = buildSelect($signin_type, $signin_types, $signin_types, 'signin_type');  
    
    $submit = <<<EOQ
<input type="submit" value="Update Event" onClick="submitForm();return false;">
EOQ;
	  if($userdata['is_asst'] || $userdata['is_gm'] || $userdata['is_head'] || $userdata['is_admin'])
	  {
		  // they are a gm and so may see is_denied
		  $is_denied_yes_check = ($is_denied == 'Y') ? "checked" : "";
		  $is_denied_no_check = ($is_denied == 'N') ? "checked" : "";
		  
		  $is_denied = <<<EOQ
Yes: <input type="radio" name="is_denied" id="is_denied" value="Y" $is_denied_yes_check>
No: <input type="radio" name="is_denied" id="is_denied" value="N" $is_denied_no_check>
EOQ;
		  if($userdata['is_head'] || $userdata['is_admin'])
		  {
			  // may see is_admin_denied
			  $is_admin_denied_yes_check = ($is_admin_denied == 'Y') ? "checked" : "";
			  $is_admin_denied_no_check = ($is_admin_denied == 'N') ? "checked" : "";
			  
			  $is_admin_denied = <<<EOQ
Yes: <input type="radio" name="is_admin_denied" id="is_admin_denied" value="Y" $is_admin_denied_yes_check>
No: <input type="radio" name="is_admin_denied" id="is_admin_denied" value="N" $is_admin_denied_no_check>
EOQ;
		  }
	  }
  }
  
  // build form
  $form = <<<EOQ
<div align="center" class="highlight"><a href="$_SERVER[PHP_SELF]?action=event_list">Return to Event List</a></div>
<form name="event_form" id="event_form" method="post" action="$_SERVER[PHP_SELF]?action=event_view">
<table border="0" cellpadding="2" cellspacing="2" class="normal_text" width="100%">
  <tr valign="top">
    <td width="50%">
      <span class="highlight">Event Name:</span><br>
      $event_name<br>
      <br>
    </td>
    <td rowspan="6" width="50%">
      <iframe src="$_SERVER[PHP_SELF]?action=event_signins&event_id=$event_id" name="signins" id="signins" width="300" height="300" border="0" scrolling="yes" style="width:100%;"></iframe>
    </td>
  </tr>
  <tr valign="top">
    <td>
      <span class="highlight">Sponser:</span><br>
      $sponser<br>
      <br>
    </td>
  </tr>
  <tr valign="top">
    <td>
      <span class="highlight">Date:</span>
      Format: YYYY-MM-DD HH:MM<br>
      $event_date<br>
      <br>
    </td>
  </tr>
  <tr valign="top">
    <td>
      <span class="highlight">Expected Length:</span><br>
      $expected_length Hours<br>
      <br>
    </td>
  </tr>
  <tr valign="top">
    <td>
      <span class="highlight">Number of Players:</span> (Optional)<br>
      Min: $min_players
      Max: $max_players<br>
      <br>
    </td>
  </tr>
  <tr valign="top">
    <td>
      <span class="highlight">Signin Type:</span><br>
      $signin_type<br>
      <br>
    </td>
  </tr>
  <tr valign="top">
    <td colspan="2">
      <span class="highlight">Short Description:</span><br>
      $event_short_desc<br>
      <br>
    </td>
  </tr>
  <tr valign="top">
    <td colspan="2">
      <span class="highlight">Long Description:</span><br>
      $event_long_desc<br>
      <br>
    </td>
  </tr>
  <tr valign="top">
    <td colspan="2">
      <span class="highlight">Is Denied:</span> <br>
      $is_denied<br>
      <br>
    </td>
  </tr>
  <tr valign="top">
    <td colspan="2">
      <span class="highlight">Admin Denied:</span><br>
      $is_admin_denied<br>
      <br>
    </td>
  </tr>
  <tr>
    <td colspan="2">
      <input type="hidden" name="event_id" id="event_id" value="$event_id">
      $submit
    </td>
  </tr>
</table>
</form>
EOQ;
}
else
{
	// not a valid event and/or site
	$alert = <<<EOQ
<span class="red_highlight">That is not a valid Event ID.</span>
EOQ;
}

$page_content = <<<EOQ
$alert
$form
EOQ;

?>