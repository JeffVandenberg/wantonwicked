<?php
use classes\core\helpers\Request;
use classes\core\repository\Database;

$favorId = Request::getValue('favorId', 0);

$dischargeQuery = <<<EOQ
UPDATE
	favors
SET
	date_discharged = now()
WHERE
	favor_id = ?
EOQ;
$params = array(
    $favorId
);
$rows = Database::getInstance()->query($dischargeQuery)->execute($params);
if ($rows) {
    echo 'Successfully discharged favor.';
} else {
    echo 'There was an error. Please try again later.';
}
