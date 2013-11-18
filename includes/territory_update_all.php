<?php
include 'includes/classes/repository/territory_repository.php';

$page_title = "Update all territories";

$territoryRepository = new TerritoryRepository();

$territories = $territoryRepository->GetTerritoriesByActive(true);
foreach($territories as $territory)
{
	$territoryRepository->UpdateCurrentQualityForTerritory($territory);
}

ob_start();
?>

<?php foreach($territories as $territory): ?>

<?php echo $territory['territory_name'] ?> updated to <?php echo $territory['current_quality']; ?><br />

<?php endforeach; ?>

<?php
$page_content = ob_get_contents();
ob_end_clean();
?>