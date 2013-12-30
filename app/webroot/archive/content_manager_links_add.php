<?
$page_title = "Add Link to Site Content";
$add_bars = false;
$required_permissions = array("content_mod");
$auth_use_and = false;
include 'start_of_page.php';

// page variables
$page = "";
$links_list = "";
$js = "";
$show_form = true;
$content_id = (isset($_GET['content_id'])) ? $_GET['content_id'] : 1;
$content_id = (isset($_POST['content_id'])) ? $_POST['content_id'] : $content_id;

// test if adding an character to the entry
if(isset($_POST['action']))
{
	// get content_id
	$target_content_id = $_POST['target_content_id'] +0;
	$description = htmlspecialchars($_POST['description']);
	
	$link_query = "insert into site_contents_links values (null, $content_id, $target_content_id, '$description');";
	$link_result = $mysqli->query($link_query);
	
	$show_form = false;
	$js = <<<EOQ
<script language="javascript">
window.opener.location.reload(true);
window.opener.focus();
window.close();
</script>
EOQ;
}

if($show_form)
{
	// get details of source content
	$content_query = "select * from site_content where content_id=$content_id;";
	$content_result = $mysqli->query($content_query);
	$content_detail = $content_result->fetch_array(MYSQLI_ASSOC);
  
  // build select of all site content
	$site_content_query = "select * from site_content where site_id=$_SESSION[site_id] order by content_name;";
  $site_content_result = $mysqli->query($site_content_query);
  $content_ids = "";
  $content_names = "";
  
  while($site_content_detail = $site_content_result->fetch_array(MYSQLI_ASSOC))
  {
	  $content_ids[] = $site_content_detail['Content_ID'];
	  $content_names[] = $site_content_detail['Content_Name'];
  }
  
  $content_select = buildSelect ("", $content_ids, $content_names, "target_content_id");
	
	// build form
	$form = <<<EOQ
<form method="post" action="$_SERVER[PHP_SELF]">
<table border="0" cellpadding="2" cellspacing="2" class="normal_text">
  <tr>
    <td>
      Page Name:
    </td>
    <td>
      $content_detail[Content_Name]
    </td>
  </tr>
  <tr>
    <td>
      Target Page:
    </td>
    <td>
      $content_select
    </td>
  </tr>
  <tr>
    <td>
      Description
    </td>
    <td>
      <input type="text" name="description" id="description" size="30" maxlength="255" value="">
    </td>
  </tr>
  <tr>
    <td colspan="2" align="center">
      <input type="hidden" name="action" name="action" value="create">
      <input type="hidden" name="content_id" id="content_id" value="$content_id">
      <input type="submit" value="Submit">
    </td>
  </tr>
</table>
</form>
EOQ;
  $form = buildTextBox( $form, "100%", $border, $border_bgcolor, $border_background, $inner_background, $inner_bgcolor );

}

// build page
$page = <<<EOQ
$js
$alert
$form
EOQ;

echo $page;

include 'end_of_page.php';
?>