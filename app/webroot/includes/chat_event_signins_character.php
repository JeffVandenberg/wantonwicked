<?
$event_id = (isset($_POST['event_id'])) ? $_POST['event_id'] +0 : 0;
$event_id = (isset($_GET['event_id'])) ? $_GET['event_id'] +0 : $event_id;

// page variables
$page = "";
$alert = "";
$form = "";
$show_form = true;

// get details of event
$event_query = "select * from l5r_events where event_id=$event_id;";
$event_result = mysql_query($event_query) or die(mysql_error());
$event_detail = mysql_fetch_array($event_result, MYSQL_ASSOC);

$page_title = "Signin character for $event_detail[Event_Name]";

// test if submitting a login
if(isset($_POST['character_id']) && ($userdata['user_id'] != 1))
{
	$insert_query = "insert into l5r_events_signins values (null, $userdata[user_id], $event_id, $_POST[character_id]);";
	$insert_result = mysql_query($insert_query) or die(mysql_error());
	
	$show_form = false;
	$java_script = <<<EOQ
<script language="JavaScript">
window.opener.location.href = "$_SERVER[PHP_SELF]?action=event_signins&event_id=$event_id";
window.close();
</script>
EOQ;
}

// show form
if($show_form && ($userdata['user_id'] != 1))
{
	// get characters for the player
	$character_query = "select wod_characters.Character_ID, wod_characters.Character_Name from wod_characters left join login_character_index on wod_characters.character_id = login_character_index.character_id where login_character_index.login_id = $userdata[user_id] and wod_characters.is_deleted='n' order by wod_characters.character_name;";
	$character_result = mysql_query($character_query) or die(mysql_error());
	
	$character_ids = "";
	$character_names = "";
	
	while($character = mysql_fetch_array($character_result, MYSQL_ASSOC))
	{
		$character_ids[] = $character['Character_ID'];
		$character_names[] = $character['Character_Name'];
	}
	
	$character_select = buildSelect("", $character_ids, $character_names, "character_id");
	
	// build form
	$form = <<<EOQ
<form method="post" action="$_SERVER[PHP_SELF]?action=event_signins_character">
<span class="highlight">Event Name:</span> $event_detail[Event_Name]<br>
<br>
<span class="highlight">Login Name:</span> $userdata[user_name]<br>
<br>
<span class="highlight">Character:</span> $character_select<br>
<br>
<input type="hidden" name="event_id" id="event_id" value="$event_id">
<input type="submit" value="Register">
</form>
EOQ;

}

$page_content = <<<EOQ
$js
$alert
$form
EOQ;
?>