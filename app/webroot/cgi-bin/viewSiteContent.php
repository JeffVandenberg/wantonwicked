<?
/**************************************************************
* Name: viewSiteContent.php
* Date: 5-Mar-04
* Description: Function to grab content and format it in a standard
               way. Does not wrap the text with anything.
**************************************************************/

function viewSiteContent ( $content_detail = "", $target = "", $info_table_header_color, $info_table_row_color )
{
  global $mysqli;
	// function values
	$content_area = "";
	
	// get links
	$content_links_query = "SELECT site_contents_links.Description, site_content.* from site_contents_links INNER JOIN site_content ON site_contents_links.target_content_id = site_content.content_id ORDER BY site_content.content_name;";
	$content_links_result = $mysqli->query($content_links_query);
	
	if($content_links_result->num_rows)
	{
		// add area of links
		$content_area .= <<<EOQ
<div align="center">
<table border="0" cellspacing="2" cellpadding="2" class="normal_text">
  <tr bgcolor="$info_table_header_color">
    <th colspan="2">
      Related Links
    </th>
  </tr>
EOQ;
			
		$row = 0;
		$target_parameter = ($target != "") ? "target=\"$target\"" : "";
		while($content_links_detail = $content_links_result->fetch_array(MYSQLI_ASSOC))
		{
			$row_color = (($row++)%2) ? $info_table_row_color : "";
			
			$content_area .= <<<EOQ
	<tr bgcolor="$row_color">
	  <td>
	    <a href="view_content.fro?content_uid=$content_links_detail[Content_UID]&site_id=$content_links_detail[Site_ID]" $target_parameter>$content_links_detail[Content_Name]</a>
	  </td>
	  <td>
	    $content_links_detail[Description]
	  </td>
	</tr>
EOQ;
		}
		
		$content_area .= "</table></div><br>";
	}
	$content_area .= $content_detail['Content_Body'];
	
	return $content_area;
}
?>