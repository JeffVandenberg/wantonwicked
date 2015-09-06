<?php
use classes\core\helpers\Response;

if ($_SERVER['REQUEST_METHOD'] != 'POST')
{
	Response::EndRequest('Illegal Action');
}

$territoryName = htmlspecialchars($_POST['territoryName']);
$characterId = $_POST['controllingCharacterId'] + 0;
$quality = $_POST['quality'] + 0;
$maxQuality = $_POST['maxQuality'] + 0;
$security = $_POST['security'] + 0;
$optimalPopulation = $_POST['optimalPopulation'] + 0;
$npcPopulation = $_POST['npcPopulation'] + 0;
$territoryNotes = htmlspecialchars($_POST['territoryNotes']);
$isOpen = (isset($_POST['isOpen'])) ? 1 : 0;

$id = $_POST['id'];

$query = <<<EOQ
UPDATE
	territories
SET
	territory_name = '$territoryName',
	character_id = $characterId,
	optimal_population = $optimalPopulation,
	npc_population = $npcPopulation,
	max_quality = $maxQuality,
	quality = $quality,
	security = $security,
	updated_by = $userdata[user_id],
	updated_on = now(),
	territory_notes = '$territoryNotes',
	is_open = $isOpen
WHERE
	id = $id
EOQ;

if(ExecuteNonQuery($query))
{
	$page_content = "Successfully updated the territory.";
}
else
{
	$page_content = "There was an error updating the territory.";
}
?>