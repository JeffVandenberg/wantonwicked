<?php

use classes\character\data\Character;
use classes\character\repository\CharacterRepository;
use classes\core\helpers\FormHelper;
use classes\core\helpers\Request;
use classes\core\repository\Database;
use classes\log\CharacterLog;
use classes\log\data\ActionType;
use classes\request\data\RequestStatus;
use classes\request\data\RequestType;
use classes\request\RequestMailer;

require_once 'cgi-bin/start_of_page.php';

$db = Database::GetInstance();

$sql = <<<EOQ
UPDATE
    requests AS R
    INNER JOIN characters as C ON R.character_id = C.id
SET
    request_status_id = ?
WHERE
    C.is_deleted = 'Y'
EOQ;

$params = array(RequestStatus::Closed);

$rows = $db->Query($sql)->Execute($params);

echo "<pre>";
echo count($rows);
foreach($rows as $row)
{
    //var_dump($row);
}
