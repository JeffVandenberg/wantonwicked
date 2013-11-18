<?
$page_title = "ST View";
$page_content = "ST View";
$js = "";
$lookup_form = "";
$npc_login_link = "";
$sheet = "";
$logins = "";

// test if updating
if(isset($_POST['submit']))
{
  // get character information
  $character_id = $_POST['character_id'] + 0;
  $character_query = <<<EOQ
SELECT login.*, wod_characters.*, gm_login.Name as ST_Name, asst_login.Name as Asst_Name
FROM ((wod_characters INNER JOIN login ON wod_characters.primary_login_id = login.id) LEFT JOIN login AS gm_login on wod_characters.last_st_updated = gm_login.id) LEFT JOIN login AS asst_login ON wod_characters.last_asst_st_updated = asst_login.id
WHERE character_id=$character_id;
EOQ;
  $character_result = mysql_query($character_query) or die(mysql_error());
  $character_detail = mysql_fetch_array($character_result, MYSQL_ASSOC);
  
  // determine what type of update
	$viewed_sheet = false;
	
	if($userdata['is_admin'])
	{
		$viewed_sheet = true;
		$edit_show_sheet = true;
		$edit_name = true;
		$edit_vitals = true;
		$edit_is_npc = true;
		$edit_is_dead = true;
		$edit_location = true;
		$edit_concept = true;
		$edit_description = true;
		$edit_url = true;
		$edit_equipment = true;
		$edit_public_effects = true;
		$edit_group = true;
		$edit_exit_line = true;
		$edit_attributes = true;
		$edit_skills = true;
		$edit_perm_traits = true;
		$edit_temp_traits = true;
		$edit_powers = true;
		$edit_history = true;
		$edit_goals = true;
		$edit_login_note = true;
		$edit_experience = true;
		$show_st_notes = true;
		$view_is_asst = true;
		$view_is_st = true;
		$view_is_head = true;	
		$view_is_admin = true;
		$may_edit = true;
		$edit_cell = true;
		$calculate_derived = false;
	}
	
	if(!$viewed_sheet && $userdata['is_head'])
	{
		$viewed_sheet = true;
		$edit_show_sheet = false;
		$edit_name = true;
		$edit_vitals = true;
		$edit_is_npc = true;
		$edit_is_dead = true;
		$edit_location = true;
		$edit_concept = true;
		$edit_description = true;
		$edit_url = true;
		$edit_equipment = true;
		$edit_public_effects = true;
		$edit_group = true;
		$edit_exit_line = true;
		$edit_attributes = true;
		$edit_skills = true;
		$edit_perm_traits = true;
		$edit_temp_traits = true;
		$edit_powers = true;
		$edit_history = true;
		$edit_goals = true;
		$edit_login_note = true;
		$edit_experience = true;
		$show_st_notes = true;
		$view_is_asst = true;
		$view_is_st = true;
		$view_is_head = true;	
		$view_is_admin = false;
		$may_edit = true;
		$edit_cell = true;
		$calculate_derived = false;
	}
	
	if(!$viewed_sheet && $userdata['is_gm'])
	{
		$viewed_sheet = true;
		if(($character_detail['is_npc'] == 'Y'))
		{
			// partial update
			$edit_show_sheet = false;
			$edit_name = false;
			$edit_vitals = false;
			$edit_is_npc = false;
			$edit_is_dead = true;
			$edit_location = false;
			$edit_concept = false;
			$edit_description = true;
			$edit_url = false;
			$edit_equipment = true;
			$edit_public_effects = false;
			$edit_group = true;
			$edit_exit_line = true;
			$edit_attributes = false;
			$edit_skills = false;
			$edit_perm_traits = false;
			$edit_temp_traits = true;
			$edit_powers = false;
			$edit_history = false;
			$edit_goals = true;
			$edit_login_note = false;
			$edit_experience = false;
			$show_st_notes = true;
			$view_is_asst = false;
			$view_is_st = false;
			$view_is_head = false;	
			$view_is_admin = false;
			$may_edit = true;
			$edit_cell = false;
			$calculate_derived = false;
		}
		else
		{
			// open update
			$edit_show_sheet = false;
			$edit_name = true;
			$edit_vitals = true;
			$edit_is_npc = true;
			$edit_is_dead = true;
			$edit_location = true;
			$edit_concept = true;
			$edit_description = true;
			$edit_url = true;
			$edit_equipment = true;
			$edit_public_effects = true;
			$edit_group = true;
			$edit_exit_line = true;
			$edit_attributes = true;
			$edit_skills = true;
			$edit_perm_traits = true;
			$edit_temp_traits = true;
			$edit_powers = true;
			$edit_history = true;
			$edit_goals = true;
			$edit_login_note = true;
			$edit_experience = true;
			$show_st_notes = true;
			$view_is_asst = false;
			$view_is_st = true;
			$view_is_head = false;	
			$view_is_admin = false;
			$may_edit = true;
			$edit_cell = true;
			$calculate_derived = false;
		}
	}
	
	if(!$viewed_sheet && $userdata['is_asst'])
	{
  	//echo "Asst Update<br>";
		$viewed_sheet = true;
		//$may_full_view = ($character_detail['Is_NPC'] == 'N') && ($character_detail['City'] == $userdata['city']) && (($character_detail['Is_Sanctioned'] == '') || ($character_detail['Cell_ID'] == $userdata['cell_id']) || ($character_detail['Cell_ID'] == 'No Preference'));
		//$may_full_view = (($character_detail['Is_NPC'] == 'N') && (($character_detail['City'] == $userdata['city']) && (($character_detail['Is_Sanctioned'] == '') || ($character_detail['Cell_ID'] == $userdata['cell_id']) || ($character_detail['Cell_ID'] == 'No Preference') || ($character_detail['Cell_ID'] == ''))));
    $may_full_view = (($character_detail['Is_NPC'] == 'N') && ((($character_detail['City'] == $userdata['city']) || ($character_detail['Is_Sanctioned'] == '')) && (($character_detail['Is_Sanctioned'] == '') || ($character_detail['Cell_ID'] == $userdata['cell_id']) || ($character_detail['Cell_ID'] == 'No Preference') || ($character_detail['Cell_ID'] == ''))));		
    
		if($may_full_view)
		{
  		/*echo "Do Full Update<br>";
  		while(list($key, $value) = each($_POST))
  		{
    		echo "POST: $key: $value<br>";
  		}*/
			$edit_show_sheet = false;
			$edit_name = true;
			$edit_vitals = true;
			$edit_is_npc = true;
			$edit_is_dead = true;
			$edit_location = true;
			$edit_concept = true;
			$edit_description = true;
			$edit_url = true;
			$edit_equipment = true;
			$edit_public_effects = true;
			$edit_group = true;
			$edit_exit_line = true;
			$edit_attributes = true;
			$edit_skills = true;
			$edit_perm_traits = true;
			$edit_temp_traits = true;
			$edit_powers = true;
			$edit_history = true;
			$edit_goals = true;
			$edit_login_note = true;
			$edit_experience = true;
			$show_st_notes = true;
			$view_is_asst = true;
			$view_is_st = false;
			$view_is_head = false;	
			$view_is_admin = false;
			$may_edit = true;
			$edit_cell = true;
			$calculate_derived = false;
		}
		else
		{
  		//echo "Do Partial Update<br>";
			$edit_show_sheet = false;
			$edit_name = false;
			$edit_vitals = false;
			$edit_is_npc = false;
			$edit_is_dead = true;
			$edit_location = false;
			$edit_concept = false;
			$edit_description = true;
			$edit_url = false;
			$edit_equipment = true;
			$edit_public_effects = false;
			$edit_group = true;
			$edit_exit_line = true;
			$edit_attributes = false;
			$edit_skills = false;
			$edit_perm_traits = false;
			$edit_temp_traits = true;
			$edit_powers = false;
			$edit_history = false;
			$edit_goals = true;
			$edit_login_note = false;
			$edit_experience = false;
			$show_st_notes = true;
			$view_is_asst = false;
			$view_is_st = false;
			$view_is_head = false;	
			$view_is_admin = false;
			$may_edit = true;
			$edit_cell = true;
			$calculate_derived = false;
		}
	}
	if($viewed_sheet)
	{
		updateWoDSheet($_POST, $edit_show_sheet, $edit_name, $edit_vitals, $edit_is_npc, $edit_is_dead, $edit_location, $edit_concept, $edit_description, $edit_url, $edit_equipment, $edit_public_effects, $edit_group, $edit_exit_line, $edit_attributes, $edit_skills, $edit_perm_traits, $edit_temp_traits, $edit_powers, $edit_history, $edit_goals, $edit_login_note, $edit_experience, $show_st_notes, $view_is_asst, $view_is_st, $view_is_head, $view_is_admin, $may_edit, $edit_cell);
  }
}

