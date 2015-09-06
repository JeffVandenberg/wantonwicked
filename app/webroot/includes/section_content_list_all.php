<?
$page_title = "List all Site Content";

// get content
$content_query = "SELECT * from site_content;";
$content_result = mysql_query($content_query) || die(mysql_error());

// layout content
$page_content = <<<EOQ
<table border="0" cellspacing="2" cellpadding="2" class="normal_text">
  <tr bgcolor="#000000">
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
while($content_detail = mysql_fetch_array($content_result, MYSQL_ASSOC))
{
	$row_color = (($row++)%2) ? "#443a33" : "";
	
	$page_content .= <<<EOQ
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

$page_content .= "</table>";

?>