<?php
use classes\core\helpers\Request;
use classes\core\helpers\Response;
use classes\core\repository\Database;

include 'cgi-bin/start_of_page.php';
$characterId = Request::GetValue('character_id', 0);
$includeAll = Request::GetValue('include_all', false);
$term = $_POST['term'] . '%';

$characterQuery = <<<EOQ
SELECT
    id,
    character_name
FROM
    characters
WHERE
    character_name LIKE ?
    AND is_sanctioned = 'Y'
    AND is_deleted='N'
ORDER BY
    character_name LIMIT 20;
EOQ;
$params = array($term);
$characters = Database::GetInstance()->Query($characterQuery)->All($params);

if(count($characters) === 0)
{
	$characters[] = array("id" => '0', 'characterName' => 'No Matches');
}

$returnArray['characters'] = $characters;

Response::SendJson($characters);