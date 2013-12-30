<?php
$favorId = $_GET['favorId'] + 0;

$dischargeQuery = <<<EOQ
UPDATE
	favors
SET
	date_discharged = now()
WHERE
	favor_id = $favorId;
EOQ;
$dischargeResult = mysql_query($dischargeQuery) or die(mysql_error());

if(mysql_affected_rows())
{
	$message = "Successfully discharged favor.";
}
else
{
	$message = "There was an error. Please try again later.";
}
?>


<?php echo $message; ?>