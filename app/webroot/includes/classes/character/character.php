<?php
class Character
{
    public function GetById($characterId)
	{
		$sql = <<<EOQ
SELECT
	character_name
FROM
	characters
WHERE
	id = $characterId
EOQ;

		$result = ExecuteQuery($sql);
		return mysql_fetch_array($result, MYSQL_ASSOC);
	}
	
	public function LoadCharacter($characterId)
	{
		// load character into classes
	}
	
	public function DoesCharacterHavePowerAtLevel($characterId, $powerName, $powerLevel)
	{
		$query = <<<EOQ
SELECT
	count(*) as HitCount
FROM
	character_powers
WHERE
	character_id = $characterId
	AND power_name = '$powerName'
	AND power_level >= $powerLevel
EOQ;
		$result = ExecuteQuery($query);
		$detail = mysql_fetch_array($result, MYSQL_ASSOC);
		
		return ($detail['HitCount'] > 0);
	}
}