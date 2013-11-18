<?
$page_title = "View All Site Content";
$add_bars = false;
$required_permissions = array("content_mod");
$auth_use_and = false;
include 'start_of_page.php';

// page variables
$page = "";
$links_list = "";
$js = "";
$content_id = (isset($_GET['content_id'])) ? $_GET['content_id'] : 1;
$content_id = (isset($_POST['content_id'])) ? $_POST['content_id'] : $content_id;

// test if deleting
if(isset($_POST['delete']))
{
	$entry_list = $_POST['delete'];
	while(list($key, $value) = each($entry_list))
	{
		//echo "delete: $key: $value<br>";
		$delete_query = "delete from site_contents_links where link_id=$value;";
		//echo $delete_query."<br>";
		$delete_result = $mysqli->query($delete_query);
	}
}

// build js for page
$js = <<<EOQ
<script language="JavaScript">
function submitForm ( )
{
	window.document.link_list.submit();
}
</script>
EOQ;

// get list of links
$link_query = <<<EOQ
SELECT site_contents_links.Link_ID, site_content.*
FROM site_contents_links INNER JOIN site_content ON site_contents_links.target_content_id = site_content.content_id
WHERE site_contents_links.source_content_id = $content_id;
EOQ;
$link_result = $mysqli->query($link_query);

// build start of links list
$links_list = <<<EOQ
<a href="content_manager_links_add.fro" onClick="window.open('content_manager_links_add.fro?content_id=$content_id', 'addLink$content_id', 'width=300,height=300,scrollbars,resizable');return false;">Add Link</a>
&nbsp;&nbsp;&nbsp;&nbsp;
<a href="#" onClick="submitForm();return false;">Delete Link(s)</a>
<form name="link_list" id="link_list" method="post" action="$_SERVER[PHP_SELF]">
<table border="0" cellspacing="2" cellpadding="2" class="normal_text">
  <tr bgcolor="$info_table_header_color">
    <th>
      &nbsp;
    </th>
    <th>
      Page Name
    </th>
    <th>
      Page ID
    </th>
  </tr>
EOQ;

// start to cycle through results
$row = 0;
while($link_detail = $link_result->fetch_array(MYSQLI_ASSOC))
{
	$row_color = (($row++)%2) ? $info_table_row_color : "";
	
	$links_list .= <<<EOQ
	<tr bgcolor="$row_color">
	  <td>
	    <input type="checkbox" name="delete[]" id="delete[]" value="$link_detail[Link_ID]">
	  </td>
	  <td>
	    <a href="content_manager.fro?view_action=y&view_content_uid=$link_detail[Content_UID]" target="_top">$link_detail[Content_Name]</a>
	  </td>
	  <td>
	    $link_detail[Content_UID]
	  </td>
	</tr>
EOQ;
}

$links_list .= "</form></table>";

// wrap links list
$links_list = buildTextBox( $links_list, "100%", $border, $border_bgcolor, $border_background, $inner_background, $inner_bgcolor );

// build page
$page = <<<EOQ
$js
$links_list
EOQ;

echo $page;

include 'end_of_page.php';
?>