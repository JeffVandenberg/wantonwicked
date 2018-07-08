<?php
use classes\core\repository\Database;

$favorId = $_GET['favorId'] + 0;

$dischargeQuery = <<<EOQ
UPDATE
	favors
SET
	is_broken = 1,
	date_broken = now()
WHERE
	favor_id = ?;
EOQ;

$result = Database::getInstance()->query($dischargeQuery)->execute(
    array(
        $favorId
    )
);

if($result)
{
	$message = 'Successfully broke favor.';
}
else
{
	$message = 'There was an error. Please try again later.';
}
?>

<?php echo $message; ?>

