<?php
$favorId = $_GET['favor_id'] + 0;

$favorQuery = <<<EOQ
SELECT
	favors.*,
	from_character.character_name AS from_character_name,
	to_character.character_name AS to_character_name,
	favor_types.name AS favor_type_name
FROM
	favors
		LEFT JOIN wod_characters AS from_character ON favors.source_id = from_character.character_id
		LEFT JOIN wod_characters AS to_character ON favors.target_id = to_character.character_id
		LEFT JOIN favor_types ON favors.favor_type_id = favor_types.id
WHERE
	favors.favor_id = $favorId
EOQ;
$favorResult = mysql_query($favorQuery) or die(mysql_error());

if(mysql_num_rows($favorResult))
{
	$favorDetail = mysql_fetch_array($favorResult, MYSQL_ASSOC);
?>

<form id="transferFavorForm" method="post" action="forms.php?action=transfer&favor_id=<?php echo $favorId; ?>">
<h2>Transfer Favor</h2>
<div class="formInput">
Favor From: <?php echo $favorDetail['from_character_name'] ?>
</div>
<div class="formInput">
Favor: <?php echo $favorDetail['description'] ?>
</div>
<div class="formInput">
	Transfer To: 
	<input type="hidden" name="favorId" value="<?php echo $favorId; ?>" />
	<input type="text" name="transferCharacterName" id="transferCharacterName" value="" />
	<input type="hidden" name="transferCharacterId" id="transferCharacterId" value="" />
</div>
<div class="formInput">
<input type="button" id="transferButton" value="Transfer Favor" />
</div>
</form>
<script type="text/javascript">
	$(document).ready(function(){
		$('input:text').keypress(function(e){
			if(e.keyCode == 13)
			{
				return false;
			}
		});
		$('#transferButton').click(function(){
			var errors = '';
			if($.trim($('#transferCharacterId').val()) == '')
			{
				errors += " - Select a character to give the favor to.\r\n";
			}
			
			if(errors == '')
			{
				alert('submit form');
				$.ajax({
					url: "/favors.php?action=transferFavor",
					data: $('#transferFavorForm').serialize(),
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
				alert('Please correct the following errors: \r\n' + errors);
			}
		});
		$('#transferCharacterName').autocomplete({
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
				$('#transferCharacterId').val(ui.item.id);
			}
		});
	});
</script>
<?php
}
else
{
?>
<script type="text/javascript">
	window.close();
</script>
<?php
}
?>