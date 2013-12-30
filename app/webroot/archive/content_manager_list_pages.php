<?
$page_title = "View All Site Content";
$add_left = false;
$required_permissions = array("content_mod");
$auth_use_and = false;
include 'start_of_page.php';

// page variables
$page = "";
$content_table = "";

// get content
$content_query = "SELECT * from site_content where site_id=$_SESSION[site_id];";
$content_result = $mysqli->query($content_query);

// layout content
$content_table = <<<EOQ
<table border="0" cellspacing="2" cellpadding="2" class="normal_text">
  <tr bgcolor="$info_table_header_color">
    <th>
      Page Name
    </th>
    <th>
      Page ID
    </th>
    <th>
      Is Top Level
    </th>
  </tr>
EOQ;

$row = 0;
while($content_detail = $content_result->fetch_array(MYSQLI_ASSOC))
{
	$row_color = (($row++)%2) ? $info_table_row_color : "";
	
	$content_table .= <<<EOQ
  <tr bgcolor="$row_color">
    <td>
      <a href="content_manager.fro?view_action=y&view_content_uid=$content_detail[Content_UID]" target="_blank">$content_detail[Content_Name]</a>
    </td>
    <td>
      $content_detail[Content_UID]
    </td>
    <td>
      $content_detail[Is_Top_Level]
    </td>
  </tr>
EOQ;
}

$content_table .= "</table>";

$content_table = buildTextBox( $content_table, "", $border, $border_bgcolor, $border_background, $inner_background, $inner_bgcolor );

$page = <<<EOQ
$content_table
EOQ;

echo $page;

include 'end_of_page.php';
?>