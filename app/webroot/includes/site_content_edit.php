<?
$page_title = "Edit Site Content";

// page variables
$page = "";
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
$section = "";
$section_order = 1;
$description = "";
$action = "create";

// test if updating a page
if(isset($_POST['action']))
{
	$content_id = $_POST['content_id']+0;
	$content_name = $_POST['content_name'];
	$content_uid = $_POST['content_uid'];
	$content_body = $_POST['content_body'];
	$is_top_level = $_POST['is_top_level'];
	$description = htmlspecialchars($_POST['description']);
	$section = htmlspecialchars($_POST['section']);
	$section_rank = $_POST['section_rank'] +0;
	
	// validate
	$is_valid = true;
	
	// compare uid against database
	$uid_query = "SELECT * FROM site_content WHERE content_uid='$content_uid' AND content_id != $content_id;";
	$uid_result = mysql_query($uid_query) or die(mysql_error());
	
	if(mysql_num_rows($uid_result))
	{
		$alert = "A Page already has that ID.<br>";
		$is_valid = false;
	}
	
	// test is if valid
	if($is_valid)
	{
		// test which action to perform
		if($_POST['action'] == 'create')
		{
			// insert new record
			$content_id = getNextID($mysqli, "site_content", "content_id");
		
			$create_query = "insert into site_content values ($content_id, '$content_uid', 0, '$content_name', '$content_body', '$is_top_level', '$section', $section_rank, '$description', '', '');";
			$create_result = mysql_query($create_query) or die(mysql_error());
		}
		if($_POST['action'] == 'update')
		{
			// update record
			$content_id = $_POST['content_id'] +0;
			
			$update_query = "update site_content set content_uid='$content_uid', content_name='$content_name', content_body='$content_body', is_top_level='$is_top_level', section='$section', section_rank=$section_rank, description='$description' where content_id=$content_id;";
			$update_result = mysql_query($update_query) or die(mysql_error());
		}
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
		$content_query = "select * from site_content where content_uid='$view_content_uid';";
		$content_result = mysql_query($content_query) or die(mysql_error());
		
		if(mysql_num_rows($content_result))
		{
			// get details of page content
			$content_detail = mysql_fetch_array($content_result, MYSQL_ASSOC);
			
			$content_id = $content_detail['Content_ID'];
			$content_uid = htmlspecialchars($content_detail['Content_UID']);
			$content_name = htmlspecialchars($content_detail['Content_Name']);
			$content_body = htmlspecialchars($content_detail['Content_Body']);
			$is_top_level = $content_detail['Is_Top_Level'];
			$is_top_level_yes_check = ($is_top_level == 'Y') ? "checked" : "";
			$is_top_level_no_check = ($is_top_level == 'N') ? "checked" : "";
			$section = $content_detail['Section'];
			$section_rank = $content_detail['Section_Rank'];
			$description = $content_detail['Description'];
			
			//ensure that view action is set properly
			$view_action = "update";
		}
		else
		{
			$alert = "No page was found with that ID<br>";
			
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
	$java_script = <<<EOQ
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
<form name="content_form" id="content_form" method="post" action="$_SERVER[PHP_SELF]?action=edit">
<table border="0" cellspacing="2" cellpadding="2" class="normal_text">
  <tr>
    <td width="25%">
      Page Name:
    </td>
    <td width="75%">
      <input type="text" name="content_name" id="content_name" size="30" maxlength="100" value="$content_name">
    </td>
  </tr>
  <tr>
    <td>
      Page ID:
    </td>
    <td>
      <input type="text" name="content_uid" id="content_uid" size="20" maxlength="100" value="$content_uid">
    </td>
  </tr>
  <tr>
    <td>
      Is Top Level:
    </td>
    <td>
      Yes: <input type="radio" name="is_top_level" id="is_top_level" value="Y" $is_top_level_yes_check>
      No: <input type="radio" name="is_top_level" id="is_top_level" value="N" $is_top_level_no_check>
    </td>
  </tr>
  <tr>
    <td>
    	Section
    </td>
    <td>
    	<input type="text" name="section" id="section" value="$section" size="25" maxlength="45">
    	Order:
    	<input type="text" name="section_rank" id="section_rank" value="$section_rank" size="3" maxlength="4">
    </td>
  </tr>
  <tr>
    <td>
    	Description:
    </td>
    <td>
    	<input type="text" name="description" id="description" value="$description" size="40" maxlength="250">
    </td>
  </tr>
  <tr>
    <td colspan="2">
      Page Content:<br>
      <textarea name="content_body" id="content_body" rows="15" cols="80">$content_body</textarea>
    </td>
  </tr>
  <tr>
    <td colspan="2" align="center">
      <input type="hidden" name="action" id="action" value="$view_action">
      <input type="hidden" name="content_id" id="content_id" value="$content_id">
      <input type="Submit" value="Update Page" onClick="submitForm();return false">
    </td>
  </tr>
</table>
</form>
EOQ;
}

// build uid_input_box
$actions = array("create", "update");
$action_select = buildSelect($action, $actions, $actions, "view_action");

$uid_input_box = <<<EOQ
<form method="post" action="$_SERVER[PHP_SELF]?action=edit">
  <span class="highlight">Page ID:</span>
  <input type="text" name="view_content_uid" id="view_content_uid" value="" size="30" maxlength="100">
  $action_select
  <input type="submit" value="Submit">
  <a href="$_SERVER[PHP_SELF]?action=list_all" target="_blank">View All Pages</a>
</form>
EOQ;

$page_content = <<<EOQ
$uid_input_box
$alert
<br>
$form
EOQ;
?>