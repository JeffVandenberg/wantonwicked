<?php
use classes\core\repository\Database;

class TerritoryRepository
{
	public function UpdateAll()
	{
		$territories = $this->GetTerritoriesByActive(true);
		foreach($territories as $territory)
		{
			$this->UpdateCurrentQualityForTerritory($territory);
		}
	}
	
	public function GetTerritoriesByActive($isActive)
	{
		$sql = <<<EOQ
SELECT
	*
FROM
	territories
WHERE
	is_active = ?
EOQ;
		$params = array(
			$isActive
		);

		return Database::GetInstance()->Query($sql)->All($params);
	}
	
	public function UpdateCurrentQualityForTerritory(&$territory)
	{
		$quality = $territory['quality'];
		$quality -= $this->GetPopulationModifier($territory['id'], $territory['optimal_population']);
		if($quality < 0)
		{
			$quality = 0;
		}
		$updateSql = <<<EOQ
UPDATE
	territories
SET
	current_quality = $quality
WHERE
	id = ?
EOQ;
		$params = array(
			$territory['id']
		);

		Database::GetInstance()->Query($updateSql)->Execute($params);
		$territory['current_quality'] = $quality;
	}
	
	public function GetPopulationModifier($territoryId, $optimalPopulation)
	{
		$currentPopulationSql = <<<EOQ
SELECT
	COUNT(*) as number_of_characters
FROM
	characters_territories AS CT
	LEFT JOIN characters as C ON CT.character_id = C.character_id
WHERE
	C.is_sanctioned = 'Y'
	AND C.is_deleted = 'N'
	and CT.territory_id = ?
	AND (CT.updated_on IS NULL OR CT.updated_on > NOW())
EOQ;
		$params = array(
			$territoryId
		);

		$currentPopulationDetail = Database::GetInstance()
			->Query($currentPopulationSql)
			->Single($params);
		$currentPopulation = $currentPopulationDetail['number_of_characters'];
		$currentPopulationModifier = 0;
		if($currentPopulation > 0)
		{
			$currentPopulationModifier = ceil(($currentPopulation - $optimalPopulation)/3);
		}
		if($currentPopulationModifier < 0)
		{
			$currentPopulationModifier = 0;
		}
		return $currentPopulationModifier;
	}
}
