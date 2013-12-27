<?php
$page_title = $contentHeader = 'List Encounters';

ob_start();
?>

Encounters listed here!
<?
$page_content = ob_get_clean();