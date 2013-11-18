<?php
function GetNumberOfLeeches($territoryId)
{
	$sql = <<<EOQ
SELECT
	COUNT(*) AS NumberOfLeeches
FROM
	territories AS T
	LEFT JOIN characters_territories AS CT ON T.id = CT.territory_id
WHERE
	T.id = $territoryId
	AND CT.is_active = 1
	AND (CT.updated_on IS NULL OR CT.updated_on > now())
EOQ;

	$result = ExecuteQuery($sql);

	$numberOfLeeches = 0;
	if(mysql_num_rows($result))
	{
		$detail = mysql_fetch_array($result, MYSQL_ASSOC);
		$numberOfLeeches = $detail['NumberOfLeeches'];
	}
	
	return $numberOfLeeches;

}
?>