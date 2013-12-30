<?php
class Character
{
    public function GetById($characterId)
	{
		$sql = <<<EOQ
SELECT
	character_name
FROM
	wod_characters
WHERE
	character_id = $characterId
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
	wod_characters_powers
WHERE
	CharacterID = $characterId
	AND PowerName = '$powerName'
	AND PowerLevel >= $powerLevel
EOQ;
		$result = ExecuteQuery($query);
		$detail = mysql_fetch_array($result, MYSQL_ASSOC);
		
		return ($detail['HitCount'] > 0);
	}
}
?>