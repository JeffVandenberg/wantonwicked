<?php
function CreateTerritoryAssociatedCharacters($id, $mayEdit = false, $showPoachers = true)
{
	if(!$id)
	{
		return "None";
	}
	
	$query = <<<EOQ
SELECT
	CT.id,
	CT.character_id,
	C.character_name,
	CT.is_poaching,
	CT.created_on
FROM
	characters_territories as CT
	LEFT JOIN wod_characters as C ON CT.character_id = C.character_id
WHERE
	CT.territory_id = $id
	AND CT.is_active = 1
	AND (CT.updated_on IS NULL OR CT.updated_on > NOW())
	AND C.is_sanctioned = 'Y'
	AND C.is_deleted = 'N'
ORDER BY
	is_poaching,
	character_name
EOQ;

	$result = ExecuteQuery($query);
	
	$characterList = "";
	
	if(mysql_num_rows($result))
	{
		
		while($detail = mysql_fetch_array($result, MYSQL_ASSOC))
		{
			if(!$detail['is_poaching'] || ($showPoachers && ($detail['is_poaching'] == 1)))
			{
				$characterList .= <<<EOQ
<div style="width:250px;">
$detail[character_name] &nbsp;&nbsp;
EOQ;
				if($detail['is_poaching'] == 1)
				{
					$characterList .= " *Poaching* ";
				}
				
				if($mayEdit)
				{
					$characterNameWithSlashes = addslashes($detail['character_name']);
					$characterList .= <<<EOQ
<a href="#" onclick="return adminRemoveCharacterFromTerritory($detail[id], $id, '$characterNameWithSlashes');">Remove</a>
EOQ;
				}
			}
			else
			{
			}
			
			$characterList .= "</div>";
		}
		
	}
	
	if($characterList == "")
	{
		$characterList = "No associated characters.";
	}
	return $characterList;
}
?>