// test if viewing
$view_character_id = (isset($_GET['view_character_id'])) ? $_GET['view_character_id'] + 0 : 0;
$view_character_name = (isset($_POST['view_character_name'])) ? htmlspecialchars($_POST['view_character_name']) : "";

if($view_character_id || $view_character_name)
{
	// get character information
	if($view_character_id)
	{
  	$character_query = <<<EOQ
SELECT login.*, wod_characters.*, gm_login.Name as ST_Name, asst_login.Name as Asst_Name
FROM ((wod_characters INNER JOIN login ON wod_characters.primary_login_id = login.id) LEFT JOIN login AS gm_login on wod_characters.last_st_updated = gm_login.id) LEFT JOIN login AS asst_login ON wod_characters.last_asst_st_updated = asst_login.id
WHERE character_id=$view_character_id;
EOQ;
	}
	if($view_character_name)
	{
  	$character_query = <<<EOQ
SELECT login.*, wod_characters.*, gm_login.Name as ST_Name, asst_login.Name as Asst_Name
FROM ((wod_characters INNER JOIN login ON wod_characters.primary_login_id = login.id) LEFT JOIN login AS gm_login on wod_characters.last_st_updated = gm_login.id) LEFT JOIN login AS asst_login ON wod_characters.last_asst_st_updated = asst_login.id
WHERE character_name='$view_character_name';
EOQ;
	}
	
	$character_result = mysql_query($character_query) or die(mysql_error());
	
	if(mysql_num_rows($character_result))
	{
		// found character
		$character_detail = mysql_fetch_array($character_result, MYSQL_ASSOC);
		$character_type = $character_detail['Character_Type'];
		
		// determine what sort of view to use
		$viewed_sheet = false;
		
		if($userdata['is_admin'])
		{
			$viewed_sheet = true;
			$edit_show_sheet = true;
			$edit_name = true;
			$edit_vitals = true;
			$edit_is_npc = true;
			$edit_is_dead = true;
			$edit_location = true;
			$edit_concept = true;
			$edit_description = true;
			$edit_url = true;
			$edit_equipment = true;
			$edit_public_effects = true;
			$edit_group = true;
			$edit_exit_line = true;
			$edit_is_npc = true;
			$edit_attributes = true;
			$edit_skills = true;
			$edit_perm_traits = true;
			$edit_temp_traits = true;
			$edit_powers = true;
			$edit_history = true;
			$edit_goals = true;
			$edit_login_note = true;
			$edit_experience = true;
			$show_st_notes = true;
			$view_is_asst = true;
			$view_is_st = true;
			$view_is_head = true;	
			$view_is_admin = true;
			$may_edit = true;
			$edit_cell = true;
			$calculate_derived = false;
		}
		
		if(!$viewed_sheet && $userdata['is_head'])
		{
			$viewed_sheet = true;
			$edit_show_sheet = false;
			$edit_name = true;
			$edit_vitals = true;
			$edit_is_npc = true;
			$edit_is_dead = true;
			$edit_location = true;
			$edit_concept = true;
			$edit_description = true;
			$edit_url = true;
			$edit_equipment = true;
			$edit_public_effects = true;
			$edit_group = true;
			$edit_exit_line = true;
			$edit_is_npc = true;
			$edit_attributes = true;
			$edit_skills = true;
			$edit_perm_traits = true;
			$edit_temp_traits = true;
			$edit_powers = true;
			$edit_history = true;
			$edit_goals = true;
			$edit_login_note = true;
			$edit_experience = true;
			$show_st_notes = true;
			$view_is_asst = true;
			$view_is_st = true;
			$view_is_head = true;	
			$view_is_admin = false;
			$may_edit = true;
			$edit_cell = true;
			$calculate_derived = false;
		}
		
		if(!$viewed_sheet && $userdata['is_gm'])
		{
			$viewed_sheet = true;
			if(($character_detail['is_npc'] == 'Y'))
			{
				// partial update
				$edit_show_sheet = false;
				$edit_name = false;
				$edit_vitals = false;
				$edit_is_npc = false;
				$edit_is_dead = true;
				$edit_location = false;
				$edit_concept = false;
				$edit_description = true;
				$edit_url = false;
				$edit_equipment = true;
				$edit_public_effects = false;
				$edit_group = true;
				$edit_exit_line = true;
				$edit_is_npc = false;
				$edit_attributes = false;
				$edit_skills = false;
				$edit_perm_traits = false;
				$edit_temp_traits = true;
				$edit_powers = false;
				$edit_history = false;
				$edit_goals = true;
				$edit_login_note = false;
				$edit_experience = false;
				$show_st_notes = true;
				$view_is_asst = false;
				$view_is_st = true;
				$view_is_head = false;	
				$view_is_admin = false;
				$may_edit = true;
				$edit_cell = false;
				$calculate_derived = false;
			}
			else
			{
				// open update
				$edit_show_sheet = false;
				$edit_name = true;
				$edit_vitals = true;
				$edit_is_npc = true;
				$edit_is_dead = true;
				$edit_location = true;
				$edit_concept = true;
				$edit_description = true;
				$edit_url = true;
				$edit_equipment = true;
				$edit_public_effects = true;
				$edit_group = true;
				$edit_exit_line = true;
				$edit_is_npc = true;
				$edit_attributes = true;
				$edit_skills = true;
				$edit_perm_traits = true;
				$edit_temp_traits = true;
				$edit_powers = true;
				$edit_history = true;
				$edit_goals = true;
				$edit_login_note = true;
				$edit_experience = true;
				$show_st_notes = true;
				$view_is_asst = false;
				$view_is_st = true;
				$view_is_head = false;	
				$view_is_admin = false;
				$may_edit = true;
				$edit_cell = true;
				$calculate_derived = false;
			}
		}
		
		if(!$viewed_sheet && $userdata['is_asst'])
		{
			$viewed_sheet = true;
			//echo $character_detail['Is_NPC'] . " : " . $character_detail['City'] . " : " . $userdata['city'] . " : " . $character_detail['Is_Sanctioned'] . " : " . $character_detail['Cell_ID'] . " : " . $userdata['cell_id'] . "<br>";
			$may_full_view = (($character_detail['Is_NPC'] == 'N') && ((($character_detail['City'] == $userdata['city']) || ($character_detail['Is_Sanctioned'] == '')) && (($character_detail['Is_Sanctioned'] == '') || ($character_detail['Cell_ID'] == $userdata['cell_id']) || ($character_detail['Cell_ID'] == 'No Preference') || ($character_detail['Cell_ID'] == ''))));
			
			if($may_full_view)
			{
				$edit_show_sheet = false;
				$edit_name = true;
				$edit_vitals = true;
				$edit_is_npc = true;
				$edit_is_dead = true;
				$edit_location = true;
				$edit_concept = true;
				$edit_description = true;
				$edit_url = true;
				$edit_equipment = true;
				$edit_public_effects = true;
				$edit_group = true;
				$edit_exit_line = true;
				$edit_is_npc = true;
				$edit_attributes = true;
				$edit_skills = true;
				$edit_perm_traits = true;
				$edit_temp_traits = true;
				$edit_powers = true;
				$edit_history = true;
				$edit_goals = true;
				$edit_login_note = true;
				$edit_experience = true;
				$show_st_notes = true;
				$view_is_asst = true;
				$view_is_st = false;
				$view_is_head = false;	
				$view_is_admin = false;
				$may_edit = true;
				$edit_cell = true;
				$calculate_derived = false;
			}
			else
			{
				$edit_show_sheet = false;
				$edit_name = false;
				$edit_vitals = false;
				$edit_is_npc = false;
				$edit_is_dead = true;
				$edit_location = false;
				$edit_concept = false;
				$edit_description = true;
				$edit_url = false;
				$edit_equipment = true;
				$edit_public_effects = false;
				$edit_group = true;
				$edit_exit_line = true;
				$edit_is_npc = false;
				$edit_attributes = false;
				$edit_skills = false;
				$edit_perm_traits = false;
				$edit_temp_traits = true;
				$edit_powers = false;
				$edit_history = false;
				$edit_goals = true;
				$edit_login_note = false;
				$edit_experience = false;
				$show_st_notes = true;
				$view_is_asst = false;
				$view_is_st = false;
				$view_is_head = false;	
				$view_is_admin = false;
				$may_edit = true;
				$edit_cell = true;
				$calculate_derived = false;
			}
		}
		
		if($viewed_sheet)
		{
			$character_sheet = buildWoDSheet($character_detail, $character_type, $edit_show_sheet, $edit_name, $edit_vitals, $edit_is_npc, $edit_is_dead, $edit_location, $edit_concept, $edit_description, $edit_url, $edit_equipment, $edit_public_effects, $edit_group, $edit_exit_line, $edit_is_npc, $edit_attributes, $edit_skills, $edit_perm_traits, $edit_temp_traits, $edit_powers, $edit_history, $edit_goals, $edit_login_note, $edit_experience, $show_st_notes, $view_is_asst, $view_is_st, $view_is_head, $view_is_admin, $may_edit, $edit_cell, $calculate_derived);
			$character_id = $character_detail['Character_ID'];
			
			$sheet = <<<EOQ
<iframe src="blank.html" name="char_info" id="char_info" width="1" height="1" border="0" frameborder="0" scrolling="no">
</iframe>
<br>
<form name="character_sheet" id="character_sheet" method="post" action="$_SERVER[PHP_SELF]?action=st_view">
<div align="center">
<a href="storyteller_index.php?action=profile_lookup&profile_name=$character_detail[Name]">View all of $character_detail[Name]'s characters</a>
</div>
<div align="center" name="char_sheet" id="char_sheet">
$character_sheet
</div>
</form>
EOQ;
		}
		
		if(($character_detail['Is_NPC'] == 'Y') && ($character_detail['Head_Sanctioned'] == 'Y') && (strpos($character_detail['Character_Name'], "Cell") === FALSE))
		{
  		$npc_login_link = <<<EOQ
<div align="center">
<a href="character_interface.php?character_id=$character_detail[Character_ID]&log_npc=y" target="_blank">Log in as $character_detail[Character_Name]</a><br>
<a href="notes.php?action=character&character_id=$character_detail[Character_ID]&log_npc=y" target="_blank">View NPC Notes</a></div>
<br>
EOQ;

		}
		
	}
	else
	{
		// did not find the character
		$sheet = "Failed to find Character";
	}
}

