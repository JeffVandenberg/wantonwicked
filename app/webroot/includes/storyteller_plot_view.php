<?
$page_content = "View Plot";

$plot_id = (isset($_POST['plot_id'])) ? $_POST['plot_id'] +0 : 0;
$plot_id = (isset($_GET['plot_id'])) ? $_GET['plot_id'] +0 : $plot_id;

$mode = (isset($_GET['mode'])) ? $_GET['mode'] : "";

// build start of page
$page_title = "View Details for Plot #$plot_id";

// page variables
$alert = "";
$form = "";

// form variables
$edit_plot_link = "";
$plot_name = "";
$start_date = "";
$end_date = "";
$plot_category = "";
$plot_type = "";
$synopsis = "";
$public_information = "";
$description = "";
$result = "";
$notes = "";
$status = "";
$test_values = "false";

// test if deleting
if(isset($_GET['delete']) && ($userdata['is_head'] || $userdata['is_admin']))
{
	$update_query = "update l5r_plots set status='Deleted' where plot_id=$plot_id;";
	$update_result = mysql_query($update_query) or die(mysql_error());
	
	$java_script = <<<EOQ
<script language="javascript">
	window.document.location.href = "$_SERVER[PHP_SELF]?action=plot_list";
</script>
EOQ;

	$plot_id = -1;
}

// test if updating
if(isset($_POST['view_action']))
{
	$validated = true;
	$short_now = date('Y-m-d');
	
	// get details from database
	$plot_query = "select * from l5r_plots where plot_id = $plot_id;";
	$plot_result = mysql_query($plot_query) or die(mysql_error());
	$plot_detail = mysql_fetch_array($plot_result, MYSQL_ASSOC);
	
	// get variables from post
	$plot_name = (isset($_POST['plot_name'])) ? htmlspecialchars($_POST['plot_name']) : addslashes($plot_detail['Plot_Name']);
	$start_date = (isset($_POST['start_date'])) ? $_POST['start_date'] : $plot_detail['Start_Date'];
	$end_date = (isset($_POST['end_date'])) ? $_POST['end_date'] : $plot_detail['End_Date'];
	$plot_category = (isset($_POST['plot_category'])) ? $_POST['plot_category'] : $plot_detail['Plot_Category'];
	$plot_type = (isset($_POST['plot_type'])) ? $_POST['plot_type'] : 0;
	$synopsis = (isset($_POST['synopsis'])) ? htmlspecialchars($_POST['synopsis']) : addslashes($plot_detail['Synopsis']);
	//$public_information = (isset($_POST['public_information'])) ? htmlspecialchars($_POST['public_information']) : addslashes($plot_detail['Public_Information']);
	$description = (isset($_POST['description'])) ? str_replace("\n", "<br>", htmlspecialchars($_POST['description'])) : addslashes($plot_detail['Description']);
	$result = (isset($_POST['result'])) ? str_replace("\n", "<br>", htmlspecialchars($_POST['result'])) : addslashes($plot_detail['Result']);
	$status = (isset($_POST['status'])) ? $_POST['status'] : $plot_detail['Status'];
	$thread_id = $plot_detail['Thread_ID'];
	
	$old_notes = $_POST['notes'];
	$new_notes = (isset($_POST['new_notes'])) ? $_POST['new_notes'] : "";
	$notes = <<<EOQ
$old_notes
$new_notes
$userdata[user_name] on $short_now
EOQ;

  // validate dates
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
				$alert .= "<span class=\"highlight\">$valid_start_date[message]</span><br>";
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
				$alert .= "<span class=\"highlight\">$valid_end_date[message]</span><br>";
			}
		}
		else
		{
			// inaccurate date format
			$validated = false;
			$alert .= "<span class=\"highlight\">Please Enter a Valid Date for End of Plot</span><br>";
		}
	}
	
	if(($start_date > $end_date) && ($end_date != ""))
	{
		$validated = false;
		$alert .= "<span class=\"highlight\">Starting Date is After the Ending Date.</span><br>";
	}

  if($validated)
  {
    // proceed to put into database
    $lock_tables_query = "lock tables l5r_plots write, l5r_plots_types_index write;";
    $lock_tables_result = mysql_query($lock_tables_query) or die(mysql_error());
    
    $update_query = "update l5r_plots set plot_name='$plot_name', plot_category='$plot_category', status='$status', start_date='$start_date', end_date='$end_date', synopsis='$synopsis', public_information='$public_information', description='$description', result='$result', notes = '$notes'";
    
    // add ST reassignment for Head STs and Admins
    if($userdata['is_head'] || $userdata['is_admin'])
    {
      $update_query .= ", submitter_id = $_POST[submitter_id] ";
    }
    $update_query .= " where plot_id=$plot_id;";
    //echo $update_query."<br>";
    $update_result = mysql_query($update_query) or die(mysql_error());
    
    if($plot_type)
    {
     $delete_query = "delete from l5r_plots_types_index where plot_id = $plot_id;";
     $delete_result = mysql_query($delete_query) or die(mysql_error());
    	while(list($key, $value) = each($plot_type))
    	{
    		$insert_plot_type_query = "insert into l5r_plots_types_index values($plot_id, $value);";
    		//echo "$insert_plot_type_query<br>";
    		$insert_plot_type_result = mysql_query($insert_plot_type_query) or die(mysql_error());
    	}
    }
    
    $unlock_tables_query = "unlock tables;";
    $unlock_tables_result = mysql_query($unlock_tables_query) or die(mysql_error());
  }
  else
  {
    echo "$alert<br>";
  }
}

