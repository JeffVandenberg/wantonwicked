<?
// get entry_id
$entry_id = (isset($_POST['entry_id'])) ? $_POST['entry_id'] +0 : 1;
$entry_id = (isset($_GET['entry_id'])) ? $_GET['entry_id'] +0 : $entry_id;

$page_title = "View Entry #$entry_id";

// page variables
$page = "";
$java_script = "";
$alert = "";
$form = "";
$edit = false; 


// test if updating
if(isset($_POST['action']))
{
	// get old values, used for districts
	$entry_query = "select * from city_book where entry_id=$entry_id;";
	$entry_result = mysql_query($entry_query) || die(mysql_error());
	$entry_detail = mysql_fetch_array($entry_result, MYSQL_ASSOC);
	
	// get values
	$entry_name = htmlspecialchars($_POST['entry_name']);
	$entry_category = htmlspecialchars($_POST['entry_category']);
	$city = htmlspecialchars($_POST['city']);
	
	$public_information = (isset($_POST['public_information'])) ? str_replace("\n", "<br>", $_POST['public_information']) : $entry_detail['Public_Information'];
	$private_information = (isset($_POST['private_information'])) ? str_replace("\n", "<br>", $_POST['private_information']) : $entry_detail['Private_Information'];
	$secret_information = (isset($_POST['secret_information'])) ? str_replace("\n", "<br>", $_POST['secret_information']) : $entry_detail['Secret_Information'];
	$district_rank = (isset($_POST['district_rank'])) ? $_POST['district_rank'] +0 : $entry_detail['District_Rank'];
	$population_rank = (isset($_POST['population_rank'])) ? $_POST['population_rank'] +0 : $entry_detail['Population_Rank'];
	$relative_wealth = (isset($_POST['relative_wealth'])) ? $_POST['relative_wealth'] +0 : $entry_detail['Relative_Wealth'];
	$crime = (isset($_POST['crime'])) ? $_POST['crime'] +0 : $entry_detail['Crime'];
	$appearance = (isset($_POST['appearance'])) ? $_POST['appearance'] +0 : $entry_detail['Appearance'];
	$imperial_rank = (isset($_POST['imperial_rank'])) ? $_POST['imperial_rank'] +0 : $entry_detail['Imperial_Rank'];
	$production = (isset($_POST['production'])) ? $_POST['production'] +0 : $entry_detail['Production'];
	$political_influence = (isset($_POST['political_influence'])) ? $_POST['political_influence'] +0 : $entry_detail['Political_Influence'];
	$corruption = (isset($_POST['corruption'])) ? $_POST['corruption'] +0 : $entry_detail['Corruption'];
	$popularity = (isset($_POST['popularity'])) ? $_POST['popularity'] +0 : $entry_detail['Popularity'];
	$is_approved = (isset($_POST['is_approved'])) ? $_POST['is_approved'] : "";
	$now = date('Y-m-d H:i:s');
	$group_name = (isset($_POST['group_name'])) ? $_POST['group_name'] : "";
	
	$update_query = "update city_book set entry_name='$entry_name', entry_category='$entry_category', public_information='$public_information', private_information='$private_information', secret_information='$secret_information', district_rank=$district_rank, population_rank=$population_rank, relative_wealth=$relative_wealth, crime=$crime, appearance=$appearance, imperial_rank=$imperial_rank, production=$production, political_influence=$political_influence, corruption=$corruption, popularity=$popularity, is_approved='$is_approved', approved_by=$userdata[user_id], approved_on='$now', city='$city', group_name='$group_name' where entry_id=$entry_id;";
	$update_result = mysql_query($update_query) || die(mysql_error());
	
	
}

// view entry
$entry_query = <<<EOQ
SELECT approved.Name AS Approved_Name, submitted.Name AS Submitted_Name, city_book.*
FROM (city_book LEFT JOIN login AS approved ON city_book.approved_by = approved.id) LEFT JOIN login AS submitted ON city_book.submitted_by = submitted.id
WHERE city_book.entry_id = $entry_id;
EOQ;

$entry_result = mysql_query($entry_query) || die(mysql_error());

// make sure that there is an actual entry
if(mysql_num_rows($entry_result))
{
  // get details
  $entry_detail = mysql_fetch_array($entry_result, MYSQL_ASSOC);

  // initialize parameters for function call
  $show_public = true;
  $show_private = false;
  $show_secret = false;
  $may_edit = false;
  $is_editting = false;
  $edit = (isset($_GET['edit']) && ($userdata['is_head'] || $userdata['is_admin'] || (($entry_detail['Submitted_By'] == $userdata['user_id']) && ($entry_detail['Is_Approved'] == '')))) ? true : false;

  // test if entry is approved
  $may_view = true;
  $show_private = ($userdata['is_asst'] || $userdata['is_gm'] || $userdata['is_head'] || $userdata['is_admin']);
  $show_secret = (($userdata['is_asst'] && ($userdata['city'] == $entry_detail['City'])) || $userdata['is_gm'] || $userdata['is_head'] || $userdata['is_admin']);
  $may_edit = ($userdata['is_head'] || $userdata['is_admin'] || (($entry_detail['Submitted_By'] == $userdata['user_id']) && ($entry_detail['Is_Approved'] == ''))) && !$edit;
  $is_editting = $edit;
  
  // test to see if alert flag was thrown
  if($may_view)
  {
    // do function call
    $form = <<<EOQ
<div align="center"><a href="$_SERVER[PHP_SELF]?action=st_list">Return to City Book List</a></div>
<br>
EOQ;
    
    $form .= buildCityBookEntry($entry_detail, $show_public, $show_private, $show_secret, $may_edit, $is_editting);
  }
}
else
{
  // didn't find entry
  $alert = <<<EOQ
<span class="red_highlight">There is no entry with that ID.</span>
EOQ;
}

// build page
$page_content = <<<EOQ
$java_script
$alert
$form
EOQ;
?>
