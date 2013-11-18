<?
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

// get entry id
$entry_id = (isset($_POST['entry_id'])) ? $_POST['entry_id'] +0 : 1;
$entry_id = (isset($_GET['entry_id'])) ? $_GET['entry_id'] +0 : $entry_id;

$type = "public";
$type = (isset($_POST['type'])) ? $_POST['type'] : $type;
$type = (isset($_GET['type'])) ? $_GET['type'] : $type;

// build start of page
$page_title = "Add Reference to Entry #$entry_id";

// page variables
$page = "";
$alert = "";
$java_script = "";
$form = "";
$show_form = true;

// test if adding an character to the entry
if(isset($_POST['action']))
{
	// get entry_id
	$target_entry_id = $_POST['target_entry_id'] +0;
	
	$reference_query = "insert into city_book_references values (null, $entry_id, $target_entry_id, '$type', '');";
	$reference_result = mysql_query($reference_query) or die(mysql_error());
	
	$show_form = false;
	$java_script = <<<EOQ
<script language="javascript">
window.opener.location.reload(true);
window.opener.focus();
window.close();
</script>
EOQ;
}

if($show_form)
{
	// get details of entry
	$source_entry_query = "select * from city_book where entry_id=$entry_id;";
	$source_entry_result = mysql_query($source_entry_query);
	$source_entry_detail = mysql_fetch_array($source_entry_result, MYSQL_ASSOC);
  
  // build select of entries
	$entry_query = "select * from city_book where city='$source_entry_detail[City]' and is_deleted='N' order by entry_category, entry_name;";
  $entry_result = mysql_query($entry_query) or die(mysql_error());
  $entry_ids = "";
  $entry_names = "";
  
  while($entry_detail = mysql_fetch_array($entry_result, MYSQL_ASSOC))
  {
	  $entry_ids[] = $entry_detail['Entry_ID'];
	  $entry_names[] = $entry_detail['Entry_Category'] . " - " . $entry_detail['Entry_Name'];
  }
  
  $entry_select = buildSelect ("", $entry_ids, $entry_names, "target_entry_id");
	
	// build form
	$form = <<<EOQ
<form method="post" action="$_SERVER[PHP_SELF]">
<table border="0" cellpadding="2" cellspacing="2" class="normal_text">
  <tr>
    <td>
      Source Entry:
    </td>
    <td>
      $source_entry_detail[Entry_Name]
    </td>
  </tr>
  <tr>
    <td>
      Target Entry:
    </td>
    <td>
      $entry_select
    </td>
  </tr>
  <tr>
    <td colspan="2" align="center">
      <input type="hidden" name="type" id="type" value="$type">
      <input type="hidden" name="action" name="action" value="create">
      <input type="hidden" name="entry_id" id="entry_id" value="$entry_id">
      <input type="submit" value="Submit">
    </td>
  </tr>
</table>
</form>
EOQ;
}

// build page
$page_content = <<<EOQ
$alert
$form
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