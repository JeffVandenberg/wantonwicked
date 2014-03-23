<?php
$id = (isset($_GET['id'])) ? $_GET['id'] : 0;
include 'includes/components/territory_associated_characters.php';

$query = <<<EOQ
SELECT
	T.territory_name,
	T.id,
	C.character_name,
	T.npc_population,
	T.max_quality,
	T.quality,
	T.current_quality,
	T.security,
	T.optimal_population,
	T.territory_notes,
	T.is_open
FROM
	territories as T
	LEFT JOIN characters_territories AS CT ON T.id = CT.territory_id
	LEFT JOIN characters AS C ON T.character_id = C.character_id
WHERE
	T.is_active = 1
	AND T.id = $id
EOQ;

$result = ExecuteQuery($query);

if(mysql_num_rows($result))
{
	$detail = mysql_fetch_array($result, MYSQL_ASSOC);
	
	$territoryName = $detail['territory_name'];
	$characterName = $detail['character_name'];
	$quality = $detail['quality'];
	$maxQuality = $detail['max_quality'];
	$currentQuality = $detail['current_quality'];
	$security = $detail['security'];
	$optimalPopulation = $detail['optimal_population'];
	$npcPopulation = $detail['npc_population'];
	$territoryNotes = str_replace("\r\n", "<br />", $detail['territory_notes']);
	$isOpen = ($detail['is_open']) ? 'Yes' : 'No';
	$id = $detail['id'];
	
	$associatedCharacters = CreateTerritoryAssociatedCharacters($id, false);
}
else
{
	die("Unknown Territory");
}
?>

<h2>Territory Details</h2>
Manage Territory<br />
<br />
<div style="float:left;width:200px;">
	<div class="formInput">
		<label>Territory Name:</label>
		<?php echo $territoryName; ?>
	</div>
	<div class="formInput">
		<label>Controlling Character:</label>
		<?php echo $characterName; ?>
	</div>
	<div class="formInput">
		<label>Max Quality:</label>
		<?php echo $maxQuality; ?>
	</div>
	<div class="formInput">
		<label>Quality:</label>
		<?php echo $quality; ?>
	</div>
	<div class="formInput">
		<label>Current Quality:</label>
		<?php echo $currentQuality; ?>
	</div>
</div>
<div style="float:left;width:200px;">
	<div class="formInput">
		<label>Optimal Population:</label>
		<?php echo $optimalPopulation; ?>
	</div>
	<div class="formInput">
		<label>NPC Population:</label>
		<?php echo $npcPopulation; ?>
	</div>
	<div class="formInput">
		<label>Security:</label>
		<?php echo $security; ?>
	</div>
	<div class="formInput">
		<label>Open Feeding:</label>
		<?php echo $isOpen; ?>
	</div>
</div>
<div class="formInput firstCell">
	<label>Territory Notes:</label>
	<?php echo $territoryNotes; ?>
</div>

<h2>PC Residents</h2>
<?php echo $associatedCharacters; ?>