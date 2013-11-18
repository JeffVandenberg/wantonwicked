<?php
$id = $_GET['id'] + 0;

$sql = <<<EOQ
UPDATE 
	characters_territories
SET
	is_active = 0,
	updated_by = $userdata[user_id],
	updated_on = now()
WHERE
	id = $id
EOQ;

if(ExecuteNonQuery($sql))
{
	echo "Successfully removed character";
	$sql = <<<EOQ
SELECT
	character_id
FROM
	characters_territories
WHERE
	id = $id
EOQ;
	
	$result = ExecuteQuery($sql);
	$detail = mysql_fetch_array($result, MYSQL_ASSOC);
	
	$abp = new ABP();
	$abp->UpdateABP($detail['character_id']);
}
else
{
	echo "Error removing character.";
}
?>