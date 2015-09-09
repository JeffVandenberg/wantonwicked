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
		created_on,
		updated_by,
		updated_on
	)
VALUES
	(
		$characterId,
		$territoryId,
		1,
		1,
		$userdata[user_id],
		now(),
		$userdata[user_id],
		'$oneWeekInFuture'
	)
EOQ;

if(Database::GetInstance()->Query($sql)->Execute())
{
	$message = "You are now poaching for the next week.";
	
	$domain = new Domain($territoryId);
	if($domain->IsCharacterCaughtPoaching($characterId))
	{
		$domain->AddNoteToDomainHolder($characterId);
	}
	
	$abp = new ABP();
	$abp->UpdateABP($characterId);

	echo $message;
}
else
{
	echo "There was an error adding you to the domain. Try again later.";
}
