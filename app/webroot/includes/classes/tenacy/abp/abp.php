<?php
include_once 'includes/classes/tenacy/abp/abp_modifier.php';

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

		$result = ExecuteQuery($sql);
		
		$abp = 0;
		
		while($detail = mysql_fetch_array($result, MYSQL_ASSOC))
		{
			$abp = $detail['max_quality'];
		}
		
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
	
		$result = ExecuteQuery($sql);
		$detail = mysql_fetch_array($result, MYSQL_ASSOC);
		$extraDomains = $detail['extra_domains'];
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
		
		$rulesResult = ExecuteQuery($rulesSql);
		$modifiers = array();
		
		while($rulesDetail = mysql_fetch_array($rulesResult, MYSQL_ASSOC))
		{
			$powerName = addslashes($rulesDetail['power_name']);
			$powerNote = addslashes($rulesDetail['power_note']);
			
			$modifierSql = <<<EOQ
SELECT
	powerlevel AS power_level
FROM
	character_powers
WHERE
	characterid = $characterId
	AND powertype = '$rulesDetail[power_type]'
	AND powername LIKE '$powerName%'
	AND powernote LIKE '$powerNote%'
EOQ;

			$modifierResult = ExecuteQuery($modifierSql);
			
			while($modifierDetail = mysql_fetch_array($modifierResult, MYSQL_ASSOC))
			{
				$value = $rulesDetail['multiplier'] * $modifierDetail['power_level'] + $rulesDetail['modifier'];
				$modifiers[] = new ABPModifier($powerName . ' ' . $powerNote, $value);
			}
		}
		
		return $modifiers;
	}
	
	public function GetSheetModifier($characterId)
	{
		$sql = <<<EOQ
SELECT
	Power_Points_Modifier
FROM
	characters
WHERE
	character_id = $characterId;
EOQ;

		$result = ExecuteQueryData($sql);
		return $result['Power_Points_Modifier'];
	}
	
	public function GetHumanityModifier($characterId) 
	{
		$sql = <<<EOQ
SELECT
	Morality
FROM
	characters
WHERE
	character_id = $characterId;
EOQ;

		$result = ExecuteQueryData($sql);
		
		$modifier = ($result[0]['Morality'] - 7) / 2;
		
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
	average_power_points = $currentAbp
WHERE
	character_id = $characterId
EOQ;
		ExecuteNonQuery($sql);
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
		$result = ExecuteQuery($sql);
		
		while($detail = mysql_fetch_array($result, MYSQL_ASSOC))
		{
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
	AND updated_on >= '$yesterday'
	AND updated_on < NOW()
EOQ;
		$result = ExecuteQuery($sql);
		
		while($detail = mysql_fetch_array($result, MYSQL_ASSOC))
		{
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
	AND C.city = 'San Diego';
EOQ;
		ExecuteNonQuery($sql);

		$sql = <<<EOQ
UPDATE 
	characters AS C
SET
	power_points = power_points - 1
WHERE
	power_points > average_power_points
	AND C.is_sanctioned = 'Y'
	AND C.character_type = 'Vampire'
	AND C.city = 'San Diego';
EOQ;
		ExecuteNonQuery($sql);
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
?>