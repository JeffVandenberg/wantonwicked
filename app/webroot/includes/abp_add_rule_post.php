<?php
use classes\core\helpers\Response;

if ($_SERVER['REQUEST_METHOD'] != 'POST')
{
	Response::EndRequest('Illegal Action');
}

$ruleName = htmlspecialchars($_POST['ruleName']);
$power_type = htmlspecialchars($_POST['power_type']);
$powerName = htmlspecialchars($_POST['powerName']);
$powerNote = htmlspecialchars($_POST['powerNote']);
$isShared = isset($_POST['isShared']) ? 1 : 0;
$multiplier = $_POST['multiplier'] + 0;
$modifier = $_POST['modifier'] + 0;

$query = <<<EOQ
INSERT INTO
	territory_rules
	(
		rule_name,
		territory_type_id,
		power_type,
		power_name,
		power_note,
		is_shared,
		multiplier,
		modifier,
		is_active,
		created_by,
		created_on
	)
VALUES
	(
		'$ruleName',
		1,
		'$power_type',
		'$powerName',
		'$powerNote',
		$isShared,
		$multiplier,
		$modifier,
		1,
		$userdata[user_id],
		now()
	)
EOQ;

if(ExecuteNonQuery($query))
{
	$page_content = "Successfully created ABP rule.";
	$abp = new ABP();
	$abp->UpdateAllABP();
}
else
{
	$page_content = "There was an error creating the ABP rule.";
}
?>