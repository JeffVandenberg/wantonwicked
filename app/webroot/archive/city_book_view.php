<?
/********************************************************
* city_book_view.fro
* Author: Jeff Vandenberg
* Date: 12-Feb-04
* Purpose: Display public information 
********************************************************/

// get entry_id
$entry_id = 0;
$entry_id = (isset($_POST['entry_id'])) ? $_POST['entry_id'] +0 : $entry_id;
$entry_id = (isset($_GET['entry_id'])) ? $_GET['entry_id'] +0 : $entry_id;

$page_title = "View Entry #$entry_id";
include 'start_of_page.php';
include 'buildCityBookEntry.php';

// page variables
$page = "";
$js = "";
$alert = "";
$form = "";

// view entry
$entry_query = <<<EOQ
SELECT approved.Name AS Approved_Name, submitted.Name AS Submitted_Name, city_book.*
FROM (city_book LEFT JOIN login AS approved ON city_book.approved_by = approved.id) LEFT JOIN login AS submitted ON city_book.submitted_by = submitted.id
WHERE city_book.entry_id = $entry_id AND is_approved='Y' and is_deleted='N';
EOQ;

$entry_result = $mysqli->query($entry_query);

// make sure that there is an actual entry
if($entry_result->num_rows)
{
  // get details
  $entry_detail = $entry_result->fetch_array(MYSQLI_ASSOC);

  // initialize parameters for function call
  $show_public = true;
  $show_private = false;
  $show_secret = false;
  $may_edit = false;
  $is_editting = false;

  // test if entry is approved
  // do function call
  $form = buildCityBookEntry($entry_detail, $show_public, $show_private, $show_secret, $may_edit, $is_editting);
    
  $form = buildTextBox( $form, "", $border, $border_bgcolor, $border_background, $inner_background, $inner_bgcolor );
}
else
{
  // didn't find entry
  $alert = <<<EOQ
<span class="red_highlight">There is no entry with that ID.</span>
EOQ;
  $alert = buildTextBox( $alert, "", $border, $border_bgcolor, $border_background, $inner_background, $inner_bgcolor );
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