// get details from database
$plot_query = <<<EOQ
SELECT l5r_plots.*, login.Name FROM l5r_plots LEFT JOIN login ON l5r_plots.submitter_id = login.ID WHERE l5r_plots.plot_id = $plot_id;
EOQ;
$plot_result = mysql_query($plot_query) or die(mysql_error());

if(mysql_num_rows($plot_result))
{
	// build up js
	$java_script = <<<EOQ
<script language="JavaScript">
function submitForm( test_fields )
{
	if(test_fields)
	{
		var fields = "";
		if(window.document.plot_form.plot_name.value == "")
		{
			fields = fields + "Plot Name, ";
		}
		
		var found_option = false;
	  var the_select = window.document.forms[0].elements[1];
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
  else
  {
		window.document.plot_form.submit();
  }
}
</script>
EOQ;
	
  // get details
	$plot_detail = mysql_fetch_array($plot_result, MYSQL_ASSOC);
  // set variables
  $plot_name = $plot_detail['Plot_Name'];
  $start_date = ($plot_detail['Start_Date'] != '0000-00-00') ? $plot_detail['Start_Date'] : "";
  $end_date = ($plot_detail['End_Date'] != '0000-00-00') ? $plot_detail['End_Date'] : "";
  $plot_category = $plot_detail['Plot_Category'];
  $synopsis = $plot_detail['Synopsis'];
  $public_information = $plot_detail['Public_Information'];
  $description = $plot_detail['Description'];
  $result = $plot_detail['Result'];
  $notes = <<<EOQ
<span class="highlight">Past Notes:</span><br>
<textarea name="notes" id="notes" rows="6" cols="60" readonly>$plot_detail[Notes]</textarea>
EOQ;
  $status = $plot_detail['Status'];
  $thread_id = $plot_detail['Thread_ID'];
  $sponser = $plot_detail['Name'];
  $submit = "";
  
  // list of plot types
  $plot_type_list = "";
  $plot_type_list_query = "select l5r_plots_types.Plot_Type_Name from l5r_plots_types left join l5r_plots_types_index on l5r_plots_types.plot_type_id = l5r_plots_types_index.plot_type_id where l5r_plots_types_index.plot_id = $plot_id;";
  $plot_type_list_result = mysql_query($plot_type_list_query) or die(mysql_error());
  
  while($plot_type_list_detail = mysql_fetch_array($plot_type_list_result, MYSQL_ASSOC))
  {
	  $plot_type_list .= "$plot_type_list_detail[Plot_Type_Name], ";
  }
  $plot_type = substr($plot_type_list, 0, strlen($plot_type_list)-2);
  
  // create link to be able to edit the plot
  if($userdata['is_head'] || $userdata['is_admin'] || ($userdata['user_id'] == $plot_detail['Submitter_ID']))
  {
	  $edit_plot_link = <<<EOQ
&nbsp;&nbsp;&nbsp;&nbsp;<a href="$_SERVER[PHP_SELF]?action=plot_view&plot_id=$plot_id&mode=edit">Edit This Plot</a>
EOQ;

		if($userdata['is_head'] || $userdata['is_admin'])
		{
			$edit_plot_link .= <<<EOQ
&nbsp;&nbsp;&nbsp;&nbsp;<a href="$_SERVER[PHP_SELF]?action=plot_view&plot_id=$plot_id&delete=y">DELETE This Plot</a>
EOQ;
		}
  }
  
  // test if may edit
  if($mode == 'edit')
  {
	  // may update notes, result, public information, and submit
	  $notes .= <<<EOQ
<br>
<span class="highlight">Your Notes:</span><br>
<textarea name="new_notes" id="new_notes" rows="6" cols="60"></textarea>
EOQ;

    $start_date = <<<EOQ
<input type="text" name="start_date" id="start_date" size="10" maxlength="10" value="$start_date">
EOQ;

    $end_date = <<<EOQ
<input type="text" name="end_date" id="end_date" size="10" maxlength="10" value="$end_date">
EOQ;

    $result = str_replace("<br>", "\n", $result);
    $result = <<<EOQ
<textarea name="result" id="result" rows="6" cols="70">$result</textarea>
EOQ;

    // may edit who the plot is assigned to
    if($userdata['is_head'] || $userdata['is_admin'])
    {
      $st_query = "SELECT login.Name, login.ID FROM login INNER JOIN gm_permissions ON login.ID = gm_permissions.ID WHERE (gm_permissions.is_asst = 'Y' OR gm_permissions.is_gm = 'Y') AND position != 'Hidden' ORDER BY login.Name;";
      $st_result = mysql_query($st_query) or die(mysql_error());
      
      $st_ids = "";
      $st_names = "";
      while($st_detail = mysql_fetch_array($st_result, MYSQL_ASSOC))
      {
        $st_ids[] = $st_detail['ID'];
        $st_names[] = $st_detail['Name'];
      }
      
      $sponser = buildSelect($plot_detail['Submitter_ID'], $st_ids, $st_names, 'submitter_id');
    }

	  if(($plot_detail['Status'] == 'Pending') || $userdata['is_head'] || $userdata['is_admin'])
	  {
		  $test_values = true;
		  // may edit plot name, plot types, synopsis, public information, description
		  $plot_name = <<<EOQ
<input type="text" name="plot_name" id="plot_name" size="40" maxlength="100" value="$plot_name">
EOQ;
      $synopsis = <<<EOQ
<input type="text" name="synopsis" id="synopsis" size="60" maxlength="255" value="$synopsis">
EOQ;

      $description = str_replace("<br>", "\n", $description);
      $description = <<<EOQ
<textarea name="description" id="description" rows="6" cols="70">$description</textarea>
EOQ;

      $plot_categories = array("One-Shot", "Adventure", "Setting", "Metaplot", "C/F/S");
      $plot_category = buildSelect($plot_category, $plot_categories, $plot_categories, "plot_category");
      
      // build up array of plot type ids  for plots
      $plot_type = "";
      $plot_type_query = "select * from l5r_plots_types_index where plot_id=$plot_id;";
      $plot_type_result = mysql_query($plot_type_query) or die(mysql_error());
      while ($plot_type_detail = mysql_fetch_array($plot_type_result, MYSQL_ASSOC))
      {
	      $plot_type[] = $plot_type_detail['Plot_Type_ID'];
      }
      
      // build up arrays of all types of plots
      $plot_types_ids = "";
      $plot_types_names = "";
      
      $plot_types_query = "select l5r_plots_types.* from l5r_plots_types order by plot_type_name;";
      
      $plot_types_result = mysql_query($plot_types_query) or die(mysql_error());
     	while($plot_types_detail = mysql_fetch_array($plot_types_result, MYSQL_ASSOC))
    	{
		    $plot_types_ids[] = $plot_types_detail['Plot_Type_ID'];
		    $plot_types_names[] = $plot_types_detail['Plot_Type_Name'];
	    }
	    $plot_type = buildMultiSelect($plot_type, $plot_types_ids, $plot_types_names, "plot_type[]", 5, true);

		  if($userdata['is_head'] || $userdata['is_admin'])
		  {
			  // may edit status
        $statuses = array("Pending", "In Progress", "Suspended", "Completed", "Was Used", "Denied", "Deleted");
        $status = buildSelect($status, $statuses, $statuses, "status");
		  }
	  }
	  // have submit at end
	  $submit = "<input type=\"submit\" value=\"Submit\" onClick=\"submitForm($test_values);return false;\">";
  }
  
  // build form
  $form = <<<EOQ
<div align="center"><a href="$_SERVER[PHP_SELF]?action=plot_list" target="_top">Return to Plot List</a> $edit_plot_link</div>
<form name="plot_form" id="plot_form" method="post" action="$_SERVER[PHP_SELF]?action=plot_view">
<table border="0" cellpadding="2" cellspacing="2" class="normal_text">
  <tr valign="top">
    <td width="25%">
      <span class="highlight">* Plot Name:</span>
    </td>
    <td width="75%">
      $plot_name
    </td>
  </tr>
  <tr valign="top">
    <td>
      <span class="highlight">Sponser:</span>
    </td>
    <td>
      $sponser
    </td>
  </tr>
  <tr valign="top">
    <td>
      <span class="highlight">Discussion Thread:</span>
    </td>
    <td>
      <a href="/forum/viewtopic.php?t=$thread_id" target="_blank">View Discussion Thread</a>
    </td>
  </tr>
  <tr valign="top">
    <td>
      <span class="highlight">* Plot Type(s)</span>
    </td>
    <td>
      $plot_type
    </td>
  </tr>
  <tr valign="top">
    <td>
      <span class="highlight">Category:</span>
    </td>
    <td>
      $plot_category
    </td>
  </tr>
  <tr valign="top">
    <td>
      <span class="highlight">Status:</span>
    </td>
    <td>
      $status
    </td>
  </tr>
  <tr valign="top">
    <td>
      <span class="highlight">Start Date:</span>
      Optional
    </td>
    <td>
      $start_date
    </td>
  </tr>
  <tr valign="top">
    <td>
      <span class="highlight">End Date:</span>
      Optional
    </td>
    <td>
      $end_date
    </td>
  </tr>
  <tr valign="top">
    <td>
      <span class="highlight">* Synopsis:</span>
    </td>
    <td>
      $synopsis
    </td>
  </tr>
  <tr valign="top">
    <td>
      <span class="highlight">* Description:</span>
    </td>
    <td>
      $description
    </td>
  </tr>
  <tr valign="top">
    <td>
      <span class="highlight">Result:</span>
    </td>
    <td>
      $result
    </td>
  </tr>
  <tr valign="top">
    <td>
      <span class="highlight">Notes:</span>
    </td>
    <td>
      $notes
    </td>
  </tr>
  <tr>
    <td colspan="2">
      <table border="0" cellpadding="2" cellspacing="2" class="normal_text">
        <tr>
          <td>
            <span class="highlight">Associated Events:</span><br>
            <iframe src="$_SERVER[PHP_SELF]?action=plot_events&plot_id=$plot_id" name="plot_events$plot_id" id="plot_events$plot_id" width="300" height="200" border="0" scrolling="yes"></iframe>
          </td>
          <td>
            <span class="highlight">Major PCs/NPCs:</span><br>
            <iframe src="$_SERVER[PHP_SELF]?action=plot_characters&plot_id=$plot_id" name="plot_events$plot_id" id="plot_events$plot_id" width="300" height="200" border="0" scrolling="yes"></iframe>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<input type="hidden" name="plot_id" id="plot_id" value="$plot_id">
<input type="hidden" name="view_action" id="action" value="update">
$submit
</form>
EOQ;
}
else
{
	$alert = <<<EOQ
<span class="highlight">That is not a valid Plot</span>
EOQ;
}

// build page
$page_content = <<<EOQ
$alert
$form
EOQ;

?>