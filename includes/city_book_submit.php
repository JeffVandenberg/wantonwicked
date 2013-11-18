<?
$page_title = "Submit City Book Entry";

// page variables
$page = "";
$java_script = "";
$alert = "";
$form = "";
$show_form = true;

// form variables
$entry_name = "";
$entry_category = "";
$city = "";
$public_information = "";
$private_information = "";
$secret_information = "";
$district_rank = 0;
$population_rank = 0;
$relative_wealth = 0;
$crime = 0;
$appearance = 0;
$imperial_rank = 0;
$production = 0;
$political_influence = 0;
$corruption = 0;
$popularity = 0;
$group_name = "";

// test if updating
if(isset($_POST['action']))
{
	$entry_name = htmlspecialchars($_POST['entry_name']);
	$entry_category = htmlspecialchars($_POST['entry_category']);
	$city = $_POST['city'];
	$public_information = str_replace("\n", "<br>", htmlspecialchars($_POST['public_information']));
	$private_information = str_replace("\n", "<br>", htmlspecialchars($_POST['private_information']));
	$secret_information = str_replace("\n", "<br>", htmlspecialchars($_POST['secret_information']));
	$district_rank = $_POST['district_rank'] +0;
	$population_rank = $_POST['population_rank'] +0;
	$relative_wealth = $_POST['relative_wealth'] +0;
	$crime = $_POST['crime'] +0;
	$appearance = $_POST['appearance'] +0;
	$imperial_rank = $_POST['imperial_rank'] +0;
	$production = $_POST['production'] +0;
	$political_influence = $_POST['political_influence'] +0;
	$corruption = $_POST['corruption'] +0;
	$popularity = $_POST['popularity'] +0;
	$is_approved = "";
	$now = date('Y-m-d H:i:s');
	$is_deleted = 'N';
	$group_name = htmlspecialchars($_POST['group_name']);
	$udf4 = "";
	$entry_id = getNextID($connection, "city_book", "entry_id");
	
	$insert_query = "insert into city_book values ($entry_id, $userdata[site_id], '$entry_name', '$entry_category', '$public_information', '$private_information', '$secret_information', $district_rank, $population_rank, $relative_wealth, $crime, $appearance, $imperial_rank, $production, $political_influence, $corruption, $popularity, '$is_approved', $userdata[user_id], '$now', 0, '0', '$is_deleted', '$city', '$group_name', '$udf4');";
	$insert_result = mysql_query($insert_query) or die(mysql_error());
	
	$java_script = <<<EOQ
<script language="javascript">
  window.document.location.href = "$_SERVER[PHP_SELF]?action=st_view&entry_id=$entry_id";
</script>
EOQ;

	$show_form = false;

}

// test if show form
if($show_form)
{
	// build necessary js
	$java_script = <<<EOQ
<script language="javascript">
  function submitForm()
  {
	  var temp_name = window.document.city_book_form.entry_name.value;
	  
		if(temp_name.match(/\w/g))
		{
			window.document.city_book_form.submit();
		}
		else
		{
			alert('Please Enter a Title');
		}
  }
</script>
EOQ;

	$categories = array("Overview", "Persona", "Location", "Custom", "District", "Journal", "Threat");
	$entry_category = buildSelect($entry_category, $categories, $categories, "entry_category");
	
	$cities = array("The City");
	$city_select = buildSelect($city, $cities, $cities, "city");
	
	// build form
	$form = <<<EOQ
<form name="city_book_form" id="city_book_form" method="post" action="$_SERVER[PHP_SELF]?action=submit">
<table border="0" cellpadding="2" cellspacing="2" class="normal_text">
	<tr>
		<td class="highlight">
			Entry Name:
		</td>
		<td colspan="3">
			<input type="text" name="entry_name" id="entry_name" value="$entry_name" size="40" maxlength="100">
		</td>
	</tr>
	<tr>
		<td class="highlight">
			City:
		</td>
		<td colspan="3">
			$city_select
		</td>
	</tr>
	<tr>
		<td class="highlight">
			Category:
		</td>
		<td colspan="3">
			$entry_category
		</td>
	</tr>
	<tr>
		<td class="highlight">
			Pack/Cabal/etc Name:
		</td>
		<td colspan="3">
			<input type="text" name="group_name" id="group_name" value="$group_name" size="40" maxlength="45">
		</td>
	</tr>
	<tr valign="top">
		<td colspan="4">
			<span class="highlight">Public Information:</span> Available to all Players<br>
			<textarea name="public_information" id="public_information" rows="10" cols="50" wrap="physical">$public_information</textarea>
		</td>
	</tr>
	<tr valign="top">
		<td colspan="4">
			<span class="highlight">Private Information:</span> Available to Cell STs and STs<br>
			<textarea name="private_information" id="private_information" rows="10" cols="50" wrap="physical">$private_information</textarea>
		</td>
	</tr>
	<tr valign="top">
		<td colspan="4">
			<span class="highlight">Secret Information:</span> Available only to Full STs<br>
			<textarea name="secret_information" id="secret_information" rows="10" cols="50" wrap="physical">$secret_information</textarea>
		</td>
	</tr>
  <tr>
    <td width="20%" class="highlight">
      Police Presence:
    </td>
    <td width="30%">
      <input type="text" name="district_rank" id="district_rank" value="$district_rank" size="3" maxlength="2">
    </td>
    <td width="20%" class="highlight">
      Paranormal Activity:
    </td>
    <td width="30%">
      <input type="text" name="imperial_rank" id="imperial_rank" value="$imperial_rank" size="3" maxlength="2">
    </td>
  </tr>
  <tr>
    <td width="20%" class="highlight">
      Relative Wealth:
    </td>
    <td width="30%">
      <input type="text" name="relative_wealth" id="relative_wealth" value="$relative_wealth" size="3" maxlength="2">
    </td>
    <td width="20%" class="highlight">
      Crime:
    </td>
    <td width="30%">
      <input type="text" name="crime" id="crime" value="$crime" size="3" maxlength="2">
    </td>
  </tr>
  <tr>
    <td width="20%" class="highlight">
      Appearance:
    </td>
    <td width="30%">
      <input type="text" name="appearance" id="appearance" value="$appearance" size="3" maxlength="2">
    </td>
    <td width="20%" class="highlight">
      Population Rank:
    </td>
    <td width="30%">
      <input type="text" name="population_rank" id="population_rank" value="$population_rank" size="3" maxlength="2">
    </td>
  </tr>
  <tr>
    <td width="20%" class="highlight">
      Economic Value:
    </td>
    <td width="30%">
      <input type="text" name="production" id="production" value="$production" size="3" maxlength="2">
    </td>
    <td width="20%" class="highlight">
      Political Influence:
    </td>
    <td width="30%">
      <input type="text" name="political_influence" id="political_influence" value="$political_influence" size="3" maxlength="2">
    </td>
  </tr>
  <tr>
    <td width="20%" class="highlight">
      Corruption:
    </td>
    <td width="30%">
      <input type="text" name="corruption" id="corruption" value="$corruption" size="3" maxlength="2">
    </td>
    <td width="20%" class="highlight">
      Popularity:
    </td>
    <td width="30%">
      <input type="text" name="popularity" id="popularity" value="$popularity" size="3" maxlength="2">
    </td>
  </tr>
  <tr>
    <td colspan="4" align="center">
			<input type="hidden" name="action" id="action" value="update">
			<input type="submit" value="Submit Entry" onClick="submitForm();return false;">
    </td>
  </tr>
</table>
</form>
EOQ;
}

// build page
$page_content = <<<EOQ
$java_script
$alert
$form
EOQ;

?>