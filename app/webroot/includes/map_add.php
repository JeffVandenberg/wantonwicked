<?
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Content-Type: application/xml");
$html = htmlspecialchars($_GET['html']);
$name = htmlspecialchars($_GET['name']);
$type = htmlspecialchars($_GET['type']);
$subtype = htmlspecialchars($_GET['subtype']);
$site_id = $_GET['site_id']+0;
$lat = $_GET['lat'];
$lng = $_GET['lng'];
$color = $_GET['color'];

$status = "success";
$message = "Added Feature";

// add to database
$feature_query = <<<EOQ
INSERT INTO 
  map_features
  (FeatureName,
  FeatureHTML,
  FeatureType,
  FeatureSubtype,
  AddedBy,
  AddedOn,
  IsDeleted,
  Location,
  FeatureColor)
VALUES
  (
  '$name',
  '$html',
  '$type',
  '$subtype',
  $userdata[user_id],
  now(),
  '0',
  $site_id,
  '$color'
  );
EOQ;
//echo $feature_query."<br><br>";
$feature_result = mysql_query($feature_query) or die(mysql_error());

// get the most recent feature ID
$id_query = "select max(FeatureID) as FeatureID from map_features";
$id_result = mysql_query($id_query) or die(mysql_error());
$id_detail = mysql_fetch_array($id_result, MYSQL_ASSOC);
$feature_id = $id_detail['FeatureID'];
//$feature_id = 1;
for($i = 0; $i < sizeof($lat); $i++)
{
  $lat_point = $lat[$i] +0;
  $lng_point = $lng[$i] +0;
  //echo "POINT: $lat[$i] : $lng[$i]<br>";
  $point_query = <<<EOQ
INSERT INTO
  map_points
  (
  FeatureID,
  Lat,
  Lng
  )
VALUES
  (
  $feature_id,
  $lat_point,
  $lng_point
  )
EOQ;
  //echo $point_query."<br><br>";
  $point_result = mysql_query($point_query) or die(mysql_error());
}

$status_page = <<<EOQ
<?xml version="1.0" ?>
<root>
  <status>$status</status>
  <message>$message</message>
  <featureId>$feature_id</featureId>
</root>
EOQ;

echo $status_page;
?>