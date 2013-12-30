<?php
use classes\core\helpers\Request;

include 'cgi-bin/dbconnect.php';
$characterId = Request::GetValue('character_id', 0);
$includeAll = Request::GetValue('include_all', false);
$term = mysql_real_escape_string($_POST['term']);

$characterQuery = <<<EOQ
SELECT
    character_id,
    character_name
FROM
    wod_characters
WHERE
    character_name LIKE '$term%'
    AND is_sanctioned = 'Y'
    AND is_deleted='N'
ORDER BY
    character_name LIMIT 20;
EOQ;
$characterResult = mysql_query($characterQuery) or die(mysql_error());

$characters = "";
while($characterDetail = mysql_fetch_array($characterResult, MYSQL_ASSOC))
{
	$row_array['id'] = $characterDetail['character_id'];
	$row_array['characterName'] = $characterDetail['character_name'];
	
	$characters[] = $row_array;
}

if($characters == "")
{
	$characters[] = array("id" => '0', 'characterName' => 'No Matches');
}

$returnArray['characters'] = $characters;

echo json_encode($characters);