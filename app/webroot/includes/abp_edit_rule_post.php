<?php
use classes\core\helpers\Request;
use classes\core\helpers\Response;
use classes\core\repository\Database;

/* @var array $userdata */
if ($_SERVER['REQUEST_METHOD'] != 'POST')
{
	Response::endRequest('Illegal Action');
}

$ruleName = htmlspecialchars(Request::getValue('ruleName'));
$power_type = htmlspecialchars(Request::getValue('power_type'));
$powerName = htmlspecialchars(Request::getValue('powerName'));
$powerNote = htmlspecialchars(Request::getValue('powerNote'));
$isShared = (Request::getValue('isShared')) ? 1 : 0;
$multiplier = Request::getValue('multiplier');
$modifier = Request::getValue('modifier');
$id = Request::getValue('id');

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

if(Database::getInstance()->query($query)->execute($params))
{
	$page_content = "Successfully updated ABP rule.";
	$abp = new ABP();
	$abp->UpdateAllABP();
}
else
{
	$page_content = "There was an error updating the ABP rule.";
}
