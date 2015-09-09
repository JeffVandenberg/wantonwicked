<?php
use classes\core\helpers\Response;
use classes\core\repository\Database;

if ($_SERVER['REQUEST_METHOD'] != 'POST')
{
	Response::EndRequest('Illegal Action');
}

$territoryName = htmlspecialchars($_POST['territoryName']);
$characterId = $_POST['controllingCharacterId'] + 0;
$quality = $_POST['quality'] + 0;
$security = $_POST['security'] + 0;
$optimalPopulation = $_POST['optimalPopulation'] + 0;
$npcPopulation = $_POST['npcPopulation'] + 0;
$territoryNotes = htmlspecialchars($_POST['territoryNotes']);
$isOpen = (isset($_POST['isOpen'])) ? 1 : 0;

$query = <<<EOQ
INSERT INTO
	territories
	(
		territory_name,
		territory_type_id,
		character_id,
		optimal_population,
		npc_population,
		quality,
		created_by,
		created_on,
		territory_notes,
		is_open
	)
VALUES
	(
		'$territoryName',
		1,
		$characterId,
		$optimalPopulation,
		$npcPopulation,
		$quality,
		$userdata[user_id],
		now(),
		'$territoryNotes',
		$isOpen
	)
EOQ;

if(Database::GetInstance()->Query($query)->Execute())
{
	$page_content = "Successfully created territory.";
}
else
{
	$page_content = "There was an error creating the territory.";
}
