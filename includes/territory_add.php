<?php
$page_content = <<<EOQ
<h2>Create Territory</h2>

<form id="createTerritoryForm">
<div style="float:left;width:200px;">
	<div class="formInput">
		<label>Territory Name:</label>
		<input type="text" name="territoryName" id="territoryName" value="" />
	</div>
	<div class="formInput">
		<label>Controlling Character:</label>
		<input type="text" name="controllingCharacterName" id="controllingCharacterName" value="" />
		<input type="hidden" name="controllingCharacterId" id="controllingCharacterId" value="" />
	</div>
	<div class="formInput">
		<label>Quality:</label>
		<input type="text" name="quality" id="quality" value="0"><br />
	</div>
	<div class="formInput">
		<label>Security:</label>
		<input type="text" name="security" id="security" value="0"><br />
	</div>
</div>
<div style="float:left;width:200px;">
	<div class="formInput">
		<label>Optimal Population:</label>
		<input type="text" name="optimalPopulation" id="optimalPopulation" value="0"><br />
	</div>
	<div class="formInput">
		<label>NPC Population:</label>
		<input type="text" name="npcPopulation" id="npcPopulation" value="0"><br />
	</div>
	<div class="formInput">
		<label for="isOpen" style="display:inline;">Open Feeding:</label>
		<input type="checkbox" name="isOpen" id="isOpen" value="1" />
	</div>
</div>
<div class="formInput" style="clear:both;">
	<label>Territory Notes:</label>
	<textarea name="territoryNotes" rows="5" cols="50">$favorNotes</textarea>
</div>
<div class="formInput">
	<input type="button" name="formSubmit" id="formSubmit" value="Create Territory" />
</div>
</form>
<script language="javascript">
	$(document).ready(function(){
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
					url: "/territory.php?action=add_post",
					data: $('#createTerritoryForm').serialize(),
					type: "post",
					dataType: "html",
					success: function(response, status, request) {
						alert(response);
						window.location.reload();
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
?>