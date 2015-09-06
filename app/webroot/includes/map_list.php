<?
// get site id
$site_id = isset($_POST['site_id']) ? $_POST['site_id'] : 0;
$site_id = isset($_GET['site_id']) ? $_GET['site_id'] : $site_id;

// setup response XML
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Content-Type: application/xml");
$xml_response = <<<EOQ
<?xml version="1.0" ?>
<featureList>
EOQ;

// query database
$feature_query = <<<EOQ
SELECT
  map_features.*
FROM
  map_features 
WHERE
  map_features.IsDeleted = 0
  AND map_features.Location = 1004
ORDER BY
  map_features.FeatureType;
EOQ;

$feature_result = mysql_query($feature_query) || die(mysql_error());

while($feature_detail = mysql_fetch_array($feature_result, MYSQL_ASSOC))
{
  $xml_response .= <<<EOQ
  <feature>
    <featureID>$feature_detail[FeatureID]</featureID>
    <featureName>$feature_detail[FeatureName]</featureName>
    <featureType>$feature_detail[FeatureType]</featureType>
    <featureSubtype>$feature_detail[FeatureSubtype]</featureSubtype>
    <featureHTML>$feature_detail[FeatureHTML]</featureHTML>
    <featureColor>$feature_detail[FeatureColor]</featureColor>
EOQ;

  $point_query = <<<EOQ
SELECT
  map_points.*
FROM 
  map_points
WHERE
  map_points.FeatureID = $feature_detail[FeatureID]
EOQ;
  $point_result = mysql_query($point_query) || die(mysql_error());

  if($feature_detail['FeatureType'] == "Territory")
  {
    // loop through all points
    $xml_response .= "<points>";
    while($point_detail = mysql_fetch_array($point_result, MYSQL_ASSOC))
    {
      $xml_response .= "<lat>$point_detail[Lat]</lat><lng>$point_detail[Lng]</lng>";
    }
  }
  else
  {
    // just record a single point
    $point_detail = mysql_fetch_array($point_result, MYSQL_ASSOC);
    $xml_response .= "<points>";
    $xml_response .= "<lat>$point_detail[Lat]</lat><lng>$point_detail[Lng]</lng>";
  }
  
  $xml_response .= "</points></feature>";
}

// close response XML
$xml_response .= "</featureList>";

echo $xml_response;
?>