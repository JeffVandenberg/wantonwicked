<?php
use classes\core\helpers\Request;
use classes\core\repository\Database;

$favorId = Request::GetValue('favorId', 0);

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
$rows = Database::GetInstance()->Query($dischargeQuery)->Execute($params);
if ($rows) {
    echo "Successfully discharged favor.";
} else {
    echo "There was an error. Please try again later.";
}