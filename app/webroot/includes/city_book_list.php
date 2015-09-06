<?
$city = (isset($_GET['city'])) ? htmlspecialchars($_GET['city']) : "New Orleans";
$city = (isset($_POST['city'])) ? htmlspecialchars($_POST['city']) : $city;

$category = (isset($_GET['category'])) ? htmlspecialchars($_GET['category']) : "All";
$category = (isset($_POST['category'])) ? htmlspecialchars($_POST['category']) : $category;

$page_title = "List City Book Entries: $category";

// page variables
$header = "";
$entry_list = "";
$page = "";
$js = "";
$sort_form = "";

// validate city
$cities = array("The City");
$city = (in_array($city, $cities, true)) ? $city : "The City";

// build header
$header = <<<EOQ
Welcome to the Wanton Wicked City Book for $city. Feel free to browse through any parts that interest you. All information provided is to be taken as In Character information that you can find out if your character does any looking around.
EOQ;

// list entries sorted by category then name then by ID
// view only approved
$entry_query = "";

if($category == 'All')
{
  // build list of all entries
  $entry_query = "select login.Name, city_book.* from city_book left join login on city_book.submitted_by = login.id where is_approved='Y' and is_deleted='N' and city='$city' order by city, entry_category, entry_name;";

}
else
{
  // build list of entries only from selected category
  $entry_query = "select login.Name, city_book.* from city_book left join login on city_book.submitted_by = login.id where is_approved='Y' and is_deleted='N' and city='$city' and entry_category='$category' order by city, entry_category, entry_name;";

}

$entry_result = mysql_query($entry_query) || die(mysql_error());;

$entry_list = <<<EOQ
<table border="0" cellspacing="2" cellpadding="2" class="normal_text">
  <tr>
    <th class="contentheading" colspan="4">
      $city City Book: $category
    </th>
  </tr>
  <tr bgcolor="#000000">
    <th>
      Category
    </th>
    <th>
      Name
    </th>
    <th>
      Submitted By
    </td>
    <th>
      Submitted On
    </th>
  </tr>
EOQ;

// cycle through rows
$row = 0;
while($entry_detail = mysql_fetch_array($entry_result, MYSQL_ASSOC))
{
	// set color
	$row_color = (($row++)%2) ? "#443a33" : "";
	
	$entry_list .= <<<EOQ
	<tr bgcolor="$row_color">
	  <td>
	    $entry_detail[Entry_Category]
	  </td>
	  <td>
	    <a href="city_book.php?action=view&entry_id=$entry_detail[Entry_ID]&city=$entry_detail[City]&category=$entry_detail[Entry_Category]">$entry_detail[Entry_Name]</a>
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

$page_content = <<<EOQ
$js
$header
<br>
<br>
$entry_list
EOQ;

?>