// build page
$lookup_form = <<<EOQ
<form method="post" action="$_SERVER[PHP_SELF]?action=st_view">
<table border="0" cellpadding="2" cellspacing="2" class="normal_text">
	<tr>
		<td>
			Character Name:
			<input type="text" name="view_character_name" size="20" maxlength="35">
		</td>
		<td>
			<input type="submit" value="View Character">
		</td>
	</tr>
</table>
</form>
EOQ;

		$java_script = <<<EOQ
<script language="javascript">
function changeDots (tag_name, value, number_of_dots, remove)
{
	// if is the same value then set to 0
	if((value == document.getElementById(tag_name).value) && remove)
	{
		value = 0;
	}
	
	// determine character type
	var character_type = document.getElementById("character_type").value
	character_type = character_type.toLowerCase();
	
	// cycle through the dots to fill up the values up to the selected value
	for(i = 1; i <= Number(number_of_dots); i++)
	{
		if(i <= value)
		{
			document.getElementById(tag_name+i).src="img/" + character_type + "_filled.gif";
		}
		else
		{
			document.getElementById(tag_name+i).src="img/empty.gif";
		}
	}
	
	document.getElementById(tag_name).value = value;
}

function changeSheet(character_type)
{
  var sURL = "get_sheet.php?action=st_view&character_id=$character_id&character_type="+character_type;
  window.char_info.location.href = sURL;
}

function SubmitCharacter()
{
	window.document.character_sheet.submit();
}
</script>
EOQ;


$page_content = <<<EOQ
<span class="highlight">Quick Links</span>: 
<a href="storyteller_index.php">Storyteller Homepage</a> -
<a href="index.php">WantonWicked Homepage</a>
$lookup_form
$npc_login_link
$sheet
$logins
EOQ;
?>