<?php
use classes\core\repository\Database;

$territoryId = $_GET['id'] + 0;
$characterId = $_GET['character_id'] + 0;
$oneWeekInFuture = date('Y-m-d', strtotime("+7 days"));
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

if(Database::getInstance()->query($sql)->execute())
{
	$message = "You are now feeding from the domain.";
	echo $message;
}
else
{
	echo "There was an error adding you to the domain. Try again later.";
}
