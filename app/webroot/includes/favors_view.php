<?php
// get character id
use classes\core\helpers\Request;
use classes\core\helpers\Response;
use classes\core\repository\Database;

$favorId = Request::getValue('favor_id', 0);

$favorQuery = <<<EOQ
SELECT
	favors.*,
	from_character.character_name AS from_character_name,
	to_character.character_name AS to_character_name,
	favor_types.name AS favor_type_name
FROM
	favors
		LEFT JOIN characters AS from_character ON favors.source_id = from_character.id
		LEFT JOIN characters AS to_character ON favors.target_id = to_character.id
		LEFT JOIN favor_types ON favors.favor_type_id = favor_types.id
WHERE
	favors.favor_id = $favorId
	AND (
		from_character.user_id = $userdata[user_id]
		OR to_character.user_id = $userdata[user_id]
	)
EOQ;
$params = array(
    $favorId,
    $userdata['user_id'],
    $userdata['user_id']
);
$favorDetail = Database::getInstance()->query($favorQuery)->single($params);

if ($favorDetail) {
    $fromCharacter = $favorDetail['from_character_name'];
    $toCharacter = $favorDetail['to_character_name'];
    $favorType = $favorDetail['favor_type_name'];
    $description = $favorDetail['description'];
    $notes = str_replace("\r\n", "<br />", $favorDetail['notes']);
    $dateGiven = $favorDetail['date_given'];
    $status = "Open";
    if ($favorDetail['is_broken'] != 0) {
        $status = "Broken";
    }
    if ($favorDetail['date_discharged'] !== null) {
        $status = "Discharged on $favorDetail[date_discharged]";
    }
} else {
    Response::endRequest('Unable to find favor');
}
?>
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
