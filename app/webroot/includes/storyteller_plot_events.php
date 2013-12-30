<?
if(isset($_POST['action']))
{
	$event_list = $_POST['delete'];
	while(list($key, $value) = each($event_list))
	{
		//echo "delete: $key: $value<br>";
		$delete_query = "delete from l5r_plots_events where plot_event_id=$value;";
		$delete_result = mysql_query($delete_query) or die(mysql_error());
	}
}

// query db to verify using appropriate plot id
$plot_query = "select * from l5r_plots where plot_id = $plot_id;";
$plot_result = mysql_query($plot_query) or die(mysql_error());

if(mysql_num_rows($plot_result))
{
	// build contents of page
	$plot_detail = mysql_fetch_array($plot_result, MYSQL_ASSOC);
	
	// determine if may edit the list or not
	if(($plot_detail['Submitter_ID'] == $userdata['user_id']) || $userdata['is_admin'] || $userdata['is_head'])
	{
		$may_edit = true;
	}
	
	// determine if may add/delete events
	if($may_edit)
	{
    $js = <<<EOQ
<script language="JavaScript">
function submitForm ( )
{
	window.document.event_list.submit();
}
</script>
EOQ;

    $event_list = <<<EOQ
<a href="$_SERVER[PHP_SELF]?action=plot_events_add" onClick="window.open('$_SERVER[PHP_SELF]?action=plot_events_add&plot_id=$plot_id', 'plot_event$plot_id', 'width=400,height=300,resizable,scrollbars');return false;">Add Event</a>&nbsp;&nbsp;&nbsp;&nbsp;
<a href="#" onClick="submitForm();return false;">Remove Event(s)</a>
EOQ;
  }
  
  // build list of events
	$plot_event_query = "select l5r_plots_events.*, l5r_events.Event_Name from l5r_plots_events left join l5r_events on l5r_plots_events.event_id = l5r_events.event_id where l5r_plots_events.plot_id = $plot_id;";
	$plot_event_result = mysql_query($plot_event_query) or die(mysql_error());

	$event_list .= <<<EOQ
<form name="event_list" id="event_list" method="post" action="$_SERVER[PHP_SELF]?action=plot_events">
<table border="0" cellpadding="2" cellspacing="2" class="normal_text">
  <tr bgcolor="#000000">
EOQ;

  if($may_edit)
  {
	  $event_list .= "<th>Delete</th>";
  }
  $event_list .= "<th>Event Name</th><th>&nbsp;</th></tr>";
  
  $row = 0;
	while($plot_event_detail = mysql_fetch_array($plot_event_result, MYSQL_ASSOC))
	{
		$row_color = (($row++)%2) ? "#443a33" : "";
		$delete_cell = ($may_edit) ? "<td><input type=\"checkbox\" name=\"delete[]\" id=\"delete[]\" value=\"$plot_event_detail[Plot_Event_ID]\"></td>" : "";
		$event_list .= <<<EOQ
  <tr bgcolor="$row_color">
    $delete_cell
    <td>
      <a href="chat.php?action=event_view&event_id=$plot_event_detail[Event_ID]" target="_blank">$plot_event_detail[Event_Name]</a>
    </td>
    <td>
      <a href="$_SERVER[PHP_SELF]?action=plot_events_view&plot_event_id=$plot_event_detail[Plot_Event_ID]" onClick="window.open('$_SERVER[PHP_SELF]?action=plot_events_view&plot_event_id=$plot_event_detail[Plot_Event_ID]', 'view_plot_event$plot_event_detail[Plot_Event_ID]', 'width=400,height=300,resizable,scrollbars');return false;">View Note</a>
    </td>
  </tr>
EOQ;
	}
	$event_list .= <<<EOQ
</table>
<input type="hidden" name="plot_id" id="plot_id" value="$plot_id">
<input type="hidden" name="action" id="action" value="delete">
</form>
EOQ;

}
else
{
	$alert = <<<EOQ
<span class="highlight">That is an invalid Plot ID</span>
EOQ;
}

// build page
$page_content = <<<EOQ
$js
$alert
$event_list
EOQ;
?>