<?php
$favorId = $_GET['favorId'] + 0;

$dischargeQuery = <<<EOQ
UPDATE
	favors
SET
	is_broken = 1,
	date_broken = now()
WHERE
	favor_id = $favorId;
EOQ;
$dischargeResult = mysql_query($dischargeQuery) or die(mysql_error());

if(mysql_affected_rows())
{
	$message = "Successfully broke favor.";
}
else
{
	$message = "There was an error. Please try again later.";
}
?>


<?php echo $message; ?>