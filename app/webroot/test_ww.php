<?php

use classes\core\repository\Database;
use classes\request\data\RequestStatus;

require_once 'cgi-bin/start_of_page.php';

$db = Database::getInstance();

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

$rows = $db->query($sql)->execute($params);

echo "<pre>";
echo count($rows);
foreach($rows as $row)
{
    //var_dump($row);
}
