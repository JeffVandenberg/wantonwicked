<?
$page_content = "Add Plot";
// page variables
$page = "";
$alert = "";
$js = "";
$form = "";
$show_form = true;

// form variables
$plot_name = "";
$start_date = date('Y-m-d');
$end_date = date('Y-m-d');
$plot_category = "";
$plot_type = "";
$synopsis = "";
$public_information = "";
$description = "";
$mode = "debug";

// test if trying to add
if(!empty($_POST['plot_name']))
{
	$validated = true;
	
	// get variables from post
	$plot_name = htmlspecialchars($_POST['plot_name']);
	$start_date = (!empty($_POST['start_date'])) ? $_POST['start_date'] : 0;
	$end_date = (!empty($_POST['end_date'])) ? $_POST['end_date'] : 0;
	$plot_category = $_POST['plot_category'];
	$plot_type = $_POST['plot_type'];
	$synopsis = htmlspecialchars($_POST['synopsis']);
	//$public_information = (!empty($_POST['public_information'])) ? htmlspecialchars($_POST['public_information']) : "";
	$description = str_replace("\n", "<br>", htmlspecialchars($_POST['description']));
	
	// validate dates
	if ($start_date != 0)
	{
		$start_date = str_replace("/", "-", $start_date);
		$date_parts = explode("-", $start_date);
		if(sizeof($date_parts) == 3)
		{
			$valid_start_date = verifyDate($date_parts['0'], $date_parts['1'], $date_parts['2']);
			if($valid_start_date['verified'])
			{
				$start_date = $valid_start_date['date'];
			}
			else
			{
				$validated = false;
				$alert .= "<span class=\"highlight\">Invalid Start Date: $valid_start_date[message]</span><br>";
			}
		}
		else
		{
			// inaccurate date format
			$validated = false;
			$alert .= "<span class=\"highlight\">Please Enter a Valid Date for Start of Plot</span><br>";
		}
	}
	
	if ($end_date != 0)
	{
		$end_date = str_replace("/", "-", $end_date);
		$date_parts = explode("-", $end_date);
		if(sizeof($date_parts) == 3)
		{
			$valid_end_date = verifyDate($date_parts['0'], $date_parts['1'], $date_parts['2']);
			if($valid_end_date['verified'])
			{
				$end_date = $valid_end_date['date'];
			}
			else
			{
				$validated = false;
				$alert .= "<span class=\"highlight\">Invalid End Date: $valid_end_date[message]</span><br>";
			}
		}
		else
		{
			// inaccurate date format
			$validated = false;
			$alert .= "<span class=\"highlight\">Please Enter a Valid Date for End of Plot</span><br>";
		}
	}
	
	if(($start_date > $end_date) && ($end_date >0))
	{
		$validated = false;
		$alert .= "<span class=\"highlight\">Starting Date is After the Ending Date.</span><br>";
	}
	
	if($validated)
	{
    $now = date('Y-m-d H:i:s');

		$lock_query = "lock tables l5r_plots write, l5r_plots_types_index write;";
		$lock_result = mysql_query($lock_query) or die(mysql_error());

		$plot_id = getNextID($mysqli, "l5r_plots", "plot_id");

		$insert_plot_query = "insert into l5r_plots values('$plot_name', $plot_id, 0, $userdata[user_id], '$now', '$start_date', '$end_date', '$plot_category', '$synopsis', '$public_information', '$description', '', '', 'Pending', 'Y', 0, '0', 0);";
		//echo "$insert_plot_query<br>";
		$insert_plot_result = mysql_query($insert_plot_query) or die(mysql_error());
		
		while(list($key, $value) = each($plot_type))
		{
			$insert_plot_type_query = "insert into l5r_plots_types_index values($plot_id, $value);";
			//echo "$insert_plot_type_query<br>";
			$insert_plot_type_result = mysql_query($insert_plot_type_query) or die(mysql_error());
		}
		
		$lock_query = "unlock tables;";
		$lock_result = mysql_query($lock_query) or die(mysql_error());
		
		// send email message notification to GM list
		$gm_list_message = <<<EOQ
$userdata[user_name] has added a new plot
Title of Plot: $plot_name
Category: $plot_category
Synopsis: $synopsis

View full details at http://www.wantonwicked.net{$_SERVER[PHP_SELF]}?action=plot_view&plot_id=$plot_id
EOQ;
		
		$message = ($gm_list_message);
		include($phpbb_root_path . 'includes/bbcode.'.$phpEx);
		include($phpbb_root_path . 'includes/functions_post.'.$phpEx);

		$mode = "newtopic";
		$username = "JeffV";
		$subject = ("New Plot added: $plot_name");
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
		$forum_id = 16;
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
		else
		{
		  echo "PHPBB: $error_msg<br>";
		}
			
		// restore them back to original values
		$userdata['user_id'] = $temp_id;
		$userdata['username'] = $temp_name;
		
		// get the thread id and add it to plot
		$thread_id = getNextID($connection, "phpbb_topics", "topic_id") - 1;
		$update_query = "update l5r_plots set thread_id = $thread_id where plot_id = $plot_id;";
		$update_result = mysql_query($update_query) or die(mysql_error());
    
		
    // forward them to view the plot
		$show_form = false;
		$java_script = <<<EOQ
<script language="JavaScript">
  window.location.href="$_SERVER[PHP_SELF]?action=plot_view&plot_id=$plot_id";
</script>
EOQ;
	}
	else
	{
		$plot_name = stripslashes($plot_name);
		$synopsis = stripslashes($synopsis);
		$description = stripslashes(str_replace("<br>", "\n", $description));
	}
}
// if show form build up form
if($show_form)
{
	// build up js
	$java_script = <<<EOQ
<script language="JavaScript">
function submitForm()
{
	var fields = "";
	if(window.document.plot_form.plot_name.value == "")
	{
		fields = fields + "Plot Name, ";
	}
	
	var found_option = false;
  var the_select = window.document.forms[0].elements[4];
  for (var loop=0; (loop < the_select.options.length); loop++)
  {
    if (the_select.options[loop].selected == true)
    {
      found_option = true;
    }
  }
  
  if(!found_option)
  {
	  fields = fields + "Plot Type, ";
  }

	if(window.document.plot_form.synopsis.value == "")
	{
		fields = fields + "Synopsis, ";
	}
	
	if(window.document.plot_form.description.value == "")
	{
		fields = fields + "Description, ";
	}

	// test if validated
	if(fields == "")
	{
		window.document.plot_form.submit();
	}
	else
	{
		fields = fields.substring(0, fields.length-2);
		alert("Please enter the following fields : " + fields + " then click Submit");
	}
}
</script>
EOQ;
	
	// build form
	// build select for plot categories
	$plot_categories = array("One-Shot", "Adventure", "Setting", "Metaplot", "C/F/S");
	$plot_category_select = buildSelect($plot_category, $plot_categories, $plot_categories, "plot_category");
	
	// build multiselect for plot types
	$plot_types_query = "select * from l5r_plots_types order by plot_type_name;";
	$plot_types_result = mysql_query($plot_types_query) or die(mysql_error());
	
	$plot_types_ids = "";
	$plot_types_names = "";
	
	while($plot_types_detail = mysql_fetch_array($plot_types_result, MYSQL_ASSOC))
	{
		$plot_types_ids[] = $plot_types_detail['Plot_Type_ID'];
		$plot_types_names[] = $plot_types_detail['Plot_Type_Name'];
	}
	
	$plot_type_select = buildMultiSelect($plot_type, $plot_types_ids, $plot_types_names, "plot_type[]", 5, true);
	
	$form = <<<EOQ
<form name="plot_form" id="plot_form" method="post" action="$_SERVER[PHP_SELF]?action=plot_add">
<table border="0" cellspacing="2" cellpadding="2" class="normal_text">
  <tr valign="top">
    <td>
      <span class="highlight">* Plot Name:</span>
    </td>
    <td>
      <input type="text" name="plot_name" id="plot_name" size="40" maxlength="100" value="$plot_name">
    </td>
    <td>
      <span class="highlight">Category</span>
    </td>
    <td>
      $plot_category_select
    </td>
  </tr>
  <tr valign="top">
    <td>
      <span class="highlight">Start Date:</span><br>
      Or Date of Event (for One shots)<br>
      Optional
    </td>
    <td>
      <input type="text" name="start_date" id="start_date" size="10" maxlength="10" value="$start_date">
    </td>
    <td>
      <span class="highlight">End Date:</span><br>
      Or Estimated End Date<br>
      Optional
    </td>
    <td>
      <input type="text" name="end_date" id="end_date" size="10" maxlength="10" value="$end_date">
    </td>
  </tr>
  <tr valign="top">
    <td>
      <span class="highlight">* Plot Type(s):</span><br>
      Select all applicable type(s)
    </td>
    <td>
      $plot_type_select
    </td>
    <td>
      &nbsp;
    </td>
    <td>
      &nbsp;
    </td>
  </tr>
  <tr valign="top">
    <td>
      <span class="highlight">* Synopsis:</span><br>
      Max 255 Character
    </td>
    <td colspan="3">
      <input type="text" name="synopsis" id="synopsis" size="60" maxlength="255" value="$synopsis">
    </td>
  </tr>
  <tr valign="top">
    <td>
      <span class="highlight">* Description:</span><br>
      Include as much further detail as you wish
    </td>
    <td colspan="3">
      <textarea name="description" id="description" rows="8" cols="50">$description</textarea>
    </td>
  </tr>
  <tr>
    <td valign="top">
      <input type="submit" onClick="submitForm();return false;">
    </td>
  </tr>
</table>
</form>
EOQ;
}

// build up page
$page_content = <<<EOQ
$alert
$form
EOQ;

?>