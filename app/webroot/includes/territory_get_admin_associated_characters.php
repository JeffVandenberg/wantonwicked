<?php
$id = $_GET['id'] + 0;

include 'includes/components/territory_associated_characters.php';

$page_content = CreateTerritoryAssociatedCharacters($id, true);
?>