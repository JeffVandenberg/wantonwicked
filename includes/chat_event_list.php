<?
$page_title = "Wanton Wicked Events";

$page = "";
$message = "";
$events_table = "";
$start_date = date ('Y-m-d', mktime (date('H') + $timezone_adjustment, date('i'), date('s'), date('m'), date('d'), date('Y')));
$end_date = date ('Y-m-d', mktime (0, 0, 0, date('m')+3, date('d'), date('Y')));
$col_span = 4;
$last_visit = 0;

// update values
$start_date = (isset($_POST['start_date'])) ? $_POST['start_date'] : $start_date;
$start_date = (isset($_GET['start_date'])) ? $_GET['start_date'] : $start_date;
$end_date = (isset($_POST['end_date'])) ? $_POST['end_date'] : $end_date;
$end_date = (isset($_GET['end_date'])) ? $_GET['end_date'] : $end_date;


// update the last visit date of a person to the page
if($userdata['user_id'] != 1)
{
  $date_check_query = "select * from l5r_events_last_visit where login_id=$userdata[user_id];";
  $date_check_result = mysql_query($date_check_query) or die(mysql_error());
  
  $query = "";
  $now = date('Y-m-d H:i:s');
  if(mysql_num_rows($date_check_result))
  {
	  // put value into $last visit
	  $date_check_detail = mysql_fetch_array($date_check_result, MYSQL_ASSOC);
	  $last_visit = $date_check_detail['Visit_Date'];
	  // do update
	  $query = "update l5r_events_last_visit set Visit_Date='$now' where Login_ID=$userdata[user_id];";
  }
  else
  {
	  // do insert
	  $query = "insert into l5r_events_last_visit values($userdata[user_id], '$now');";
  }
  $result = mysql_query($query) or die(mysql_error());
}

// test if supplying a different start and/or end date
if(!empty($_POST['start_date']))
{
	$start_date = str_replace("/", "-", $_POST['start_date']);
}
if(!empty($_POST['end_date']))
{
	$end_date = str_replace("/", "-", $_POST['end_date']);
}

// query database for events
$event_query = "select l5r_events.*, login.Name, count(l5r_events_signins.login_id) as Num_Of_Signins FROM (l5r_events LEFT JOIN login ON l5r_events.sponser_id = login.id) LEFT JOIN l5r_events_signins ON l5r_events.event_id = l5r_events_signins.event_id WHERE ";

// test if they are an ST or not
if($userdata['is_asst'] || $userdata['is_gm'] || $userdata['is_head'] || $userdata['is_admin'])
{
  // they are an ST
  if($userdata['is_head'] || $userdata['is_admin'])
  {
    // they are head ST or Admin
    $col_span = 6;
  }
  else
  {
    // they are a cell or full ST
  	$event_query .= "is_admin_denied = 'N' and ";
  	$col_span = 5;
  }
}
else
{
  // they are a player
	$event_query .= "is_admin_denied = 'N' and is_denied='N' and ";
}

$event_query .= " event_date > '$start_date' and event_date <= '$end_date' group by l5r_events.event_id order by event_date";


// get results
$event_result = mysql_query($event_query) or die(mysql_error());

if($userdata['user_id'] != 1)
{
  $events_table .= <<<EOQ
<div align="center"><a href="$_SERVER[PHP_SELF]?action=event_add">Add Event</a></div>
EOQ;
}

// build list of events
$events_table .= <<<EOQ
<div align="center">
<form method="post" action="$_SERVER[PHP_SELF]?action=event_list">
View Different Range of Dates of Events:<br>
Start Date: <input type="text" name="start_date" id="start_date" size="10" maxlength="10" value="$start_date">
End Date: <input type="text" name="end_date" id="end_date" size="10" maxlength="10" value="$end_date">
<input type="submit">
</form>
</div>
EOQ;

$events_table .= <<<EOQ
Click the title of the event to view the details and to sign up for the event if interested, signing up helps people know if there is enough interest or not.<br>
<br>
<table border="0" cellpadding="2" cellspacing="2" class="normal_text">
  <tr bgcolor="#000000">
    <th>
      Title
    </th>
    <th>
      Sponser
    </th>
    <th>
      Event Date &amp; Time
    </th>
    <th>
      Number of Players
    </th>
EOQ;
if($col_span >= 5)
{
	$events_table .= <<<EOQ
	  <th>
	    Is Denied
	  </th>
EOQ;
}
if($col_span >= 6)
{
	$events_table .= <<<EOQ
	  <th>
	    Is Admin Denied
	  </th>
EOQ;
}

$events_table .= <<<EOQ
  </tr>
EOQ;

$row = 0;
while ($event_detail = mysql_fetch_array($event_result, MYSQL_ASSOC))
{
	$row_color = (($row++)%2) ? "#443a33" : "";
	$event_name = ($event_detail['Create_Date'] > $last_visit) ? "<span class=\"highlight\">$event_detail[Event_Name]</span>" : $event_detail['Event_Name'];
	
	$event_time = substr($event_detail['Event_Date'], 0, strlen($event_detail['Event_Date']) - 3);
	$is_denied = "";
	$is_admin_denied = "";
	
	if($col_span >= 5)
	{
		$is_denied = <<<EOQ
		<td>
		  $event_detail[Is_Denied]
		</td>
EOQ;
	}
	
	if($col_span >= 6)
	{
		$is_admin_denied = <<<EOQ
		<td>
		  $event_detail[Is_Admin_Denied]
		</td>
EOQ;
	}
	$events_table .= <<<EOQ
	<tr bgcolor="$row_color">
	  <td>
	    <a href="$_SERVER[PHP_SELF]?action=event_view&event_id=$event_detail[Event_ID]">$event_name</a>
	  </td>
	  <td>
	    $event_detail[Name]
	  </td>
	  <td>
	    $event_time
	  </td>
	  <td>
	    $event_detail[Num_Of_Signins]
	  </td>
	  $is_denied
	  $is_admin_denied
	</tr>
	<tr bgcolor="$row_color">
	  <td colspan="$col_span">
	    Description: $event_detail[Event_Short_Description]
	  </td>
	</tr>
EOQ;
}

$events_table .= "</table>";

// build page
$message = <<<EOQ
Below you can see a schedule of events for Wanton Wicked. Players have the option of adding events.  Feel free to use this resource to help you schedule events that you are planning that other players would be interested in.<br>
EOQ;

$page_content = <<<EOQ
$message
<br>
$events_table
EOQ;


?>