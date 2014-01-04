<?php
$id = (isset($_GET['id'])) ? $_GET['id'] + 0 : 0;
$characterId = (isset($_GET['character_id'])) ? $_GET['character_id'] + 0 : 0;

include 'includes/components/territory_associated_characters.php';
include 'includes/helpers/get_number_of_leeches.php';

$sql = <<<EOQ
SELECT
	T.*,
	C.character_name
FROM
	territories as T
	LEFT JOIN characters AS C on T.character_id = C.character_id
WHERE
	T.id = $id
	AND T.is_active = 1
EOQ;

$result = ExecuteQuery($sql);

if(mysql_num_rows($result))
{
	$detail = mysql_fetch_array($result, MYSQL_ASSOC);
	
	$page_title = "Territory: " . $detail['territory_name'];
	$territoryName = $detail['territory_name'];
	$characterId = $detail['character_id'];
	$characterName = $detail['character_name'];
	$quality = $detail['quality'];
	$security = $detail['security'];
	$optimalPopulation = $detail['optimal_population'];
	$npcPopulation = $detail['npc_population'];
	$territoryNotes = str_replace("\r\n", "<br />", $detail['territory_notes']);
	
	$numberOfLeeches = GetNumberOfLeeches($id);
	
	$qualityModifier = 0;
	if(($numberOfLeeches + $npcPopulation) > $optimalPopulation)
	{
		$qualityModifier = $numberOfLeeches + $npcPopulation - $optimalPopulation;
	}
	
	$currentQuality = $quality - $qualityModifier;
	
	$associatedCharacters = CreateTerritoryAssociatedCharacters($id, true, false);
	
	$page_content = <<<EOQ
<div id="territoryPane" style="display:none;">
	<div id="territoryPaneClose">
		Close
	</div>
	<div id="territoryPaneContent">
		Territory Pane
	</div>
</div>
<div style="overflow:auto;">
<a href="/territory.php?action=list_territories&character_id=$characterId">Return to List</a><br />
<h2>Manage Territory: $territoryName</h2>
<div style="width:65%;float:left;">
<form id="territoryForm">
<div class="formInput">
	<label>Territory Name:</label>
	$territoryName
</div>
<div class="formInput">
	<label>Controlling Character:</label>
	$characterName
</div>
<div class="formInput">
	<label>Quality:</label>
	$quality
</div>
<div class="formInput">
	<label>Current Quality:</label>
	$currentQuality
</div>
<div class="formInput">
	<label>Security:</label>
	$security
</div>
<div class="formInput">
	<label>Optimal Population:</label>
	$optimalPopulation
</div>
<div class="formInput">
	<label>NPC Population:</label>
	$npcPopulation
</div>
<div class="formInput">
	<label>Territory Notes:</label>
	$territoryNotes
</div>
</div>
<div style="width:35%;float:left;">
	<b>Associated Characters</b><br />
	<a href="#" onclick="return adminAddCharacterToTerritory($id);">Add Character</a><br />
	<div id="associatedCharacters">
		$associatedCharacters
	</div>
</div>
</div>
<script language="javascript">
	$(document).ready(function(){
		$("#territoryPaneClose").click(function(e){
			$("#territoryPane").css("display", "none");
		});
		$(document).keypress(function(e){
			if(e.keyCode == 27)
			{
				$("#territoryPane").css("display", "none");
			}
		});
		$(document).keydown(function(e){
			if(e.keyCode == 27)
			{
				$("#territoryPane").css("display", "none");
			}
		});
		$('input:text').keypress(function(e){
			if(e.keyCode == 13)
			{
				return false;
			}
		});
	});
</script>
EOQ;
}
else
{
	$page_title = "Unknown Territory";
	$page_content = "Unknown Territory";
}
?>