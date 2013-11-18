<?
/********************************************************
* city_book_references.fro
* Author: Jeff Vandenberg
* Date: 2-7-04
* Purpose: create/destroy references between city book entries
********************************************************/

include 'cgi-bin/start_of_page.php';

// perform required includes
define('IN_PHPBB', true);
$phpbb_root_path = './forum/';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);

$template = new Template("/templates/");

// check page actions
$page_title = "";
$css_url = "www.wantonwicked.net/wicked.css";
$page_content = "";
$java_script = "";

// get GET/POST variables for page
$entry_id = (isset($_POST['entry_id'])) ? $_POST['entry_id'] +0 : 1;
$entry_id = (isset($_GET['entry_id'])) ? $_GET['entry_id'] +0 : $entry_id;

$type = "public";
$type = (isset($_POST['type'])) ? $_POST['type'] : $type;
$type = (isset($_GET['type'])) ? $_GET['type'] : $type;

// build start of page page
$page_title = "Create $type references for Entry #$entry_id";

// page variables
$page = "";
$alert = "";
$java_script = "";
$entry_list = "";
$may_edit = false;

// test if deleting entrys
if(isset($_POST['delete']))
{
	$entry_list = $_POST['delete'];
	while(list($key, $value) = each($entry_list))
	{
		//echo "delete: $key: $value<br>";
		$delete_query = "delete from city_book_references where entry_reference_id=$value;";
		//echo $delete_query."<br>";
		$delete_result = mysql_query($delete_query) or die(mysql_error());
	}
}

// query db to verify using appropriate entry id
$entry_query = "select * from city_book where entry_id = $entry_id;";
$entry_result = mysql_query($entry_query) or die(mysql_error());

if(mysql_num_rows($entry_result))
{
	// build contents of page
	$entry_detail = mysql_fetch_array($entry_result, MYSQL_ASSOC);
	
  $java_script = <<<EOQ
<script language="JavaScript">
function submitForm ( )
{
	window.document.entry_list.submit();
}
</script>
EOQ;

    $entry_list = <<<EOQ
<a href="city_book_references_add.php" onClick="window.open('city_book_references_add.php?entry_id=$entry_id&type=$type', 'addReference$entry_id', 'width=300,height=200,resizable,scrollbars');return false;">Add Reference</a>&nbsp;&nbsp;&nbsp;
<a href="#" onClick="submitForm();return false;">Remove Reference(s)</a>
EOQ;
  
  // build list of references
	$entry_reference_query = "select city_book_references.*, city_book.* from city_book_references left join city_book on city_book_references.target_entry_id = city_book.entry_id where type='$type' and source_entry_id=$entry_id and city_book.is_deleted='N' order by entry_reference_id";
	//echo $entry_reference_query."<br>";
	$entry_reference_result = mysql_query($entry_reference_query) or die(mysql_error());

	$entry_list .= <<<EOQ
<form name="entry_list" id="entry_list" method="post" action="$_SERVER[PHP_SELF]">
<table border="0" cellpadding="2" cellspacing="2" class="normal_text">
  <tr bgcolor="#000000">
    <th>
      Delete
    </th>
    <th>
      Entry Name
    </th>
  </tr>
EOQ;

  $entry_list .= "";
  
  $row = 0;
	while($entry_reference_detail = mysql_fetch_array($entry_reference_result, MYSQL_ASSOC))
	{
		$row_color = (($row++)%2) ? "#443a33" : "";
		$delete_cell = ($may_edit) ? "" : "";
		$entry_list .= <<<EOQ
  <tr bgcolor="$row_color">
    <td>
      <input type="checkbox" name="delete[]" id="delete[]" value="$entry_reference_detail[Entry_Reference_ID]">
    </td>
    <td>
      <a href="city_book_view.fro?entry_id=$entry_reference_detail[Entry_ID]" target="_top">$entry_reference_detail[Entry_Name]
    </td>
  </tr>
EOQ;
	}
	$entry_list .= <<<EOQ
</table>
<input type="hidden" name="entry_id" id="entry_id" value="$entry_id">
<input type="hidden" name="action" id="action" value="delete">
</form>
EOQ;

}
else
{
	$alert = <<<EOQ
<span class="redhighlight">That is an invalid entry ID</span>
EOQ;
}

// build page
$page_content = <<<EOQ
$alert
$entry_list
EOQ;

$template->assign_vars(array(
"PAGE_TITLE" => $page_title,
"CSS_URL" => $css_url, 
"JAVA_SCRIPT" => $java_script,
"PAGE_CONTENT" => $page_content
)
);

// initialize template
$template->set_filenames(array(
		'body' => 'templates/blank_layout.tpl')
);
$template->pparse('body');

?>