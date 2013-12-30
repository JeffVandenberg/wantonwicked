<?
$page_title = "Manage Site Content";
$add_left = false;
$required_permissions = array("content_mod");
$auth_use_and = false;
include 'start_of_page.php';

// page variables
$page = "";
$js = "";
$form = "";
$alert = "";
$uid_input_box = "";
$links_iframe = "";

// form variables
$content_uid = "";
$content_id = 0;
$content_name = "";
$content_body = "";
$is_top_level = "N";
$is_top_level_yes_check = "";
$is_top_level_no_check = "checked";
$action = "create";

// test if updating a page
if(isset($_POST['action']))
{
	$content_id = $_POST['content_id']+0;
	$content_name = $_POST['content_name'];
	$content_uid = $_POST['content_uid'];
	$content_body = $_POST['content_body'];
	$is_top_level = $_POST['is_top_level'];
	
	// validate
	$is_valid = true;
	
	// compare uid against database
	$uid_query = "SELECT * FROM site_content WHERE content_uid='$content_uid' AND site_id=$_SESSION[site_id] AND content_id != $content_id;";
	$uid_result = $mysqli->query($uid_query);
	
	if($uid_result->num_rows)
	{
		$alert = "<span class=\"red_highlight\">A Page already has that ID.</span>";
	}
	
	// test is if valid
	if($is_valid)
	{
		// test which action to perform
		if($_POST['action'] == 'create')
		{
			// insert new record
			$content_id = getNextID($mysqli, "site_content", "content_id");
		
			$create_query = "insert into site_content values ($content_id, '$content_uid', $_SESSION[site_id], '$content_name', '$content_body', '$is_top_level', '', '', '', '', '');";
			$create_result = $mysqli->query($create_query);
		}
		if($_POST['action'] == 'update')
		{
			// update record
			$content_id = $_POST['content_id'] +0;
			
			$update_query = "update site_content set content_uid='$content_uid', content_name='$content_name', content_body='$content_body', is_top_level='$is_top_level' where content_id=$content_id;";
			$update_result = $mysqli->query($update_query);
		}
	}
	else
	{
		$alert = buildTextBox( $alert, "", $border, $border_bgcolor, $border_background, $inner_background, $inner_bgcolor );
	}
}

// test if attempting to view
$view_action = (isset($_POST['view_action'])) ? 'Y' : 'N';
$view_action = (isset($_GET['view_action'])) ? 'Y' : $view_action;

