<?
// get character id
$character_id = (isset($_GET['character_id'])) ? $_GET['character_id'] + 0 : 0;
$character_id = (isset($_POST['character_id'])) ? $_POST['character_id'] + 0 : $character_id;

// test if updating
$page_title = "View Own Sheet";

// test to see if this character id is linked to the player
$character_query = <<<EOQ
SELECT wod.*, i.Icon_Name, gm_login.Name as ST_Name, asst_login.Name as Asst_Name
  FROM (((wod_characters AS wod INNER JOIN login_character_index AS lci ON wod.character_id = lci.character_id) LEFT JOIN icons AS i ON wod.icon = i.icon_id) LEFT JOIN login AS gm_login on wod.last_st_updated = gm_login.id) LEFT JOIN login AS asst_login ON wod.last_asst_st_updated = asst_login.id
 WHERE lci.login_id = $userdata[user_id]
   AND wod.character_id = $character_id;
EOQ;

//echo "$character_query<br>";
$character_result = mysql_query($character_query) or die(mysql_error()); 

if(mysql_num_rows($character_result))
{
	// may view the character
	$character_detail = mysql_fetch_array($character_result, MYSQL_ASSOC);
	
	// test if we're updating the character
	if(isset($_POST['submit']))
	{
		if(($character_detail['City'] != 'Side Game') && (($character_detail['Asst_Sanctioned'] == 'Y') || ($character_detail['Is_Sanctioned']) || ($character_detail['Head_Sanctioned'] == 'Y')))
		{
			$edit_show_sheet = true;
			$edit_name = false;
			$edit_vitals = false;
			$edit_is_npc = false;
			$edit_is_dead = false;
			$edit_location = false;
			$edit_concept = false;
			$edit_description = true;
			$edit_url = true;
			$edit_equipment = false;
			$edit_public_effects = false;
			$edit_group = false;
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
			$show_st_notes = false;
			$view_is_asst = false;
			$view_is_st = false;
			$view_is_head = false;	
			$view_is_admin = false;
			$may_edit = true;
			$edit_cell = false;
			
			updateWoDSheet($_POST, $edit_show_sheet, $edit_name, $edit_vitals, $edit_is_npc, $edit_is_dead, $edit_location, $edit_concept, $edit_description, $edit_url, $edit_equipment, $edit_public_effects, $edit_group, $edit_exit_line, $edit_attributes, $edit_skills, $edit_perm_traits, $edit_temp_traits, $edit_powers, $edit_history, $edit_goals, $edit_login_note, $edit_experience, $show_st_notes, $view_is_asst, $view_is_st, $view_is_head, $view_is_admin, $may_edit, $edit_cell);
			
			// requery for new stats
			$character_query = <<<EOQ
SELECT wod.*, i.Icon_Name, gm_login.Name as ST_Name, asst_login.Name as Asst_Name
  FROM (((wod_characters AS wod INNER JOIN login_character_index AS lci ON wod.character_id = lci.character_id) LEFT JOIN icons AS i ON wod.icon = i.icon_id) LEFT JOIN login AS gm_login on wod.last_st_updated = gm_login.id) LEFT JOIN login AS asst_login ON wod.last_asst_st_updated = asst_login.id
 WHERE wod.character_id = $character_id;
EOQ;
			$character_result = mysql_query($character_query) or die(mysql_error()); 
			$character_detail = mysql_fetch_array($character_result, MYSQL_ASSOC);
		}
		else
		{
			// attempt to process character
			$str_to_find = array('"', ",");
			$str_to_replace = array("", "");
			$character_name = addslashes(htmlspecialchars(str_replace($str_to_find, $str_to_replace, stripslashes($_POST['character_name']))));
			
			// verify that character name isn't in use already
			$name_check_query = "select character_id from wod_characters where character_name='$character_name' and character_id != $character_id;";
			$name_check_result = mysql_query($name_check_query) or die(mysql_error());
			
			if(mysql_num_rows($name_check_result))
			{
				// warn that there is already a character with that name
				$show_form = false;
				$page_content .= <<<EOQ
There is already a character with that name, please go back and give the character a different name.
EOQ;
		
			}
			else
			{
				// Do an open update of the character
				$show_form = false;
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
				$edit_login_note = false;
				$edit_experience = false;
				$show_st_notes = false;
				$view_is_asst = false;
				$view_is_st = false;
				$view_is_head = false;	
				$view_is_admin = false;
				$may_edit = true;
				$edit_cell = true;
				
				updateWoDSheet($_POST, $edit_show_sheet, $edit_name, $edit_vitals, $edit_is_npc, $edit_is_dead, $edit_location, $edit_concept, $edit_description, $edit_url, $edit_equipment, $edit_public_effects, $edit_group, $edit_exit_line, $edit_attributes, $edit_skills, $edit_perm_traits, $edit_temp_traits, $edit_powers, $edit_history, $edit_goals, $edit_login_note, $edit_experience, $show_st_notes, $view_is_asst, $view_is_st, $view_is_head, $view_is_admin, $may_edit, $edit_cell);
				
				// requery for new stats
				$character_query = <<<EOQ
SELECT wod.* 
FROM wod_characters AS wod INNER JOIN login_character_index AS lci ON wod.character_id = lci.character_id
WHERE lci.login_id = $userdata[user_id]
 AND wod.character_id = $character_id;
EOQ;
				$character_result = mysql_query($character_query) or die(mysql_error()); 
				$character_detail = mysql_fetch_array($character_result, MYSQL_ASSOC);
			}
		}
	}
	
	
	$page_title = "View Sheet: $character_detail[Character_Name]";
	
	if(($character_detail['City'] != 'Side Game') && (($character_detail['Asst_Sanctioned'] == 'Y') || ($character_detail['Is_Sanctioned']) || ($character_detail['Head_Sanctioned'] == 'Y')))
	{
		$character_type = $character_detail['Character_Type'];
		$edit_show_sheet = true;
		$edit_name = false;
		$edit_vitals = false;
		$edit_is_npc = false;
		$edit_is_dead = false;
		$edit_location = false;
		$edit_concept = false;
		$edit_description = true;
		$edit_url = true;
		$edit_equipment = false;
		$edit_public_effects = false;
		$edit_group = false;
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
		$show_st_notes = false;
		$view_is_asst = false;
		$view_is_st = false;
		$view_is_head = false;	
		$view_is_admin = false;
		$may_edit = true;
		$edit_cell = false;
		$calculate_derived = false;
		
		$character_sheet = buildWoDSheet($character_detail, $character_type, $edit_show_sheet, $edit_name, $edit_vitals, $edit_is_npc, $edit_is_dead, $edit_location, $edit_concept, $edit_description, $edit_url, $edit_equipment, $edit_public_effects, $edit_group, $edit_exit_line, $edit_is_npc, $edit_attributes, $edit_skills, $edit_perm_traits, $edit_temp_traits, $edit_powers, $edit_history, $edit_goals, $edit_login_note, $edit_experience, $show_st_notes, $view_is_asst, $view_is_st, $view_is_head, $view_is_admin, $may_edit, $edit_cell, $calculate_derived);
		
		$page_content .= <<<EOQ
Updating $character_detail[Character_Name]
<iframe src="blank.html" name="char_info" id="char_info" width="1" height="1" border="0" frameborder="0" scrolling="no">
</iframe>
<br>
<form name="character_sheet" id="character_sheet" method="post" action="$_SERVER[PHP_SELF]?action=view_own">
<div align="center" name="char_sheet" id="char_sheet">
$character_sheet
</div>
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


function SubmitCharacter()
{
	window.document.character_sheet.submit();
}
</script>
EOQ;
	}
	else
	{
		$character_type = $character_detail['Character_Type'];
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
		$edit_login_note = false;
		$edit_experience = false;
		$show_st_notes = false;
		$view_is_asst = false;
		$view_is_st = false;
		$view_is_head = false;	
		$view_is_admin = false;
		$may_edit = true;
		$edit_cell = true;
		$calculate_derived = true;
		
		$character_sheet = buildWoDSheet($character_detail, $character_type, $edit_show_sheet, $edit_name, $edit_vitals, $edit_is_npc, $edit_is_dead, $edit_location, $edit_concept, $edit_description, $edit_url, $edit_equipment, $edit_public_effects, $edit_group, $edit_exit_line, $edit_is_npc, $edit_attributes, $edit_skills, $edit_perm_traits, $edit_temp_traits, $edit_powers, $edit_history, $edit_goals, $edit_login_note, $edit_experience, $show_st_notes, $view_is_asst, $view_is_st, $view_is_head, $view_is_admin, $may_edit, $edit_cell, $calculate_derived);
		
		$page_content .= <<<EOQ
Updating $character_detail[Character_Name]
<iframe src="blank.html" name="char_info" id="char_info" width="1" height="1" border="0" frameborder="0" scrolling="no">
</iframe>
<br>
<form name="character_sheet" id="character_sheet" method="post" action="$_SERVER[PHP_SELF]?action=view_own">
<div align="center" name="char_sheet" id="char_sheet">
$character_sheet
</div>
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

function updateTraits()
{
	// willpower
	var resolve = document.getElementById("resolve").value;
	var composure = document.getElementById("composure").value;
	changeDots("willpower_perm", Number(resolve)+Number(composure), 10, false);
	changeDots("willpower_temp", Number(resolve)+Number(composure), 10, false);
	
	// health
	var stamina = document.getElementById("stamina").value;
	var size = document.getElementById("size").value;
	changeDots("health", Number(stamina) + Number(size), 15, false);
	
	// defense
	var wits = document.getElementById("wits").value;
	var dexterity = document.getElementById("dexterity").value;
	var defense = wits; 
	
	if (dexterity < wits)
	{
		defense = dexterity;
	}
	document.getElementById("defense").value = defense;
	
	// initiative
	var initiative = Number(dexterity) + Number(composure); 
	document.getElementById("initiative_mod").value = initiative;
	
	// speed
	var strength = document.getElementById("strength").value;
	var speed = Number(size) + Number(strength) + Number(dexterity);
	
	document.getElementById("speed").value = speed;
}

function changeSheet(character_type)
{
  var sURL = "get_sheet.php?action=view_own&character_id=$character_id&character_type="+character_type;
  window.char_info.location.href = sURL;
}

function SubmitCharacter()
{
	if(document.character_sheet.character_name.value.match(/\w/g))
	{
		window.document.character_sheet.submit();
	}
	else
	{
		alert('Please Enter a Character Name');
	}
}
</script>
EOQ;

	}
	
}
else
{
	// either character id or not linked to the logged in person
	$page_title = "View Character Sheet";
	$page_content = "You are not able to view that sheet.";
}

?>