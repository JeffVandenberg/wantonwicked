<?php
use classes\core\repository\Database;

class Domain
{
	private $territoryId;
	public function __construct($id = 0)
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
			id = $characterId
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

		$params = array(
			$characterId,
			$this->territoryId,
			$twelveWeeksAgo
		);

		$detail = Database::getInstance()->query($sql)->single($params);

		if($detail)
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
		$sql = <<<EOQ
SELECT
	character_id,
	territory_name
FROM
	territories 
WHERE
	id = ?;
EOQ;
		$params = array($this->territoryId);
		$rows = Database::getInstance()->query($sql)->all($params);

		foreach($rows as $detail) {
			$poachingCharacterSql = "SELECT character_name FROM characters WHERE id = ?;";
			$params = array($characterId);
			$poachingCharacter = Database::getInstance()->query($poachingCharacterSql)->single($params);

			$characterName = addslashes($poachingCharacter['character_name']);
			$territoryName = addslashes($detail['territory_name']);
			$noteSql = <<<EOQ
UPDATE
	characters
SET
	login_note = ?
WHERE
	character_id = ?;
EOQ;
			$params = array(
				$characterName .' was caught poaching in ' .$territoryName .'.',
				$detail['character_id']
			);
			Database::getInstance()->query($noteSql)->execute($params);
		}
	}

	public function GetCharactersInDomains()
	{
		$sql = <<<EOQ
SELECT
	C.id,
	C.character_name,
	CT.is_poaching,
	T.territory_name,
	T.id
FROM
	characters AS C
	LEFT JOIN characters_territories AS CT on CT.character_id = C.id
	LEFT JOIN territories AS T ON T.id = CT.territory_id
WHERE
	T.is_active = 1
	AND CT.is_active = 1
	AND (CT.updated_on IS NULL OR CT.updated_on > NOW())
	AND C.is_sanctioned = 'Y'
	AND C.city = 'Savannah'
	AND C.is_deleted = 'N'
ORDER BY
	C.character_name,
	T.territory_name
EOQ;
		return Database::getInstance()->query($sql)->All();
	}
}
