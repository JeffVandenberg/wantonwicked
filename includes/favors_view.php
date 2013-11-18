<?php
// get character id
$favorId = isset($_POST['favor_id']) ? $_POST['favor_id'] + 0 : 0;
$favorId = isset($_GET['favor_id']) ? $_GET['favor_id'] + 0: $favor_id;

$favorQuery = <<<EOQ
SELECT
	favors.*,
	from_character.character_name AS from_character_name,
	to_character.character_name AS to_character_name,
	favor_types.name AS favor_type_name
FROM
	favors
		LEFT JOIN wod_characters AS from_character ON favors.source_id = from_character.character_id
		LEFT JOIN wod_characters AS to_character ON favors.target_id = to_character.character_id
		LEFT JOIN favor_types ON favors.favor_type_id = favor_types.id
WHERE
	favors.favor_id = $favorId
	AND (
		from_character.primary_login_id = $userdata[user_id]
		OR to_character.primary_login_id = $userdata[user_id]
	)
EOQ;
$favorResult = mysql_query($favorQuery) or die(mysql_error());

if(mysql_num_rows($favorResult))
{
	$favorDetail = mysql_fetch_array($favorResult, MYSQLI_ASSOC) or die(mysql_error());
	
	$fromCharacter = $favorDetail['from_character_name'];
	$toCharacter = $favorDetail['to_character_name'];
	$favorType = $favorDetail['favor_type_name'];
	$description = $favorDetail['description'];
	$notes = str_replace("\r\n", "<br />", $favorDetail['notes']);
	$dateGiven = $favorDetail['date_given'];
	$status = "Open";
	if($favorDetail['is_broken'] != 0)
	{
		$status = "Broken";
	}
	if($favorDetail['date_discharged'] != null)
	{
		$status = "Discharged on $favorDetail[date_discharged]";
	}
}
else
{
	die("Unable to find favor.");
}
?>
<h2>View Favor</h2>

<div class="formInput">
	<label>From:</label>
	<?php echo $fromCharacter; ?>
</div>
<div class="formInput">
	<label>To:</label>
	<?php echo $toCharacter; ?>
</div>
<div class="formInput">
	<label>Status:</label>
	<?php echo $status; ?>
</div>
<div class="formInput">
	<label>Favor Type:</label>
	<?php echo $favorType; ?>
</div>
<div class="formInput">
	<label>Favor Description:</label>
	<?php echo $description; ?>
</div>
<div class="formInput">
	<label>Favor Notes:</label>
	<?php echo $notes; ?>
</div>
