<?php
class Domain
{
	private $territoryId;
	public function Domain($id = 0)
	{
		$this->territoryId = $id;
	}
	
	public function IsCharacterCaughtPoaching($characterId)
	{
		$twelveWeeksAgo = date('Y-m-d', strtotime("-12 weeks"));
		
		$sql = <<<EOQ
SELECT
	COUNT(*) AS poaching_attempts,
	(
		SELECT
			survival
		FROM
			characters
		WHERE
			character_id = $characterId
	) AS survival,
	(
		SELECT
			security
		FROM
			territories
		WHERE
			id = $this->territoryId
	) AS security
FROM
	characters_territories AS CT
WHERE
	CT.character_id = $characterId
	AND CT.territory_id = $this->territoryId
	AND CT.is_active = 1
	AND CT.is_poaching = 1
	AND CT.updated_on > $twelveWeeksAgo;
EOQ;

		$attempts = 0;
		$security = 0;
		$survival = 0;
		
		$result = ExecuteQuery($sql);
		
		while($detail = mysql_fetch_array($result, MYSQL_ASSOC))
		{
			$attempts = $detail['poaching_attempts'];
			$security = $detail['security'];
			$survival = $detail['survival'];
		}
		
		$isCaught = ($attempts > ($survival - $security + 1));
		
		return $isCaught;
	}

	public function AddNoteToDomainHolder($characterId)
	{
		$domainHolderId = 0;
		$sql = <<<EOQ
SELECT
	character_id,
	territory_name
FROM
	territories 
WHERE
	id = $this->territoryId;
EOQ;

		$result = ExecuteQuery($sql);
		
		while($detail = mysql_fetch_array($result, MYSQL_ASSOC))
		{
			$poachingCharacterSql = "SELECT character_name FROM characters WHERE character_id = $characterId;";
			$poachingCharacterResult = ExecuteQuery($poachingCharacterSql);
			$poachingCharacter = mysql_fetch_array($poachingCharacterResult, MYSQL_ASSOC);
			
			$characterName = addslashes($poachingCharacter['character_name']);
			$territoryName = addslashes($detail['territory_name']);
			$noteSql = <<<EOQ
UPDATE
	characters
SET
	login_note = '$characterName was caught poaching in $territoryName.'
WHERE
	character_id = $detail[character_id];
EOQ;
			$noteResult = ExecuteNonQuery($noteSql);
		}
	}

	public function GetCharactersInDomains()
	{
		$sql = <<<EOQ
SELECT
	C.character_id,
	C.character_name,
	CT.is_poaching,
	T.territory_name,
	T.id
FROM
	characters AS C
	LEFT JOIN characters_territories AS CT on CT.character_id = C.character_id
	LEFT JOIN territories AS T ON T.id = CT.territory_id
WHERE
	T.is_active = 1
	AND CT.is_active = 1
	AND (CT.updated_on IS NULL OR CT.updated_on > NOW())
	AND C.is_sanctioned = 'Y'
	AND C.city = 'San Diego'
	AND C.is_deleted = 'N'
ORDER BY
	C.character_name,
	T.territory_name
EOQ;

		return ExecuteQuery($sql);
	}
}