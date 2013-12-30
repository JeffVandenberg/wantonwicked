<?
$page_title = "View Events";
$add_left = false;
include 'start_of_page.php';

// page variables
$page = "";
$message = "";
$events_table = "";
$start_date = date('Y-m-d');
$end_date = date ('Y-m-d', mktime (0, 0, 0, date('m')+3, date('d'), date('Y')));
$site_id = 1000;
$col_span = 4;
$last_visit = 0;

// update values
$start_date = (isset($_POST['start_date'])) ? $_POST['start_date'] : $start_date;
$start_date = (isset($_GET['start_date'])) ? $_GET['start_date'] : $start_date;
$end_date = (isset($_POST['end_date'])) ? $_POST['end_date'] : $end_date;
$end_date = (isset($_GET['end_date'])) ? $_GET['end_date'] : $end_date;
$site_id = (isset($_POST['site_id'])) ? $_POST['site_id'] : $site_id;
$site_id = (isset($_GET['site_id'])) ? $_GET['site_id'] : $site_id;

// update the last visit date of a person to the page
if($_SESSION['user_id'])
{
  $date_check_query = "select * from l5r_events_last_visit where login_id=$_SESSION[user_id];";
  $date_check_result = $mysqli->query($date_check_query);
  
  $query = "";
  $now = date('Y-m-d H:i:s');
  if($date_check_result->num_rows)
  {
	  // put value into $last visit
	  $date_check_detail = $date_check_result->fetch_array(MYSQLI_ASSOC);
	  $last_visit = $date_check_detail['Visit_Date'];
	  // do update
	  $query = "update l5r_events_last_visit set Visit_Date='$now' where Login_ID=$_SESSION[user_id];";
  }
  else
  {
	  // do insert
	  $query = "insert into l5r_events_last_visit values($_SESSION[user_id], '$now');";
  }
  $result = $mysqli->query($query);
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
$event_query = "select l5r_events.*, login.Name, count(l5r_events_signins.login_id) as Num_Of_Signins FROM (l5r_events LEFT JOIN login ON l5r_events.sponser_id = login.id) LEFT JOIN l5r_events_signins ON l5r_events.event_id = l5r_events_signins.event_id WHERE event_site = $site_id ";
// if is GM and make sure to query only from the appropriate site
if(!empty($_SESSION['site_id']))
{
	// they have a site entered
	if($_SESSION['site_id'] == $site_id)
	{
		// they are a GM in the site they are viewing
		if(!$_SESSION['is_head'] && !$_SESSION['is_admin'])
		{
			// GMs can see other denied, but not admin denied
			$event_query .= "and is_admin_denied = 'N' ";
			$col_span = 5;
		}
		else
		{
			$col_span = 6;
		}
	}
	else
	{
		// they are not a GM of the site events they are viewing
		$event_query .= "and is_admin_denied = 'N' and is_denied='N' ";
	}
}
else
{
	// they are a player and don't see what is denied by GMs and Admin
	$event_query .= "and is_admin_denied = 'N' and is_denied='N' ";
}

// add time criteria and order by to query 
$event_query .= " and event_date > '$start_date' and event_date <= '$end_date' group by l5r_events.event_id order by event_date";

// get results
$event_result = $mysqli->query($event_query);

// build list of events
if($_SESSION['user_id'])
{
  $events_table .= <<<EOQ
<center><a href="l5r_events_add.fro?site_id=$site_id" class="highlight">Add Event</a></center>
<br>
EOQ;
}

$events_table .= <<<EOQ
<form method="post" action="$_SERVER[PHP_SELF]">
View Different Range of Dates of Events:<br>
Start Date: <input type="text" name="start_date" id="start_date" size="10" maxlength="10" value="$start_date">
End Date: <input type="text" name="end_date" id="end_date" size="10" maxlength="10" value="$end_date">
<input type="submit">
</form>
<br>
Click the title of the event to view the details and to sign up for the event if interested, signing up helps people know if there is enough interest or not.<br>
<br>
<table border="0" cellpadding="2" cellspacing="2" class="normal_text">
  <tr bgcolor="$info_table_header_color">
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

$row = 1;

while ($event_detail = $event_result->fetch_array(MYSQLI_ASSOC))
{
	$row_color = (($row++)%2) ? $info_table_row_color : "";
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
	    <a href="l5r_events_view.fro?site_id=$site_id&event_id=$event_detail[Event_ID]">$event_name</a>
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
Below you can see a schedule of events for the game. Players do have the option of adding
events.  Please use this resource to help you schedule events that you are planning, it
becomes more useful as more people use it.<br>
EOQ;

$message = buildTextBox( $message, "", $border, $border_bgcolor, $border_background, $inner_background, $inner_bgcolor );

$events_table = buildTextBox( $events_table, "", $border, $border_bgcolor, $border_background, $inner_background, $inner_bgcolor );
$page = <<<EOQ
$message
<br>
$events_table
EOQ;
// echo page
echo $page;

include 'end_of_page.php';
?>