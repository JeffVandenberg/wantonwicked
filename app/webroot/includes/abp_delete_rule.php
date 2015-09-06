<?php
use classes\core\helpers\Response;

if ($_SERVER['REQUEST_METHOD'] != 'POST')
{
	Response::EndRequest('Illegal Action');
}

$id = $_POST['id'] + 0;

$sql = <<<EOQ
UPDATE
	territory_rules
SET
	is_active = 0
WHERE
	id = $id
EOQ;

if(ExecuteNonQuery($sql))
{
	echo "The rule has been removed.";
	$abp = new ABP();
	$abp->UpdateAllABP();
}
else
{
	echo "There was an error updating the rule.";
}