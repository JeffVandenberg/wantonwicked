<?php
$favorId = isset($_POST['favorId']) ? $_POST['favorId'] + 0 : 0;
$favorId = isset($_GET['favorId']) ? $_GET['favorId'] + 0: $favorId;

$transferCharacterId = $_POST['transferCharacterId'] + 0;

$transactionQuery = "begin;";
$transactionResult = mysql_query($transactionQuery) or die(mysql_error());

$transferQuery = <<<EOQ
INSERT INTO 
	favors
(
	source_id,
	source_type_id,
	target_id,
	target_type_id,
	parent_favor_id,
	favor_type_id,
	description,
	notes,
	date_given,
	date_created
)
SELECT
	source_id,
	source_type_id,
	$transferCharacterId,
	1,
	favor_id,
	favor_type_id,
	description,
	notes,
	date_given,
	now()
FROM
	favors
WHERE
	favor_id = $favorId
EOQ;
$transferResult = mysql_query($transferQuery) or die(rollback());

if(mysql_affected_rows())
{
	$updateQuery = <<<EOQ
UPDATE
	favors
SET
	date_discharged = now()
WHERE 
	favor_id = $favorId
EOQ;
	$updateResult = mysql_query($updateQuery) or die(rollback());
	
	if(mysql_affected_rows())
	{
		$message = "Successfully Transferred Favor.";
	}
	else
	{
		$message = "There was an error cancelling your favor. Please try again later.";
	}
}
else
{
	$message = "There was an error. Please try again later.";
}

$transactionQuery = "commit;";
$transactionResult = mysql_query($transactionQuery) or die(mysql_error());

function rollBack()
{
	$message = mysql_error();
	$query = "rollback;";
	$result = mysql_query($query);
	return $message;
}
?>

<?php echo $message ?>