<?php
use classes\character\data\CharacterStatus;
use classes\core\helpers\Request;
use classes\core\helpers\Response;
use classes\core\repository\Database;

include 'cgi-bin/start_of_page.php';
$characterId = Request::getValue('character_id', 0);
$includeAll = Request::getValue('include_all', false);
$term = Request::getValue('term') . '%';
$statuses = implode(',', CharacterStatus::SANCTIONED);

$characterQuery = <<<EOQ
SELECT
    id,
    character_name
FROM
    characters AS C
WHERE
    C.character_name LIKE ?
    AND C.character_status_id IN ($statuses)
ORDER BY
    character_name LIMIT 20;
EOQ;

$params = [$term];
$characters = Database::getInstance()->query($characterQuery)->all($params);

if(count($characters) === 0)
{
	$characters[] = ["id" => '0', 'characterName' => 'No Matches'];
}

$returnArray['characters'] = $characters;

Response::sendJson($characters);
