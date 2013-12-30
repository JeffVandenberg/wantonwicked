<?php
function CreateTerritoryListPublic($territoryResult, $characterId)
{
	if($territoryResult == null)
	{
		return "No territories.";
	}
	
	$territoryList = <<<EOQ
<div class="tableRowHeader" style="width:672px;">
	<div class="tableRowHeaderCell firstCell cell" style="width:180px;">
		Territory Name
	</div>
	<div class="tableRowHeaderCell cell" style="width:60px;">
		Type
	</div>
	<div class="tableRowHeaderCell cell" style="width:160px;">
		Held By
	</div>
	<div class="tableRowHeaderCell cell" style="width:50px;">
		Open
	</div>
	<div class="tableRowHeaderCell cell" style="width:50px;">
		Quality
	</div>
	<div class="tableRowHeaderCell cell" style="width:50px;">
		Security
	</div>
	<div class="tableRowHeaderCell cell" style="width:80px;">
		&nbsp;
	</div>
</div>
EOQ;

	$row = 0;
	if(mysql_num_rows($territoryResult))
	{
		while($territoryDetail = mysql_fetch_array($territoryResult, MYSQL_ASSOC))
		{
			$rowAlt = (($row++)%2) ? "Alt" : "";
			
			$isOpen = $territoryDetail['is_open'] ? 'Yes' : 'No';
			
			$links = "";
			
			if($characterId == $territoryDetail['character_id'])
			{
				$links .= <<<EOQ
 <a href="/territory.php?action=manage&id=$territoryDetail[id]&character_id=$characterId">Manage</a>
EOQ;
			}
			
			if(!$territoryDetail['in_territory'])
			{
				if($territoryDetail['is_open'])
				{
					$links .= <<<EOQ
 <a href="#" onclick="return feedFromTerritory($territoryDetail[id], $characterId, '$territoryDetail[territory_name]', this);">Feed</a>
EOQ;
				}
				else
				{
					$links .= <<<EOQ
 <a href="#" onclick="return poachTerritory($territoryDetail[id], $characterId, '$territoryDetail[territory_name]', this);">Poach</a>
EOQ;
				}
			}
			else
			{
				if(!$territoryDetail['is_poaching'])
				{
					$links .= <<<EOQ
<a href="#" onclick="return leaveTerritory($territoryDetail[character_territory_id], $territoryDetail[id], '$territoryDetail[territory_name]', this);">Leave</a>
EOQ;
				}
				else
				{
					$links .= "&nbsp;";
				}
			}
			
			$territoryList .= <<<EOQ
<div class="tableRow$rowAlt" style="clear:both;width:672px;" id="territoryRow$territoryDetail[id]">
	<div class="firstCell cell" style="width:180px;">
		$territoryDetail[territory_name]
	</div>
	<div class="cell" style="width:60px;">
		Domain
	</div>
	<div class="cell" style="width:160px;">
		$territoryDetail[character_name]
	</div>
	<div class="cell centeredText" style="width:50px;">
		$isOpen
	</div>
	<div class="cell centeredText" style="width:50px;">
		$territoryDetail[current_quality]
	</div>
	<div class="cell centeredText" style="width:50px;">
		$territoryDetail[security]
	</div>
	<div class="cell" style="width:80px;">
		$links
	</div>
</div>
EOQ;
		}
	}
	else
	{
		$territoryList .= <<<EOQ
<div style="clear:both;">
	No territories defined.
</div>
EOQ;
	}
	
	return $territoryList;
}
?>