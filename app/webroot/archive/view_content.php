<?
// build connection
include 'dbconnect.php';

// get variable for content
$content_uid = (isset($_GET['content_uid'])) ? $_GET['content_uid'] : "about_fro";
$site_id = (isset($_GET['site_id'])) ? $_GET['site_id'] +0 : 1000;

// get content details from the database
$content_query = "select * from site_content where content_uid='$content_uid' and site_id=$site_id;";
$content_result = $mysqli->query($content_query);

$page_contents = "No Contents";
$pate_title = "No Title";
$content_detail = "";
if($content_result->num_rows)
{
	$content_detail = $content_result->fetch_array(MYSQLI_ASSOC);
	
	$page_title = $content_detail['Content_Name'];
}
// build start of page
include 'start_of_page.php';

// pass details on to function to handle
if($content_detail != '')
{
	$page_contents = viewSiteContent($content_detail, "", $info_table_header_color, $info_table_row_color);
}

// build page
$page_contents = buildTextBox( $page_contents, "100%", $border, $border_bgcolor, $border_background, $inner_background, $inner_bgcolor );

// output page
echo $page_contents;

include 'end_of_page.php';
?>