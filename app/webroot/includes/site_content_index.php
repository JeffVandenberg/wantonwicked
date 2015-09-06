<?
$page_title = "Site Content";
$content_query = "select * from site_content order by Section_Rank, Section, Content_Name;";
$content_result = mysql_query($content_query) || die(mysql_error());

$page_content = "Site Content<br>";

$section = "";
while($content_detail = mysql_fetch_array($content_result, MYSQL_ASSOC))
{
	if($content_detail['Section'] != $section)
	{
		$section = $content_detail['Section'];
		$page_content .= "<br>$section:<br>";
	}
	$page_content .= <<<EOQ
&nbsp; <a href="site_content.php?action=view&content_uid=$content_detail[Content_UID]">$content_detail[Content_Name]</a> - $content_detail[Description]<br>
EOQ;
}
?>
