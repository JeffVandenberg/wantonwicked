<?
/********************************************************
* city_book_list.fro
* Author: Jeff Vandenberg
* Date: 12-Feb-04
* Purpose: List entries for the relevant site id and catgory provided
********************************************************/
$category = "All";
$category = (isset($_GET['category'])) ? htmlspecialchars($_GET['category']) : $category;
$category = (isset($_POST['category'])) ? htmlspecialchars($_POST['category']) : $category;

$site_id = 0;
$site_id = (isset($_GET['site_id'])) ? $_GET['site_id'] +0 : $site_id;
$site_id = (isset($_POST['site_id'])) ? $_POST['site_id'] +0 : $site_id;

$page_title = "List City Book Entries: $category";
include 'start_of_page.php';

$page_image = <<<EOQ
<img src="http://www.wantonwicked.net/img/bookpolicy.gif" alt="Book Policy"><br><br>
EOQ;

// special includes
include 'js_doSort.php';
include 'buildSortForm.php';

// page variables
$header = "";
$entry_list = "";
$page = "";
$js = "";
$sort_form = "";

$this_order_by = "entry_category";
$last_order_by = "";
$order_by = "entry_category, entry_name";
$order_dir = "asc";

// check to see if updating how the page is ordered
if(!empty($_POST['action']))
{
  if($_POST['action'] == 'sort')
  {
    $this_order_by = $_POST['this_order_by'];
    $last_order_by = $_POST['last_order_by'];
    if(($_POST['this_order_by'] == $_POST['last_order_by']) && $_POST['this_order_dir'] == 'asc')
    {
      $order_dir = "desc";
    }
  }
  $order_by = "$this_order_by $order_dir, entry_name";
}


// build header
$header = <<<EOQ
Welcome to the Amimono no Toshi City Book . Feel free to browse through any parts that interest you. All information provided is to be taken as In Character information that you can find out if your character does any looking around.
EOQ;

$header = buildTextBox( $header, "", $border, $border_bgcolor, $border_background, $inner_background, $inner_bgcolor );

// list entries sorted by category then name then by ID
// view only approved
$entry_query = "";

if($category == 'All')
{
  // build list of all entries
  $entry_query = "select login.Name, city_book.* from city_book left join login on city_book.submitted_by = login.id where is_approved='Y' and is_deleted='N' and site_id=$site_id order by $order_by;";

}
else
{
  // build list of entries only from selected category
  $entry_query = "select login.Name, city_book.* from city_book left join login on city_book.submitted_by = login.id where is_approved='Y' and is_deleted='N' and site_id=$site_id and entry_category='$category' order by $order_by;";

}

$entry_result = $mysqli->query($entry_query);

$entry_list = <<<EOQ
<table border="0" cellspacing="2" cellpadding="2" class="normal_text">
  <tr>
    <th class="contentheading" colspan="4">
      AnT City Book: $category
    </th>
  </tr>
  <tr bgcolor="$info_table_header_color">
    <th>
      <a href="javascript:doSort('entry_category')">Category</a>
    </th>
    <th>
      <a href="javascript:doSort('entry_name')">Name</a>
    </th>
    <th>
      <a href="javascript:doSort('name')">Submitted By</a>
    </td>
    <th>
      <a href="javascript:doSort('submitted_on')">Submitted On</a>
    </th>
  </tr>
EOQ;

// cycle through rows
$row = 0;
while($entry_detail = $entry_result->fetch_array(MYSQLI_ASSOC))
{
	// set color
	$row_color = (($row++)%2) ? $info_table_row_color : "";
	
	$entry_list .= <<<EOQ
	<tr bgcolor="$row_color">
	  <td>
	    $entry_detail[Entry_Category]
	  </td>
	  <td>
	    <a href="city_book_view.fro?entry_id=$entry_detail[Entry_ID]">$entry_detail[Entry_Name]</a>
	  </td>
	  <td>
	    $entry_detail[Name]
	  </td>
	  <td>
	    $entry_detail[Submitted_On]
	  </td>
	</tr>
EOQ;
}

$entry_list .= "</table>";

$entry_list = buildTextBox( $entry_list, "", $border, $border_bgcolor, $border_background, $inner_background, $inner_bgcolor );

// add sort form
$hidden_value_names = array("category", "site_id");
$hidden_value_values = array($category, $site_id);

$sort_form = buildSortForm($this_order_by, $order_dir, $last_order_by, $hidden_value_names, $hidden_value_values);

$page = <<<EOQ
$js
$header
<br>
<br>
$entry_list
$sort_form
EOQ;

echo $page;

include 'end_of_page.php';
?>