if($view_action == 'Y')
{
	// test if they are attempting to look up a page
	$view_content_uid = (isset($_POST['view_content_uid'])) ? $_POST['view_content_uid'] : "";
	$view_content_uid = (isset($_GET['view_content_uid'])) ? $_GET['view_content_uid'] : $view_content_uid;
	
	if($view_content_uid != "")
	{
		// attempt to retrieve page content
		$content_query = "select * from site_content where content_uid='$view_content_uid' and site_id=$_SESSION[site_id];";
		$content_result = $mysqli->query($content_query);
		
		if($content_result->num_rows)
		{
			// get details of page content
			$content_detail = $content_result->fetch_array(MYSQLI_ASSOC);
			
			$content_id = $content_detail['Content_ID'];
			$content_uid = htmlspecialchars($content_detail['Content_UID']);
			$content_name = htmlspecialchars($content_detail['Content_Name']);
			$content_body = htmlspecialchars($content_detail['Content_Body']);
			$is_top_level = $content_detail['Is_Top_Level'];
			$is_top_level_yes_check = ($is_top_level == 'Y') ? "checked" : "";
			$is_top_level_no_check = ($is_top_level == 'N') ? "checked" : "";
			
			// create iframe for links
			$links_iframe = <<<EOQ
<iframe src="content_manager_links.fro?content_id=$content_id" width="250" height="150"></iframe>
EOQ;
			
			//ensure that view action is set properly
			$view_action = "update";
		}
		else
		{
			$alert = "<span class=\"red_highlight\">No page was found with that ID</span>";
			$alert = buildTextBox( $alert, "", $border, $border_bgcolor, $border_background, $inner_background, $inner_bgcolor );
			
			// set page to be in insert mode
			$view_action = "create";
		}
		
	}
	else
	{
		// no content set view action properly
		$view_action = "create";
	}
	
	// build form
	$js = <<<EOQ
<script language="javascript">
  function submitForm()
  {
	  var is_valid = true;
	  
	  if(!document.content_form.content_name.value.match(/\w/g))
	  {
		  is_valid = false;
		  alert('Please enter a page name');
	  }
	  if(!document.content_form.content_uid.value.match(/\w/g))
	  {
		  is_valid = false;
		  alert('Please enter a unique ID for the page');
	  }
	  if(!document.content_form.content_body.value.match(/\w/g))
	  {
		  is_valid = false;
		  alert('Please enter content for the page');
	  }
	  
	  if(is_valid)
	  {
		  document.content_form.submit();
	  }
  }
</script>
EOQ;
	
	$form = <<<EOQ
<form name="content_form" id="content_form" method="post" action="$_SERVER[PHP_SELF]">
<table border="0" cellspacing="2" cellpadding="2" class="normal_text">
  <tr>
    <td class="highlight">
      Page Name:
    </td>
    <td>
      <input type="text" name="content_name" id="content_name" size="30" maxlength="100" value="$content_name">
    </td>
    <td rowspan="4">
      $links_iframe
    </td>
  </tr>
  <tr>
    <td class="highlight">
      Page ID:
    </td>
    <td>
      <input type="text" name="content_uid" id="content_uid" size="20" maxlength="100" value="$content_uid">
    </td>
  </tr>
  <tr>
    <td class="highlight">
      Is Top Level:
    </td>
    <td>
      Yes: <input type="radio" name="is_top_level" id="is_top_level" value="Y" $is_top_level_yes_check>
      No: <input type="radio" name="is_top_level" id="is_top_level" value="N" $is_top_level_no_check>
    </td>
  </tr>
  <tr>
    <td class="highlight">
      Site Styles:
    </td>
    <td>
      <span class="normal_text">normal_text</span><br>
      <span class="highlight">highlight</span><br>
      <span class="red_highlight">red_highlight</span><br>
      <span class="contentheading">contentheading</span><br>
    </td>
  </tr>
  <tr>
    <td colspan="3">
      <span class="highlight">Page Content:</span><br>
      <textarea name="content_body" id="content_body" rows="15" cols="80">$content_body</textarea>
    </td>
  </tr>
  <tr>
    <td colspan="3" align="center">
      <input type="hidden" name="action" id="action" value="$view_action">
      <input type="hidden" name="site_id" id="site_id" value="$_SESSION[site_id]">
      <input type="hidden" name="content_id" id="content_id" value="$content_id">
      <input type="Submit" value="Update Page" onClick="submitForm();return false">
    </td>
  </tr>
</table>
</form>
EOQ;

	$form = buildTextBox( $form, "", $border, $border_bgcolor, $border_background, $inner_background, $inner_bgcolor );
}

// build uid_input_box
$actions = array("create", "update");
$action_select = buildSelect($action, $actions, $actions, "view_action");

$uid_input_box = <<<EOQ
<form method="post" action="$_SERVER[PHP_SELF]">
  <span class="highlight">Page ID:</span>
  <input type="text" name="view_content_uid" id="view_content_uid" value="" size="30" maxlength="100">
  $action_select
  <input type="submit" value="Submit">
  <a href="content_manager_list_pages.fro" target="_blank">View All Pages</a>
</form>
EOQ;

$uid_input_box = buildTextBox( $uid_input_box, "", $border, $border_bgcolor, $border_background, $inner_background, $inner_bgcolor );

// build page
$page = <<<EOQ
$js
$alert
$uid_input_box
<br>
$form
EOQ;

echo $page;

include 'end_of_page.php';
?>