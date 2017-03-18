<?php
$id = $_GET['id'] + 0;
?>

<h2>Add a Character to Territory</h2>
<form id="addCharacterForm">
<div class="formInput">
	<label>Character Name:</label>
	<input type="text" name="characterName" id="characterName" value="" />
	<input type="hidden" name="characterId" id="characterId" value="" />
	<input type="hidden" name="territoryId" id="territoryId" value="<?php echo $id; ?>
</div>
<div class="formInput">
	<input type="button" name="formSubmit" id="formSubmit" value="Add Character To Territory" />
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
			if($.trim($('#characterId').val()) == '')
			{
				errors += " - Select a character to add to the territory.\r\n";
			}
			
			if(errors == '')
			{
				$.ajax({
					url: "/territory.php?action=admin_add_character_post",
					data: $('#addCharacterForm').serialize(),
					type: "post",
					dataType: "html",
					success: function(response, status, request) {
						alert(response);
						RefreshAdminTerritoryCharacterList(<?php echo $id; ?>);
						$("#territoryPane").css("display", "none");
					},
					error: function(request, message, exception) {
						alert('There was an error submitting the request. Please try again.');
					}
				});
			}
			else
			{
				alert('Please correct the following errors: \r\n' + errors);
			}
		});
		$('#characterName').autocomplete({
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
				$('#characterId').val(ui.item.id);
			}
		});
	});
</script>
