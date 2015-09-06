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
$id = $_POST['id'] + 0;

$query = <<<EOQ
UPDATE
	territory_rules
SET
	rule_name = '$ruleName',
	power_type = '$power_type',
	power_name = '$powerName',
	power_note = '$powerNote',
	is_shared = $isShared,
	multiplier = $multiplier,
	modifier = $modifier,
	updated_by = $userdata[user_id],
	updated_on = now()
WHERE
	id = $id
EOQ;

if(ExecuteNonQuery($query))
{
	$page_content = "Successfully updated ABP rule.";
	$abp = new ABP();
	$abp->UpdateAllABP();
}
else
{
	$page_content = "There was an error updating the ABP rule.";
}
?>