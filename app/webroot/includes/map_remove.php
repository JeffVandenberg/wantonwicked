<?
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Content-Type: application/xml");
$feature_id = $_GET['feature_id']+0;

$remove_query = "update map_features set isDeleted=1, updatedBy=$userdata[user_id], updatedOn=now() where featureId=$feature_id;";
$remove_result = mysql_query($remove_query) or -1;

$status = "success";
$message = "Removed Feature";

if($remove_result === -1)
{
  $status = "failed";
  $message = "Failed to remove Feature";
}


$status_page = <<<EOQ
<?xml version="1.0" ?>
<root>
  <status>$status</status>
  <message>$message</message>
</root>
EOQ;

echo $status_page;
?>