<?php
use classes\core\repository\Database;

$message = "Nothing Posted";
	
	if(isset($_POST['targetCharacterId']))
	{
		$message = "Attempt to save.";
		// attempt to save favor
		$favorTypeId = $_POST['favorTypeId'] + 0;
		$sourceCharacterId = $_POST['sourceCharacterId'] + 0;
		$targetCharacterId = $_POST['targetCharacterId'] + 0;
		$description = htmlspecialchars($_POST['favorDescription']);
		$notes = htmlspecialchars($_POST['favorNotes']);
		$now = date('Y-m-d h:i:s');
		
		$createFavorQuery = <<<EOQ
INSERT INTO
	favors
	(
		source_id,
		source_type_id,
		target_id,
		target_type_id,
		favor_type_id,
		description,
		notes,
		date_given
	)
VALUES
	(
		$sourceCharacterId,
		1,
		$targetCharacterId,
		1,
		$favorTypeId,
		'$description',
		'$notes',
		'$now'
	)
EOQ;

		$rows = Database::GetInstance()->Query($createFavorQuery)->Execute();

		if($rows)
		{
			$message = "Successfully added favor.";
		}
	}
?>

<?php echo $message; ?>