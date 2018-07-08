<?php
use classes\core\helpers\Request;
use classes\core\repository\Database;

$favorId = Request::getValue('favorId', 0);
$transferCharacterId = Request::getValue('transferCharacterId', 0);

Database::getInstance()->startTransaction();

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

$rows = Database::getInstance()->query($transferQuery)->execute($params);

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
    $rows = Database::getInstance()->query($updateQuery)->execute($params);

    if ($rows > 0) {
        $message = 'Successfully Transferred Favor.';
    } else {
		Database::getInstance()->rollBackTransaction();
        $message = 'There was an error cancelling your favor. Please try again later.';
    }
} else {
    $message = 'There was an error. Please try again later.';
}

Database::getInstance()->commitTransaction();

echo $message;
