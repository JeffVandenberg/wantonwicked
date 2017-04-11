<?php
use classes\territory\Territory;

$id = $_GET['id'] + 0;

$page_content = Territory::CreateTerritoryAssociatedCharacters($id, true);
?>