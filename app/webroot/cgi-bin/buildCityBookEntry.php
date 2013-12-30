<?
function buildCityBookEntry($entry_detail="", $show_public=true, $show_private=false, $show_secret=false, $may_edit=false, $is_editting=false)
{
  global $userdata;
	// function to build any sort of view of the entries of the city book.
	// $entry_detail = array passed from mysql_fetch_array, giving details of entry

	$view_action = (isset($_GET['action'])) ? $_GET['action'] : "view";
	
	// form variables && initialize
	$entry_name = $entry_detail['Entry_Name'];
	$entry_category = $entry_detail['Entry_Category'];
	$city = $entry_detail['City'];
	$public_information = $entry_detail['Public_Information'];
  $public_information_row = "";
	$public_references = "";
	$private_information = $entry_detail['Private_Information'];
  $private_information_row = "";
	$private_references = "";
	$secret_information = $entry_detail['Secret_Information'];
  $secret_information_row = "";
	$secret_references = "";
	$district_information = "";
	$district_rank = $entry_detail['District_Rank'];
	$population_rank = $entry_detail['Population_Rank'];
  $relative_wealth = $entry_detail['Relative_Wealth'];
	$crime = $entry_detail['Crime'];
	$appearance = $entry_detail['Appearance'];
	$imperial_rank = $entry_detail['Imperial_Rank'];
	$production = $entry_detail['Production'];
	$political_influence = $entry_detail['Political_Influence'];
	$corruption = $entry_detail['Corruption'];
	$popularity = $entry_detail['Popularity'];
	$is_approved = $entry_detail['Is_Approved'];
	$group_name = $entry_detail['Group_Name'];
	$group_name_row = "";
	$approved_on_row = "";
	$update_button_row = "";


	// get public references
	$public_reference_query = "select city_book.* from city_book_references left join city_book on city_book_references.target_entry_id = city_book.entry_id where city_book_references.source_entry_id = $entry_detail[Entry_ID] and type='public' and is_approved='Y' and is_deleted='N' order by entry_reference_id;";
	$public_reference_result = mysql_query($public_reference_query) or die(mysql_error());
		
	$public_references = "";
	while($public_reference_detail = mysql_fetch_array($public_reference_result, MYSQL_ASSOC))
	{
		$public_references .= <<<EOQ
<a href="$_SERVER[PHP_SELF]?action=$view_action&entry_id=$public_reference_detail[Entry_ID]&city=$public_reference_detail[City]&category=$public_reference_detail[Entry_Category]"Name>$public_reference_detail[Entry_Name]</a>,
EOQ;
	}
		
	// trim trailing comma
	if($public_references != "")
	{
		$public_references = substr($public_references, 0, strlen($public_references)-2);
	}
	
	// get private references
	$private_reference_query = "select city_book.* from city_book_references left join city_book on city_book_references.target_entry_id = city_book.entry_id where city_book_references.source_entry_id = $entry_detail[Entry_ID] and type='private' and is_approved='Y' and is_deleted='N' order by entry_reference_id;";
	$private_reference_result = mysql_query($private_reference_query) or die(mysql_error());
		
	$private_references = "";
	while($private_reference_detail = mysql_fetch_array($private_reference_result, MYSQL_ASSOC))
	{
		$private_references .= <<<EOQ
<a href="$_SERVER[PHP_SELF]?action=$view_action&entry_id=$private_reference_detail[Entry_ID]&city=$private_reference_detail[City]&category=$private_reference_detail[Entry_Category]"Name>$private_reference_detail[Entry_Name]</a>,
EOQ;
	}
		
	// trim trailing comma
	if($private_references != "")
	{
		$private_references = substr($private_references, 0, strlen($private_references)-2);
	}
	
	// get secret references
	$secret_reference_query = "select city_book.* from city_book_references left join city_book on city_book_references.target_entry_id = city_book.entry_id where city_book_references.source_entry_id = $entry_detail[Entry_ID] and type='secret' and is_approved='Y' and is_deleted='N' order by entry_reference_id;";
	$secret_reference_result = mysql_query($secret_reference_query) or die(mysql_error());
		
	$secret_references = "";
	while($secret_reference_detail = mysql_fetch_array($secret_reference_result, MYSQL_ASSOC))
	{
		$secret_references .= <<<EOQ
<a href="$_SERVER[PHP_SELF]?action=$view_action&entry_id=$secret_reference_detail[Entry_ID]&city=$secret_reference_detail[City]&category=$secret_reference_detail[Entry_Category]"Name>$secret_reference_detail[Entry_Name]</a>,
EOQ;
	}
	
	// trim trailing comma
	if($secret_references != "")
	{
		$secret_references = substr($secret_references, 0, strlen($secret_references)-2);
	}

	// test if editting or not
	if($is_editting)
	{
		// build editable fields
		$entry_name = <<<EOQ
<input type="text" name="entry_name" id="entry_name" value="$entry_name" size="40" maxlength="100">
EOQ;
    $group_name = <<<EOQ
<input type="text" name="group_name" id="group_name" value="$group_name" size="40" maxlength="45">
EOQ;

		$categories = array("Persona", "Location", "Custom", "District", "Journal", "Threat", "Overview");
		$entry_category = buildSelect($entry_category, $categories, $categories, "entry_category");
		
		$cities = array("The City");
		$city = buildSelect($city, $cities, $cities, "city");
	
		$public_information = str_replace("<br>", "\n", $public_information);
		$public_information = <<<EOQ
<textarea name="public_information" id="public_information" rows="10" cols="50" wrap="physical"Name>$public_information</textarea>
EOQ;
		$public_references = <<<EOQ
<iframe src="city_book_references.php?type=public&entry_id=$entry_detail[Entry_ID]" width="250" height="150" border="0" frameborder="0"></iframe>
EOQ;

    $private_information = str_replace("<br>", "\n", $private_information);
		$private_information = <<<EOQ
<textarea name="private_information" id="private_information" rows="10" cols="50" wrap="physical">$private_information</textarea>
EOQ;
		$private_references = <<<EOQ
<iframe src="city_book_references.php?type=private&entry_id=$entry_detail[Entry_ID]" width="250" height="150" border="0" frameborder="0"></iframe>
EOQ;

    $secret_information = str_replace("<br>", "\n", $secret_information);
		$secret_information = <<<EOQ
<textarea name="secret_information" id="secret_information" rows="10" cols="50" wrap="physical">$secret_information</textarea>
EOQ;
		$secret_references = <<<EOQ
<iframe src="city_book_references.php?type=secret&entry_id=$entry_detail[Entry_ID]" width="250" height="150" border="0" frameborder="0"></iframe>
EOQ;

		// set district information to edittable
		$district_rank = <<<EOQ
<input type="text" name="district_rank" id="district_rank" value="$district_rank" size="3" maxlength="2">
EOQ;
		$population_rank = <<<EOQ
<input type="text" name="population_rank" id="population_rank" value="$population_rank" size="3" maxlength="2">
EOQ;
       	 	$relative_wealth = <<<EOQ
<input type="text" name="relative_wealth" id="relative_wealth" value="$relative_wealth" size="3" maxlength="2">
EOQ;
		$crime = <<<EOQ
<input type="text" name="crime" id="crime" value="$crime" size="3" maxlength="2">
EOQ;
		$appearance = <<<EOQ
<input type="text" name="appearance" id="appearance" value="$appearance" size="3" maxlength="2">
EOQ;
		$imperial_rank = <<<EOQ
<input type="text" name="imperial_rank" id="imperial_rank" value="$imperial_rank" size="3" maxlength="2">
EOQ;
		$production = <<<EOQ
<input type="text" name="production" id="production" value="$production" size="3" maxlength="2">
EOQ;
		$political_influence = <<<EOQ
<input type="text" name="political_influence" id="political_influence" value="$political_influence" size="3" maxlength="2">
EOQ;
		$corruption = <<<EOQ
<input type="text" name="corruption" id="corruption" value="$corruption" size="3" maxlength="2">
EOQ;
		$popularity = <<<EOQ
<input type="text" name="popularity" id="popularity" value="$popularity" size="3" maxlength="2">
EOQ;

		// build form validation function
		$js = <<<EOQ
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
		// display update button row
		$is_approved_yes_check = ($entry_detail['Is_Approved'] == 'Y') ? "checked" : "";
		$is_approved_no_check = ($entry_detail['Is_Approved'] == 'N') ? "checked" : "";
		
		$is_approved = <<<EOQ
Yes: <input type="radio" name="is_approved" id="is_approved" value="Y" $is_approved_yes_check>
No: <input type="radio" name="is_approved" id="is_approved" value="N" $is_approved_no_check>
EOQ;


		$update_button_row = <<<EOQ
	<tr>
		<td colspan="4">
			<input type="hidden" name="action" id="action" value="update">
			<input type="hidden" name="entry_id" id="entry_id" value="$entry_detail[Entry_ID]">
			<input type="submit" value="Submit Entry" onClick="submitForm();return false;">
		</td>
	</tr>
EOQ;
	}

	// test to make row of public information
	if($show_public)
	{
		$public_information_row = <<<EOQ
	<tr>
		<td>
			&nbsp;
		</td>
		<td>
		</td>
	</tr>
	<tr valign="top">
		<td colspan="3">
			<span class="highlight">Public Information:</span> Available to all Players<br>
			$public_information
		</td>
		<td>
			<span class="highlight">Public References:</span><br>
			$public_references
		</td>
	</tr>
EOQ;
	}

	// test to make row of private information
	if($show_private)
	{
		$private_information_row = <<<EOQ
	<tr>
		<td>
			&nbsp;
		</td>
		<td>
		</td>
	</tr>
	<tr valign="top">
		<td colspan="3">
			<span class="highlight">Private Information:</span> Available to Cell STs and STs<br>
			$private_information
		</td>
		<td>
			<span class="highlight">Private References:</span><br>
			$private_references
		</td>
	</tr>
EOQ;
	}

	// test to make row of secret information
	if($show_secret)
	{
		$secret_information_row = <<<EOQ
	<tr>
		<td>
			&nbsp;
		</td>
		<td>
		</td>
	</tr>
	<tr valign="top">
		<td colspan="3">
			<span class="highlight">Secret Information:</span> Available only to Full STs<br>
			$secret_information
		</td>
		<td>
			<span class="highlight">Secret References:</span><br>
			$secret_references
		</td>
	</tr>
EOQ;
	}

	// test to make row of information for district
	if($entry_detail['Entry_Category'] == 'District' && ($show_private || $show_secret))
	{
		$district_information = <<<EOQ
	<tr>
		<td>
			&nbsp;
		</td>
		<td>
		</td>
	</tr>
  <tr>
    <td width="20%" class="highlight">
      Police Presense:
    </td>
    <td width="30%">
      $district_rank
    </td>
    <td width="20%" class="highlight">
      Paranormal Activity:
    </td>
    <td width="30%">
      $imperial_rank
    </td>
  </tr>
  <tr>
    <td width="20%" class="highlight">
      Economic Value:
    </td>
    <td width="30%">
      $relative_wealth
    </td>
    <td width="20%" class="highlight">
      Crime:
    </td>
    <td width="30%">
      $crime
    </td>
  </tr>
  <tr>
    <td width="20%" class="highlight">
      Appearance:
    </td>
    <td width="30%">
      $appearance
    </td>
    <td width="20%" class="highlight">
      Population Rank:
    </td>
    <td width="30%">
      $population_rank
    </td>
  </tr>
  <tr>
    <td width="20%" class="highlight">
      Production:
    </td>
    <td width="30%">
      $production
    </td>
    <td width="20%" class="highlight">
      Political Influence:
    </td>
    <td width="30%">
      $political_influence
    </td>
  </tr>
  <tr>
    <td width="20%" class="highlight">
      Corruption:
    </td>
    <td width="30%">
      $corruption
    </td>
    <td width="20%" class="highlight">
      Popularity:
    </td>
    <td width="30%">
      $popularity
    </td>
  </tr>
EOQ;
	}
	
	if(($may_edit || $is_editting) && ($userdata['is_head'] || $userdata['is_admin']))
	{
		// display approve row
		$approved_on_row = <<<EOQ
	<tr>
		<td class="highlight">
			Approved By:
		</td>
		<td>
			$entry_detail[Approved_Name]
		</td>
		<td class="highlight">
			Approved On:
		</td>
		<td>
			$entry_detail[Approved_On]
		</td>
	</tr>
	<tr>
		<td class="highlight">
			Is Approved:
		</td>
		<td>
			$is_approved
		</td>
		<td colspan="2">
		</td>
	</tr>
EOQ;
	}

	// test to create edit link
	$edit_link = "";
	if($may_edit && !$is_editting)
	{
		$edit_link = "&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"$_SERVER[PHP_SELF]?action=st_view&entry_id=$entry_detail[Entry_ID]&edit=y\" class=\"linkmain\">Edit Entry</a>";
	}
	
	if($group_name != "")
	{
  	$group_name_row = <<<EOQ
	<tr>
		<td class="highlight">
			Pack/Cabal/etc Name:
		</td>
		<td colspan="3">
			$group_name
		</td>
	</tr>
EOQ;

	}

	$entry_layout = <<<EOQ
<form name="city_book_form" id="city_book_form" method="post" action="$_SERVER[PHP_SELF]?action=st_view">
<table border="0" cellpadding="2" cellspacing="2" class="normal_text">
	<tr>
		<td class="highlight">
			Entry Name:
		</td>
		<td colspan="3">
			$entry_name
			$edit_link
		</td>
	</tr>
	<tr>
		<td class="highlight">
			City:
		</td>
		<td>
			$city
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
	$group_name_row
	$public_information_row
	$private_information_row
	$secret_information_row
	$district_information
	<tr>
		<td>
			&nbsp;
		</td>
		<td>
		</td>
	</tr>
	<tr>
		<td class="highlight" width="20%">
			Submitted By:
		</td>
		<td width="30%">
			$entry_detail[Submitted_Name]
		</td>
		<td class="highlight" width="20%">
			Submitted On:
		</td>
		<td width="30%">
			$entry_detail[Submitted_On]
		</td>
	</tr>
	$approved_on_row
	$update_button_row
</table>
</form>
EOQ;

	return $entry_layout;
}
?>
