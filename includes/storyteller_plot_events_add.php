<?
// get plot id
$plot_id = 0;
$plot_id = (isset($_POST['plot_id'])) ? $_POST['plot_id'] +0 : $plot_id;
$plot_id = (isset($_GET['plot_id'])) ? $_GET['plot_id'] +0 : $plot_id;

// build start of page
$page_title = "Add Event to Plot #$plot_id";

// page variables
$alert = "";
$js = "";
$form = "";
$show_form = true;

// test if adding an event to the plot
if(isset($_POST['action']))
{
	// get event id
	$event_id = 0;
	$event_id = ($_POST['event_id_source'] == 'select') ? $_POST['event_id_select'] +0 : $event_id;
	$event_id = ($_POST['event_id_source'] == 'text') ? $_POST['event_id_text'] +0 : $event_id;
	
	// test to make sure we have a valid event id
	$event_query = "select * from l5r_events where event_id=$event_id;";
	$event_result = mysql_query($event_query) or die(mysql_error());
	
	if(mysql_num_rows($event_result))
	{
		// insert values
		$insert_query = "insert into l5r_plots_events values (null, $plot_id, $event_id, '$_POST[notes]');";
		$insert_result = mysql_query($insert_query) or die(mysql_error());
		
		// hide form and put in js to refresh event listing and close window.
		$show_form = false;
		$java_script = <<<EOQ
<script language="javascript">
  window.opener.location.reload(true);
  window.opener.focus();
  window.close();
</script>
EOQ;
	}
	else
	{
		$alert = "<span class=\"highlight\">Please put in a valid Event ID.</span>";
	}
}

if($show_form)
{
	// get details of plot
	$plot_query = "select * from l5r_plots where plot_id = $plot_id;";
  $plot_result = mysql_query($plot_query) or die(mysql_error());
	$plot_detail = mysql_fetch_array($plot_result, MYSQL_ASSOC);

	// build drop down of plots that will occure in the next 6 months
	$start_date = date('Y-m-d', mktime (0, 0, 0, date('m')-1, date('d'), date('Y')));
	$end_date = date ('Y-m-d', mktime (0, 0, 0, date('m')+6, date('d'), date('Y')));
	
	$event_query = "select * from l5r_events where event_date >= '$start_date' and event_date <= '$end_date' order by event_date;";
	$event_result = mysql_query($event_query) or die(mysql_error());
	
	$event_ids = "";
	$event_names = "";
	
	while($event_detail = mysql_fetch_array($event_result, MYSQL_ASSOC))
	{
		$event_ids[] = $event_detail['Event_ID'];
		$temp_event_name = (strlen($event_detail['Event_Name']) > 30) ? substr($event_detail['Event_Name'], 0, 30) . "..." : $event_detail['Event_Name'];
		$event_names[] = $temp_event_name;
	}
	
	$event_select = buildSelect("", $event_ids, $event_names, "event_id_select");
	
	// build form
	$form = <<<EOQ
<form method="post" action="$_SERVER[PHP_SELF]?action=plot_events_add">
<table border="0" cellpadding="2" cellspacing="2" class="normal_text">
  <tr>
    <td>
      <span class="highlight">Plot Name:</span>
    </td>
    <td>
      $plot_detail[Plot_Name]
    </td>
  </tr>
  <tr valign="top">
    <td>
      <span class="highlight">Event Name:</span><br>
      Scheduled events<br>
      for next 6 months
    </td>
    <td>
      <input type="radio" name="event_id_source" id="event_id_source" value="select" checked>
      $event_select
    </td>
  </tr>
  <tr>
    <td>
      <span class="highlight">Event ID:</span><br>
      OR Type in Event<br>
      ID Manually.
    </td>
    <td>
      <input type="radio" name="event_id_source" id="event_id_source" value="text">
      <input type="text" name="event_id_text" id="event_id_text" size="3" maxlength="5" value="">
    </td>
  </tr>
  <tr>
    <td>
      <span class="highlight">Notes:</span>
    </td>
    <td>
      &nbsp;
    </td>
  </tr>
  <tr>
    <td colspan="2">
      <textarea name="notes" id="notes" cols="40" rows="5" wrap="physical"></textarea>
    </td>
  </tr>
  <tr>
    <td colspan="2" align="center">
      <input type="hidden" name="action" name="action" value="create">
      <input type="hidden" name="plot_id" id="plot_id" value="$plot_id">
      <input type="submit" value="Submit">
    </td>
  </tr>
</table>
</form>
EOQ;
}

// build page
$page_content = <<<EOQ
$alert
$form
EOQ;
?>