<?
// get plot id
$plot_event_id = 0;
$plot_event_id = (isset($_POST['plot_event_id'])) ? $_POST['plot_event_id'] +0 : $plot_event_id;
$plot_event_id = (isset($_GET['plot_event_id'])) ? $_GET['plot_event_id'] +0 : $plot_event_id;

// build start of page
$page_title = "View Event Note #$plot_event_id";

// page variables
$page = "";
$alert = "";
$js = "";
$form = "";
$show_form = true;
$may_edit = false;

// form variables
$notes_readonly = "readonly";
$submit = "";

// test if adding an event to the plot
if(isset($_POST['action']))
{
	$update_query = "update l5r_plots_events set notes='$_POST[notes]' where plot_event_id=$plot_event_id;";
	$update_result = mysql_query($update_query);
}

// get details of event for plot
$plot_event_query = "select l5r_events.Event_Name, l5r_plots.*, l5r_plots_events.Notes from (l5r_plots_events left join l5r_plots on l5r_plots_events.plot_id = l5r_plots.plot_id) left join l5r_events on l5r_plots_events.event_id = l5r_events.event_id where l5r_plots_events.plot_event_id = $plot_event_id;";
$plot_event_result = mysql_query($plot_event_query);
$plot_event_detail = mysql_fetch_array($plot_event_result, MYSQL_ASSOC);

// test if may edit this
if($plot_event_detail['Submitter_ID'] == $userdata['user_id'] || $userdata['is_head'] || $userdata['is_admin'])
{
	$submit = "<input type=\"submit\" value=\"Submit\">";
	$notes_readonly = "";
}

// build form on page
$form = <<<EOQ
<form method="post" action="$_SERVER[PHP_SELF]?action=plot_events_view">
<table border="0" cellpadding="2" cellspacing="2" class="normal_text">
  <tr>
    <td>
      <span class="highlight">Plot Name:</span>
    </td>
    <td>
      $plot_event_detail[Plot_Name]
    </td>
  </tr>
  <tr>
    <td>
      <span class="highlight">Event Name:</span>
    </td>
    <td>
      $plot_event_detail[Event_Name]
    </td>
  </tr>
  <tr>
    <td colspan="2">
      <span class="highlight">Notes:</span>
    </td>
  </tr>
  <tr>
    <td colspan="2">
      <textarea name="notes" id="notes" cols="40" rows="7" wrap="physical" $notes_readonly>$plot_event_detail[Notes]</textarea>
    </td>
  </tr>
  <tr>
    <td colspan="2" align="center">
      <input type="hidden" name="plot_event_id" id="plot_event_id" value="$plot_event_id">
      <input type="hidden" name="action" id="action" value="update">
      $submit
    </td>
  </tr>
</form>
EOQ;

// build page
$page_content = <<<EOQ
$alert
$form
EOQ;

?>