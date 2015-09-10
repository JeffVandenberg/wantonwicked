<?php
use classes\territory\Territory;

$id = (isset($_GET['id'])) ? $_GET['id'] + 0 : 0;

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
	$maxQuality = $detail['max_quality'];
	$quality = $detail['quality'];
	$currentQuality = $detail['current_quality'];
	$security = $detail['security'];
	$optimalPopulation = $detail['optimal_population'];
	$npcPopulation = $detail['npc_population'];
	$territoryNotes = $detail['territory_notes'];
	$isOpenChecked = ($detail['is_open']) ? 'checked' : '';
	
	$associatedCharacters = Territory::CreateTerritoryAssociatedCharacters($id, true);
	
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
<a href="/territory.php?action=list">Return to List</a><br />
<h2>Manage Territory: $territoryName</h2>
<div style="width:500px;float:left;">
<form id="territoryForm">
<div style="float:left;width:200px;">
	<div class="formInput">
		<label>Territory Name:</label>
		<input type="text" name="territoryName" id="territoryName" value="$territoryName" />
		<input type="hidden" name="id" value="$id" />
	</div>
	<div class="formInput">
		<label>Controlling Character:</label>
		<input type="text" name="controllingCharacterName" id="controllingCharacterName" value="$characterName" />
		<input type="hidden" name="controllingCharacterId" id="controllingCharacterId" value="$characterId" />
	</div>
	<div class="formInput">
		<label>Max Quality:</label>
		<input type="text" name="maxQuality" id="max-quality" value="$maxQuality"><br />
	</div>
	<div class="formInput">
		<label>Quality:</label>
		<input type="text" name="quality" id="quality" value="$quality"><br />
	</div>
	<div class="formInput">
		<label>Current Quality:</label>
		$currentQuality<br />
	</div>
	<div class="formInput">
		<label>Security:</label>
		<input type="text" name="security" id="security" value="$security"><br />
	</div>
</div>
<div style="float:left;width:200px;">
	<div class="formInput">
		<label>Optimal Population:</label>
		<input type="text" name="optimalPopulation" id="optimalPopulation" value="$optimalPopulation"><br />
	</div>
	<div class="formInput">
		<label>NPC Population:</label>
		<input type="text" name="npcPopulation" id="npcPopulation" value="$npcPopulation"><br />
	</div>
	<div class="formInput">
		<label for="isOpen" style="display:inline;">Open Feeding:</label>
		<input type="checkbox" name="isOpen" id="isOpen" value="1" $isOpenChecked/>
	</div>
</div>
<div class="formInput firstCell">
	<label>Territory Notes:</label>
	<textarea name="territoryNotes" rows="5" cols="50">$territoryNotes</textarea>
</div>
<div class="formInput">
	<input type="button" name="formSubmit" id="formSubmit" value="Update Territory" />
</div>
</form>
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
		$('#formSubmit').click(function(){
			var errors = '';
			if($.trim($('#controllingCharacterId').val()) == '')
			{
				errors += " - Select a character to control the territory.\\r\\n";
			}
			if($.trim($('#territoryName').val()) == '')
			{
				errors += ' - Provide a name of the Territory.\\r\\n';
			}
			
			if(errors == '')
			{
				$.ajax({
					url: "/territory.php?action=edit_post",
					data: $('#territoryForm').serialize(),
					type: "post",
					dataType: "html",
					success: function(response, status, request) {
						alert(response);
					},
					error: function(request, message, exception) {
						alert('There was an error submitting the request. Please try again.');
					}
				});
			}
			else
			{
				alert('Please correct the following errors: \\r\\n' + errors);
			}
		});
		$('#controllingCharacterName').autocomplete({
			source: function(request, response){
				$.ajax({
					url: "/characters.php?action=quick_search",
					type: "post", 
					dataType: "json",
					data: {
						term: request.term,
						maxResults: 20
					},
					success: function(data){
						response($.map(data, function(item){
							return {
								name: item.characterName,
								value: item.characterName,
								id: item.id
							}
						}))
					}
				});
			},
			minLength: 2,
			select: function(event, ui){
				$('#controllingCharacterId').val(ui.item.id);
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