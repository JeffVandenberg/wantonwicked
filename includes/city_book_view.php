<?
$entry_id = (isset($_POST['entry_id'])) ? $_POST['entry_id'] +0 : 0;
$entry_id = (isset($_GET['entry_id'])) ? $_GET['entry_id'] +0 : $entry_id;
$character_id = (isset($_GET['character_id'])) ? $_GET['character_id'] + 0 : 0;
$group_name = (isset($_GET['group_name'])) ? htmlspecialchars($_GET['group_name']) : "";

if(($group_name == "") && $character_id)
{
  // make sure that they can't try to view all blank
  $entry_id = -1;
}

// page variables
$page = "";
$js = "";
$alert = "";
$form = "";
$view_page = "normal";

// view entry
if($entry_id)
{
  $entry_query = <<<EOQ
SELECT approved.Name AS Approved_Name, submitted.Name AS Submitted_Name, city_book.*
FROM (city_book LEFT JOIN login AS approved ON city_book.approved_by = approved.id) LEFT JOIN login AS submitted ON city_book.submitted_by = submitted.id
WHERE city_book.entry_id = $entry_id AND is_approved='Y' and is_deleted='N';
EOQ;

}
else
{
  $view_page = "territory";
  $entry_query = <<<EOQ
SELECT approved.Name AS Approved_Name, submitted.Name AS Submitted_Name, city_book.*
FROM 
  ((city_book 
    LEFT JOIN login AS approved 
      ON city_book.approved_by = approved.id) 
    LEFT JOIN login AS submitted 
      ON city_book.submitted_by = submitted.id)
  INNER JOIN wod_characters
    ON wod_characters.friends = city_book.group_name
WHERE 
  city_book.group_name = '$group_name' 
  AND wod_characters.friends = '$group_name' 
  AND wod_characters.primary_login_id = $userdata[user_id]
  AND is_approved='Y' 
  and wod_characters.is_deleted='N'
  AND city_book.is_deleted='N'
  AND wod_characters.character_id = $character_id;
EOQ;
}

$entry_result = mysql_query($entry_query) or die(mysql_error());
//echo "$entry_query<br>";

// make sure that there is an actual entry
if(mysql_num_rows($entry_result))
{
  // get details
  $entry_detail = mysql_fetch_array($entry_result, MYSQL_ASSOC);
  $page_title = "View Entry: $entry_detail[City] - $entry_detail[Entry_Category] - $entry_detail[Entry_Name]";

  // initialize parameters for function call
  $show_public = true;
  $show_private = false;
  $show_secret = false;
  $may_edit = false;
  $is_editting = false;
  
  if($view_page == "territory")
  {
    $show_private = true;
  }

  // test if entry is approved
  // do function call
  $form = buildCityBookEntry($entry_detail, $show_public, $show_private, $show_secret, $may_edit, $is_editting);
}
else
{
  // didn't find entry
  $page_title = "Unknown Entry";
  $alert = <<<EOQ
<span class="red_highlight">There is no entry with that ID.</span><br>
EOQ;
}

// build page
$page_content = <<<EOQ
$js
$alert
$form
EOQ;

?>
