<?php
$characterName = $_GET['username'];
$disallowedCharacters = array('.', '"');
$characterName = str_replace($disallowedCharacters, '', $characterName);
$dashPosition = strpos($characterName, '--', 1);
if($dashPosition > 0)
{
	$characterName = substr($characterName, 0, $dashPosition);
}


header("location:http://www.wantonwicked.net/wiki/index.php?n=Players.$characterName");
//die($characterName);
?>