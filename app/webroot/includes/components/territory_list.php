<?php
function CreateTerritoryList($territoryResult, $mayEdit = false)
{
	if($territoryResult === null)
	{
		return "No territories.";
	}
	
	$territoryList = "";
	if($mayEdit)
	{
		$territoryList .= <<<EOQ
<div class="paragraph">
	<a href="#" onclick="return createTerritory();">Create Territory</a>
</div>
EOQ;
	}
	
	$territoryList .= <<<EOQ
<div class="tableRowHeader" style="width:770px;">
	<div class="tableRowHeaderCell firstCell cell" style="width:150px;">
		Territory Name
	</div>
	<div class="tableRowHeaderCell cell" style="width:60px;">
		Type
	</div>
	<div class="tableRowHeaderCell cell" style="width:160px;">
		Held By
	</div>
	<div class="tableRowHeaderCell cell" style="width:30px;">
		PCs
	</div>
	<div class="tableRowHeaderCell cell" style="width:30px;">
		NPCs
	</div>
	<div class="tableRowHeaderCell cell" style="width:30px;">
		Q.
	</div>
	<div class="tableRowHeaderCell cell" style="width:30px;">
		C.Q.
	</div>
	<div class="tableRowHeaderCell cell" style="width:30px;">
		S.
	</div>
	<div class="tableRowHeaderCell cell" style="width:70px;">
		O. P.
	</div>
	<div class="tableRowHeaderCell cell" style="width:120px;">
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
			
			$territoryList .= <<<EOQ
<div class="tableRow$rowAlt" style="clear:both;width:770px;" id="territoryRow$territoryDetail[id]">
	<div class="firstCell cell" style="width:120px;">
		$territoryDetail[territory_name]
	</div>
	<div class="cell" style="width:60px;">
		Domain
	</div>
	<div class="cell" style="width:160px;">
		$territoryDetail[character_name]
	</div>
	<div class="cell" style="width:30px;">
		$territoryDetail[pc_count]
	</div>
	<div class="cell" style="width:30px;">
		$territoryDetail[npc_population]
	</div>
	<div class="cell" style="width:30px;">
		$territoryDetail[quality]
	</div>
	<div class="cell" style="width:30px;">
		$territoryDetail[current_quality]
	</div>
	<div class="cell" style="width:30px;">
		$territoryDetail[security]
	</div>
	<div class="cell" style="width:70px;">
		$territoryDetail[optimal_population]
	</div>
	<div class="cell" style="width:150px;">
		<a href="#" onclick="return viewTerritory($territoryDetail[id]);">View</a>
		<a href="/territory.php?action=edit&id=$territoryDetail[id]">Manage</a>
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