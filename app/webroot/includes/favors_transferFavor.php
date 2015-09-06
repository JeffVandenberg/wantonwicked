<?php
use classes\core\helpers\Request;
use classes\core\repository\Database;

$favorId = Request::GetValue('favorId', 0);
$transferCharacterId = Request::GetValue('transferCharacterId', 0);

Database::GetInstance()->StartTransaction();

$transferQuery = <<<EOQ
INSERT INTO 
	favors
(
	source_id,
	source_type_id,
	target_id,
	target_type_id,
	parent_favor_id,
	favor_type_id,
	description,
	notes,
	date_given,
	date_created
)
SELECT
	source_id,
	source_type_id,
	?,
	1,
	favor_id,
	favor_type_id,
	description,
	notes,
	date_given,
	now()
FROM
	favors
WHERE
	favor_id = ?
EOQ;
$params = array(
    $transferCharacterId,
    $favorId
);

$rows = Database::GetInstance()->Query($transferQuery)->Execute($params);

if ($rows > 0) {
    $updateQuery = <<<EOQ
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
    $rows = Database::GetInstance()->Query($updateQuery)->Execute($params);

    if ($rows > 0) {
        $message = "Successfully Transferred Favor.";
    } else {
		Database::GetInstance()->RollBackTransaction();
        $message = "There was an error cancelling your favor. Please try again later.";
    }
} else {
    $message = "There was an error. Please try again later.";
}

Database::GetInstance()->CommitTransaction();

echo $message;