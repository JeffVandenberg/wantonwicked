<?php
// get character id
$character_id = isset($_POST['character_id']) ? $_POST['character_id'] + 0 : 0;
$character_id = isset($_GET['character_id']) ? $_GET['character_id'] + 0: $character_id;

$character_query = <<<EOQ
SELECT wod.*, Character_Name, l.Name
FROM (characters as wod INNER JOIN login_character_index as lci ON wod.character_id = lci.character_id) INNER JOIN login as l on lci.login_id = l.id
WHERE wod.character_id = $character_id
	AND lci.login_id = $userdata[user_id]
EOQ;

$character_result = mysql_query($character_query) or die(mysql_error());
if(!mysql_num_rows($character_result))
{
	die('illegal character');
}

$page_title = "Give Favor";

if($_POST['formSubmit'])
{
	// attempt to save favor
	$favorTypeId = $_POST['favorTypeId'] + 0;
	$targetCharacterId = $_POST['targetCharacterId'] + 0;
	$description = htmlspecialchars($_POST['favorDescription']);
	$notes = htmlspecialchars($_POST['favorNotes']);
	$now = date('Y-m-d h:i:s');
	
	$createFavorQuery = <<<EOQ
INSERT INTO
	favors
	(
		source_id,
		source_type_id,
		target_id,
		target_type_id,
		favor_type_id,
		description,
		notes,
		date_given
	)
VALUES
	(
		$character_id,
		1,
		$targetCharacterId,
		1,
		$favorTypeId,
		'$description',
		'$notes',
		'$now'
	)
EOQ;

	$createFavorResult = mysql_query($createFavorQuery) or die(mysql_error());
	
	$page_message = <<<EOQ
<span class="pageMessage">Favor has been created.</span><br />
EOQ;
}


$favorTypeQuery = "SELECT * FROM favor_types";
$favorTypeResult = mysql_query($favorTypeQuery) or die(mysql_error());

$ids = $names = "";
while($favorTypeDetail = mysql_fetch_array($favorTypeResult, MYSQL_ASSOC))
{
	$ids[] = $favorTypeDetail['id'];
	$names[] = $favorTypeDetail['name'];
}

$favorTypeSelect = buildSelect($favorType, $ids, $names, "favorTypeId");

$page_content = <<<EOQ
<h2>Give Favor to another Character/Group</h2>
$page_message

<form id="giveFavorForm">
<div class="formInput">
	<label>Give Favor to:</label>
	<input type="hidden" name="targetCharacterId" id="targetCharacterId" value="" />
	<input type="text" name="targetCharacter" id="targetCharacter" value="$targetCharacter"><br />
</div>
<div class="formInput">
	<label>Favor Type:</label>
	$favorTypeSelect
</div>
<div class="formInput">
	<label>Favor Description:</label>
	<input type="text" name="favorDescription" id="favorDescription" value="$favorDescription"><br />
</div>
<div class="formInput">
	<label>Favor Notes:</label>
	<textarea name="favorNotes" rows="5" cols="50">$favorNotes</textarea>
</div>
<div class="formInput">
	<input type="hidden" name="sourceCharacterId" value="$character_id" />
	<input type="button" name="formSubmit" id="formSubmit" value="Grant Favor" />
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
			if($.trim($('#targetCharacterId').val()) == '')
			{
				errors += " - Select a character to give the favor to.\\r\\n";
			}
			if($.trim($('#favorDescription').val()) == '')
			{
				errors += ' - Provide a brief description of the favor being given.\\r\\n';
			}
			
			if(errors == '')
			{
				$.ajax({
					url: "/favors.php?action=add",
					data: $('#giveFavorForm').serialize(),
					type: "post",
					dataType: "html",
					success: function(response, status, request) {
						alert(response);
						//window.location.reload();
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
		$('#targetCharacter').autocomplete({
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
				$('#targetCharacterId').val(ui.item.id);
			}
		});
	});
</script>

EOQ;
?>