<?php
use classes\core\helpers\Request;
use classes\core\helpers\Response;
use classes\core\repository\Database;

/* @var array $userdata */
if ($_SERVER['REQUEST_METHOD'] != 'POST')
{
	Response::EndRequest('Illegal Action');
}

$ruleName = htmlspecialchars(Request::GetValue('ruleName'));
$power_type = htmlspecialchars(Request::GetValue('power_type'));
$powerName = htmlspecialchars(Request::GetValue('powerName'));
$powerNote = htmlspecialchars(Request::GetValue('powerNote'));
$isShared = (Request::GetValue('isShared')) ? 1 : 0;
$multiplier = Request::GetValue('multiplier');
$modifier = Request::GetValue('modifier');
$id = Request::GetValue('id');

$query = <<<EOQ
UPDATE
	territory_rules
SET
	rule_name = ?,
	power_type = ?,
	power_name = ?,
	power_note = ?,
	is_shared = ?,
	multiplier = ?,
	modifier = ?,
	updated_by = ?,
	updated_on = now()
WHERE
	id = ?
EOQ;

$params = array(
	$ruleName,
	$power_type,
	$powerName,
	$powerNote,
	$isShared,
	$multiplier,
	$modifier,
	$userdata['user_id'],
	$id
);

if(Database::GetInstance()->Query($query)->Execute($params))
{
	$page_content = "Successfully updated ABP rule.";
	$abp = new ABP();
	$abp->UpdateAllABP();
}
else
{
	$page_content = "There was an error updating the ABP rule.";
}
