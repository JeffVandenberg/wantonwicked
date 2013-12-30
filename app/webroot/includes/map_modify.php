<?
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Content-Type: application/xml");
$html = htmlspecialchars($_GET['html']);
$name = htmlspecialchars($_GET['name']);
$type = htmlspecialchars($_GET['type']);
$subtype = htmlspecialchars($_GET['subtype']);
$color = $_GET['color'];
$id = $_GET['id'] + 0;

$status = "success";
$message = "Updated Feature";

// add to database
$feature_query = <<<EOQ
UPDATE
  map_features
SET
  FeatureName = '$name',
  FeatureHTML = '$html',
  FeatureTYPE = '$type',
  FeatureSubtype = '$subtype',
  FeatureColor = '$color'
WHERE
  FeatureID = $id;
EOQ;
//echo $feature_query."<br><br>";
$feature_result = mysql_query($feature_query) or die(mysql_error());

$status_page = <<<EOQ
<?xml version="1.0" ?>
<root>
  <status>$status</status>
  <message>$message</message>
  <featureId>$id</featureId>
</root>
EOQ;

echo $status_page;
?>