<?php
use classes\core\helpers\Response;
use classes\core\repository\Database;

if ($_SERVER['REQUEST_METHOD'] != 'POST')
{
	Response::EndRequest('Illegal Action');
}

$characterId = $_POST['characterId'] + 0;
$territoryId = $_POST['territoryId'] + 0;

$sql = <<<EOQ
INSERT INTO
	characters_territories
	(
		character_id,
		territory_id,
		is_poaching,
		is_active,
		created_by,
		created_on
	)
VALUES
	(
		$characterId,
		$territoryId,
		0,
		1,
		$userdata[user_id],
		now()
	)
EOQ;


if(Database::GetInstance()->Query($sql)->Execute())
{
	echo "Successfully added character to territory.";
}
else
{
	echo "Unable to add character to territory.";
}
