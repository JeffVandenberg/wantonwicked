<?php
use classes\core\repository\Database;

include_once ROOT_PATH . '/includes/classes/tenacy/abp/abp_modifier.php';

class ABP
{
	public function GetABP($characterId)
	{
		$abp = $this->GetBaseABP($characterId);
		$abp += $this->GetExtraDomains($characterId);
		$abp += $this->ApplyRules($characterId);
		$abp += $this->GetSheetModifier($characterId);
		$abp += $this->GetHumanityModifier($characterId);
		
		return $abp;
	}
	
	public function GetBaseABP($characterId)
	{
		$sql = <<<EOQ
SELECT
	MAX(current_quality) as max_quality
FROM
	territories AS T
	LEFT JOIN characters_territories AS CT ON T.id = CT.territory_id
WHERE
	T.is_active = 1
	AND CT.character_id = $characterId
	AND CT.is_active = 1
	AND (CT.updated_on IS NULL OR CT.updated_on > NOW())
EOQ;

		$params = array($characterId);
		$abp = Database::getInstance()->query($sql)->value($params);

		if($abp < 0)
		{
			$abp = 0;
		}
		
		return $abp;
	}
	
	public function GetExtraDomains($characterId)
	{
		$sql = <<<EOQ
SELECT
	COUNT(*) - 1 AS extra_domains
FROM
	characters_territories AS CT 
	LEFT JOIN territories as T ON CT.territory_id = T.id
WHERE
	CT.is_active = 1
	AND T.current_quality > 0
	AND CT.character_id = $characterId
	AND (
		CT.updated_on IS NULL
		OR CT.updated_on > now()
	)
EOQ;
		$params = array($characterId);
		$extraDomains = Database::getInstance()->query($sql)->value($params);

		if($extraDomains < 0)
		{
			$extraDomains = 0;
		}
		if($extraDomains > 2)
		{
			$extraDomains = 2;
		}
		return $extraDomains;
	}
	
	public function GetOtherModifiers($characterId)
	{
		$rulesSql = <<<EOQ
SELECT
	*
FROM
	territory_rules AS TR
WHERE
	territory_type_id = 1
	AND is_active = 1
EOQ;

		$rules = Database::getInstance()->query($rulesSql)->all();
		$modifiers = array();

		foreach($rules as $rulesDetail) {
			$modifierSql = <<<EOQ
SELECT
	power_level AS power_level
FROM
	character_powers
WHERE
	character_id = ?
	AND power_type = ?
	AND power_name LIKE ?
	AND power_note LIKE ?
EOQ;
			$params = array(
				$characterId,
				$rulesDetail['power_type'],
				$rulesDetail['power_name'] . '%',
				$rulesDetail['power_note'] . '%'
			);

			$power = Database::getInstance()->query($modifierSql)->single($params);

			if($power) {
				$value = $rulesDetail['multiplier'] * $power['power_level'] + $rulesDetail['modifier'];
				$modifiers[] = new ABPModifier($rulesDetail['power_name'] . ' ' . $rulesDetail['power_note'], $value);
			}
		}
		
		return $modifiers;
	}
	
	public function GetSheetModifier($characterId)
	{
		$sql = <<<EOQ
SELECT
	power_points_modifier
FROM
	characters
WHERE
	id = ?
EOQ;

		$params = array(
			$characterId
		);
		return Database::getInstance()->query($sql)->all($params);
	}
	
	public function GetHumanityModifier($characterId) 
	{
		$sql = <<<EOQ
SELECT
	Morality
FROM
	characters
WHERE
	id = $characterId;
EOQ;
		$params = array(
			$characterId
		);


		$modifier = (Database::getInstance()->query($sql)->value($params) - 7) / 2;
		
		if($modifier > 0)
		{
			$modifier = floor($modifier);
		}
		else
		{
			$modifier = ceil($modifier);
		}
		return $modifier;
	}
	
	public function UpdateABP($characterId)
	{
		$currentAbp = $this->GetABP($characterId);
		$sql = <<<EOQ
UPDATE
	characters AS C
SET
	average_power_points = ?
WHERE
	id = ?
EOQ;
        $params = array(
            $currentAbp,
            $characterId
        );

        Database::getInstance()->query($sql)->execute($params);
	}
	
	public function UpdateAllABP()
	{
		$sql = <<<EOQ
SELECT
	character_id
FROM
	characters
WHERE
	character_type = 'Vampire'
	AND is_sanctioned = 'Y'
	AND is_deleted = 'N'
EOQ;
		foreach(Database::getInstance()->query($sql)->all() as $detail) {
			$this->UpdateABP($detail['character_id']);
		}
	}
	
	public function NightlyUpdateABP()
	{
		$yesterday = date('Y-m-d', strtotime('-1 day'));
		
		$sql = <<<EOQ
SELECT
	character_id
FROM
	characters_territories AS CT
WHERE
	is_active = 1
	AND is_poaching = 1
	AND updated_on >= ?
	AND updated_on < NOW()
EOQ;
		$params = array($yesterday);

		foreach(Database::getInstance()->query($sql)->all($params) as $detail) {
			$this->UpdateABP($detail['character_id']);
		}
	}
	
	public function AdjustCurrentBlood()
	{
		$sql = <<<EOQ
UPDATE 
	characters AS C
SET
	power_points = power_points + IF(CEILING(average_power_points/5) > (average_power_points - power_points), average_power_points - power_points, CEILING(average_power_points/5))
WHERE
	power_points < average_power_points
	AND C.is_sanctioned = 'Y'
	AND C.character_type = 'Vampire'
	AND C.city = 'Savannah';
EOQ;
		Database::getInstance()->query($sql)->execute();

		$sql = <<<EOQ
UPDATE 
	characters AS C
SET
	power_points = power_points - 1
WHERE
	power_points > average_power_points
	AND C.is_sanctioned = 'Y'
	AND C.character_type = 'Vampire'
	AND C.city = 'Savannah';
EOQ;
		Database::getInstance()->query($sql)->execute();
	}

	private function ApplyRules($characterId)
	{
		$extraABP = 0;
		$list = $this->GetOtherModifiers($characterId);

		foreach($list as $item)
		{
			$extraABP += $item->GetModifierValue();
		}

		if($extraABP >= 0)
		{
			$extraABP = floor($extraABP);
		}
		else
		{
			$extraABP = ceil($extraABP);
		}
		return $extraABP;
	}
}
