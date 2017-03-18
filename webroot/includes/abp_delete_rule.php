<?php
use classes\core\helpers\Request;
use classes\core\helpers\Response;
use classes\core\repository\Database;

if ($_SERVER['REQUEST_METHOD'] != 'POST')
{
	Response::endRequest('Illegal Action');
}

$id = Request::getValue('id');

$sql = <<<EOQ
UPDATE
	territory_rules
SET
	is_active = 0
WHERE
	id = ?
EOQ;

$params = array(
	$id
);
if(Database::getInstance()->query($sql)->execute($params))
{
	echo "The rule has been removed.";
	$abp = new ABP();
	$abp->UpdateAllABP();
}
else
{
	echo "There was an error updating the rule.";
}