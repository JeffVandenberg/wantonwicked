<?
// get variable for content
$content_uid = (isset($_GET['content_uid'])) ? $_GET['content_uid'] : "about_fro";
$site_id = (isset($_GET['site_id'])) ? $_GET['site_id'] +0 : 1000;

// get content details from the database
$content_query = "select * from site_content where content_uid='$content_uid';";
$content_result = mysql_query($content_query) || die(mysql_error());

$page_content = "No Contents";
$pate_title = "No Title";
$content_detail = "";
if(mysql_num_rows($content_result))
{
	$content_detail = mysql_fetch_array($content_result, MYSQL_ASSOC);
	
	$page_title = $content_detail['Content_Name'];
}

// pass details on to function to handle
if($content_detail != '')
{
	$page_content = $content_detail['Content_Body'];
}
?>
