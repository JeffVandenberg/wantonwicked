<?
$event_id = 0;
$event_id = (isset($_POST['event_id'])) ? $_POST['event_id'] +0 : $event_id;
$event_id = (isset($_GET['event_id'])) ? $_GET['event_id'] +0 : $event_id;

$page_title = "View Signins for #$event_id";
$add_bars = false;
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

// get details of event
$event_query = "select * from l5r_events where event_id=$event_id;";
$event_result = $mysqli->query($event_query);
$event_detail = $event_result->fetch_array(MYSQLI_ASSOC);

// test if submitting a login
if(isset($_POST['character_id']) && $_SESSION['user_id'])
{
	$insert_query = "insert into l5r_events_signins values (null, $_SESSION[user_id], $event_id, $_POST[character_id]);";
	$insert_result = $mysqli->query($insert_query);
	
	$show_form = false;
	$js = <<<EOQ
<script language="JavaScript">
window.opener.location.href = "l5r_events_signins.fro?event_id=$event_id&site_id=$site_id";
window.close();
</script>
EOQ;
}

// show form
if($show_form && $_SESSION['user_id'])
{
	// get characters for the player
	$character_query = "select l5rcharacter.Character_ID, l5rcharacter.Character_Name from l5rcharacter left join login_character_index on l5rcharacter.character_id = login_character_index.character_id where login_character_index.login_id = $_SESSION[user_id] order by l5rcharacter.character_name;";
	$character_result = $mysqli->query($character_query);
	
	$character_ids = "";
	$character_names = "";
	
	while($character_detail = $character_result->fetch_array(MYSQLI_ASSOC))
	{
		$character_ids[] = $character_detail['Character_ID'];
		$character_names[] = $character_detail['Character_Name'];
	}
	
	$character_select = buildSelect("", $character_ids, $character_names, "character_id");
	
	// build form
	$form = <<<EOQ
<form method="post" action="$_SERVER[PHP_SELF]">
<span class="highlight">Event Name:</span> $event_detail[Event_Name]<br>
<br>
<span class="highlight">Login Name:</span> $_SESSION[user_name]<br>
<br>
<span class="highlight">Character:</span> $character_select<br>
<br>
<input type="hidden" name="site_id" id="site_id" value="$site_id">
<input type="hidden" name="event_id" id="event_id" value="$event_id">
<input type="submit" value="Register">
</form>
EOQ;

  $form = buildTextBox( $form, "", $border, $border_bgcolor, $border_background, $inner_background, $inner_bgcolor );

}

$page = <<<EOQ
$js
$alert
$form
EOQ;

echo $page;
?>