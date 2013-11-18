<?php
$character_type = isset($_GET['character_type']) ? strtolower($_GET['character_type']) : 'mortal';

switch($character_type)
{
	case 'mortal':
		include 'includes/view_sheet_get_fragment_mortal.php';
		break;
	case 'vampire':
		include 'includes/view_sheet_get_fragment_vampire.php';
		break;
}

header('Content-type: application/json');
$page_content = json_encode($page_content);
?>