<?
function buildWoDSheet($stats, $character_type='Mortal', $edit_show_sheet = false, $edit_name = false, $edit_vitals = false, $edit_is_npc = false, $edit_is_dead = false, $edit_location = false, $edit_concept = false, $edit_description = false, $edit_url = false, $edit_equipment = false, $edit_public_effects = false, $edit_group = false, $edit_exit_line = false, $edit_is_npc = false, $edit_attributes = false, $edit_skills = false, $edit_perm_traits = false, $edit_temp_traits = false, $edit_powers = false, $edit_history = false, $edit_goals = false, $edit_login_note = false, $edit_experience = false, $show_st_notes_table = false, $view_is_asst = false, $view_is_st = false, $view_is_head = false, $view_is_admin = false, $may_edit = false, $edit_cell = false, $calculate_derived = false)
{
	// initialize sheet
	$sheet = "";
	
	// initialize sheet values
	$max_dots = 7;
	$attribute_list = array("strength", "dexterity", "stamina", "presence", "manipulation", "composure", "intelligence", "wits", "resolve");
	
	$skill_list = array("academics", "computer", "crafts", "investigation", "medicine", "occult", "politics", "science", "athletics", "brawl", "drive", "firearms", "larceny", "stealth", "survival", "weaponry", "animal_ken", "empathy", "expression", "intimidation", "persuasion", "socialize", "streetwise", "subterfuge");
	
	$table_bg_color = "#000000";
	$table_class = "normal_text";
		
	$character_id = 0;
	$character_name = "";
	$show_sheet = "N";
	$view_password = "";
	$hide_icon = "N";
	
	$location = "";
	$virtue = "";
	$vice = "";
	$splat1 = "";
	$splat1_groups = "";
	$splat2 = "";
	$splat2_groups = "";
	$subsplat = "";
	$icon = 0;
	$sex = "";
	$age = "18+";
	$apparent_age = "18+";
	$is_npc = "N";
	$status = "";
	$is_deleted = "N";
	$xp_per_day = .5;
	
	$concept = "";
	$description = "";
	$url = "";
	$equipment_public = "";
	$equipment_hidden = "";
	$public_effects = "";
	$friends = ""; // pack/coterie/whatever
	$helper = ""; // Totem/Familiar/whatever
	$safe_place = "";
	$exit_line = "";
	
	while(list($key, $attribute) = each($attribute_list))
	{
		$$attribute = 1;
	}
	
	reset($attribute_list);
	
	while(list($key, $skill) = each($skill_list))
	{
		$$skill = 0;
		$skill_spec = $skill."_spec";
		$$skill_spec = "";
	}
	
	reset($skill_list);
	
	$power_trait = 1;
	$willpower_perm = 0;
	$willpower_temp = 0;
	$morality = 7;
	$power_points = 10;
    $maxPowerPoints = 20;
	$health = 0;
	$size = 5;
	$defense = 0; 
	$initiative_mod = 0; 
	$speed = 0; 
	$armor = "0/0";
	$wounds_bashing = 0;
	$wounds_lethal = 0; 
	$wounds_aggravated = 0;
	
	$merits = "";
	$flaws = "";
	$powers = "";
	$history = "";
	$notes = ""; 
	$goals = "";

	$cell_id = "";
	$login_note = "";
	$current_experience = 0;
	$total_experience = 0;
	$first_login = "";
	$last_login = "";
	$last_st_updated = "";
	$when_last_st_updated = "";
	$last_asst_st_updated = "";
	$when_last_asst_st_updated = "";
	$gm_notes = "";
	$sheet_updates = "";
	$head_sanctioned = "";
	$is_sanctioned = "";
	$asst_sanctioned = "";
	$view_status = "";
	
	$merits_edit = "readonly";
	$powers_edit = "readonly";
	$history_edit = "readonly";
	$goals_edit = "readonly";
	
	// mods for ghouls
	if($character_type == "Ghoul")
	{
  	$morality = 7;
  	$power_points = $stamina;
  	
	}
	
	// test if stats were passed
	if($stats != "")
	{
		// set sheet values based on passed stats
		$character_id = $stats['Character_ID'];
		$character_name = $stats['Character_Name'];
		$show_sheet = $stats['Show_Sheet'];
		$view_password = $stats['View_Password'];
		$hide_icon = $stats['Hide_Icon'];
		
		$location = $stats['City'];
		$virtue = $stats['Virtue'];
		$vice = $stats['Vice'];
		$splat1 = $stats['Splat1'];
		$splat2 = $stats['Splat2'];
		$subsplat = $stats['SubSplat'];
		$icon = $stats['Icon'];
		$sex = $stats['Sex'];
		$age = $stats['Age'];
		$apparent_age = $stats['Apparent_Age'];
		$is_npc = $stats['Is_NPC'];
		$status = $stats['Status'];
		$is_deleted = $stats['Is_Deleted'];
		$xp_per_day = $stats['XP_Per_Day'];
		
		$concept = $stats['Concept'];
		$description = $stats['Description'];
		$url = $stats['URL'];
		$equipment_public = $stats['Equipment_Public'];
		$equipment_hidden = $stats['Equipment_Hidden'];
		$public_effects = $stats['Public_Effects'];
		$friends = $stats['Friends']; // pack/coterie/whatever
		$helper = $stats['Helper']; // Totem/Familiar/whatever/Regnent
		$safe_place = $stats['Safe_Place'];
		$exit_line = $stats['Exit_Line'];
		
		$proper_attribute_list = array("Strength", "Dexterity", "Stamina", "Presence", "Manipulation", "Composure", "Intelligence", "Wits", "Resolve");
		
		while(list($key, $attribute) = each($attribute_list))
		{
			$$attribute = $stats[$proper_attribute_list[$key]];
		}
	
		reset($attribute_list);

		$proper_skill_list = array("Academics", "Computer", "Crafts", "Investigation", "Medicine", "Occult", "Politics", "Science", "Athletics", "Brawl", "Drive", "Firearms", "Larceny", "Stealth", "Survival", "Weaponry", "Animal_Ken", "Empathy", "Expression", "Intimidation", "Persuasion", "Socialize", "Streetwise", "Subterfuge");

		while(list($key, $skill) = each($skill_list))
		{
			$$skill = $stats[$proper_skill_list[$key]];
			$skill_spec = $skill."_spec";
			$proper_skill_spec = $proper_skill_list[$key] . "_Spec";
			$$skill_spec = $stats[$proper_skill_spec];
		}
		
		reset($skill_list);

		$power_trait = $stats['Power_Stat'];
		$willpower_perm = $stats['Willpower_Perm'];
		$willpower_temp = $stats['Willpower_Temp'];
		$morality = $stats['Morality'];
		$power_points = $stats['Power_Points'];
		$health = $stats['Health'];
		$size = $stats['Size'];
		$defense = $stats['Defense']; 
		$initiative_mod = $stats['Initiative_Mod']; 
		$speed = $stats['Speed']; 
		$armor = $stats['Armor'];
		$wounds_bashing = $stats['Wounds_Bashing'];
		$wounds_lethal = $stats['Wounds_Lethal']; 
		$wounds_aggravated = $stats['Wounds_Agg'];
		
		$merits = $stats['Merits'];
		$flaws = $stats['Flaws'];
		$powers = $stats['Powers'];
		$history = $stats['History'];
		$notes = $stats['Character_Notes']; 
		$goals = $stats['Goals'];
	
		$cell_id = $stats['Cell_ID'];
		$login_note = $stats['Login_Note'];
		$current_experience = $stats['Current_Experience'];
		$total_experience = $stats['Total_Experience'];
		$first_login = $stats['First_Login'];
		$last_login = $stats['Last_Login'];
		$last_st_updated = $stats['ST_Name'];
		$when_last_st_updated = $stats['When_Last_ST_Updated'];
		$last_asst_st_updated = $stats['Asst_Name'];
		$when_last_asst_st_updated = $stats['When_Last_Asst_ST_Updated'];
		$gm_notes = $stats['GM_Notes'];
		$sheet_updates = $stats['Sheet_Update'];
		$head_sanctioned = $stats['Head_Sanctioned'];
		$is_sanctioned = $stats['Is_Sanctioned'];
		$asst_sanctioned = $stats['Asst_Sanctioned'];
		$potential_experience = $stats['Potential_Experience'];
		$hours_on = $stats['Hours_On'];
	}

	// set page colors based on type of character
	switch($character_type)
	{
		case 'Mortal':
			$table_bg_color = "#6e68a3";
			$table_class = "mortal_normal_text";
			break;
			
		case 'Psychic':
			$table_bg_color = "#6e68a3";
			$table_class = "mortal_normal_text";
			break;
			
		case 'Thaumaturge':
			$table_bg_color = "#6e68a3";
			$table_class = "mortal_normal_text";
			$splat1_groups = array("Ceremonial Magician", "Hedge Witch", "Shaman", "Taoist Alchemist", "Vodoun");
			break;
			
		case 'Vampire':
			$table_bg_color = "#9e0b0f";
			$table_class = "vampire_normal_text";
			$splat1_groups = array("Daeva", "Gangrel", "Mekhet", "Nosferatu", "Ventrue");
			$splat2_groups = array("Carthian", "Circle of the Crone", "Invictus", "Lancea Sanctum", "Ordo Dracul", "Unaligned");
			break;
		case 'Werewolf':
			$table_bg_color = "#74483f";
			$table_class = "werewolf_normal_text";
			$splat1_groups = array("Rahu", "Cahalith", "Elodoth", "Ithaeur", "Irraka", "None");
			$splat2_groups = array("Blood Talons", "Bone Shadows", "Hunters in Darkness", "Iron Masters", "Storm Lords", "Ghost Wolves", "Fire-Touched", "Ivory Claws", "Predator Kings");
			break;
		case 'Mage':
			$table_bg_color = "#004a80";
			$table_class = "mage_normal_text";
			$splat1_groups = array("Acanthus", "Mastigos", "Moros", "Obrimos", "Thyrsus");
			$splat2_groups = array("The Adamantine Arrows", "Free Council", "Guardians of the Veil", "The Mysterium", "The Silver Ladder", "Apostate", "Seer of the Throne", "Banisher");
			break;
		case 'Geist':
			$table_bg_color = "#415582";
			$table_class = "geist_normal_text";
			$splat1_groups = array("Advocate", "Bonepicker", "Celebrant", "Gatekeeper", "Mourner", "Necromancer", "Pilgrim", "Reaper");
			$splat2_groups = array("Forgotten", "Prey", "Silent", "Stricken", "Torn");
            $maxPowerPoints = 30;
            die($maxPowerPoints);
			break;
		case 'Ghoul':
			$table_bg_color = "#9e0b0f";
			$table_class = "ghoul_normal_text";
			break;
		case 'Sleepwalker':
			$table_bg_color = "#004a80";
			$table_class = "sleepwalker_normal_text";
			break;
		case 'Wolfblood':
			$table_bg_color = "#004a80";
			$table_class = "wolfblood_normal_text";
			break;
		default:
			$table_bg_color = "#505067";
			$table_class = "mortal_normal_text";
			break;
	}
	
	$show_sheet_table = "";
	if($edit_show_sheet)
	{
		$show_sheet_yes_check = ($show_sheet == 'Y') ? "checked" : "";
		$show_sheet_no_check = ($show_sheet == 'N') ? "checked" : "";
		
		$hide_icon_yes_check = ($hide_icon == 'Y') ? "checked" : "";
		$hide_icon_no_check = ($hide_icon == 'N') ? "checked" : "";
		
		$show_sheet_table = <<<EOQ
<table bgcolor="$table_bg_color" border="0" cellpadding="1" cellspacing="0" width="100%">
	<tr>
	  <td>
			<table border="0" cellpadding="2" cellspacing="1" class="$table_class" width="100%">
				<tr>
					<th colspan="2">
						Show Sheet
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000" width="50%">
						Show sheet to Others:
						Yes: <input type="radio" name="show_sheet" id="show_sheet" value="Y" $show_sheet_yes_check>
						No: <input type="radio" name="show_sheet" id="show_sheet" value="N" $show_sheet_no_check>
					</td>
					<td bgcolor="#000000" width="50%">
						Password to View:
						<input type="text" name="view_password" id="view_password" value="$view_password" size="20" maxlength="30">
					</td>
				</tr>
				<tr>
				  <td bgcolor="#000000" colspan="2">
				  	Use General Icon:
						Yes: <input type="radio" name="hide_icon" id="hide_icon" value="Y" $hide_icon_yes_check>
						No: <input type="radio" name="hide_icon" id="hide_icon" value="N" $hide_icon_no_check>
				  </td>
			</table>
		</td>
	</tr>
</table>
EOQ;
	}
	
	$submit_button = "";
	if($may_edit)
	{
		$submit_button = <<<EOQ
<table bgcolor="$table_bg_color" border="0" cellpadding="1" cellspacing="0" width="100%">
	<tr>
	  <td align="center">
	  	<input type="hidden" name="character_id" value="$character_id">
	  	<input type="submit" name="submit" value="Submit Character" onClick="SubmitCharacter();return false;">
	  </td>
	</tr>
</table>
EOQ;
		
	}
	// create sheet values
	if($edit_name)
	{
		$character_name = <<<EOQ
<input type="text" name="character_name" id="character_name" value="$character_name" size="30" maxlength="30">
EOQ;
	}
	
	if($edit_vitals)
	{
		// edit character type
		$character_types = array("Mortal", "Vampire", "Werewolf", "Mage", "Ghoul", "Psychic", "Thaumaturge");
		$character_type_js = "onChange=\"changeSheet(window.document.character_sheet.character_type.value)\";";
		$character_type_select = buildSelect($character_type, $character_types, $character_types, "character_type", $character_type_js);
		
		// location
		$locations = array("Side Game");
		$location = buildSelect($location, $locations, $locations, "location");
		
		// sex
		$sexes = array("Male", "Female");
		$sex = buildSelect($sex, $sexes, $sexes, "sex");
		
		// virtue & vice
		$virtues = array("Charity", "Faith", "Fortitude", "Hope", "Justice", "Prudence", "Temperance");
		$virtue = buildSelect($virtue, $virtues, $virtues, "virtue");
		$vices = array("Envy", "Gluttony", "Greed", "Lust", "Pride", "Sloth", "Wrath");
		$vice = buildSelect($vice, $vices, $vices, "vice");

		// icon
		$icon_query = "";
		if ( $view_is_asst )
		{
			$icon_query = "select * from icons where Player_Viewable='Y' order by Icon_Name;";
		}
		if ( $view_is_st )
		{
			$icon_query = "select * from icons where GM_Viewable='Y' order by Icon_Name;";
		}
		if ( $view_is_admin || $view_is_head)
		{
			$icon_query = "select * from icons where Admin_Viewable='Y' order by Icon_Name;";
		}
		if ( $icon_query == "" )
		{
			$icon_query = "select * from icons where Player_Viewable='Y' order by Icon_Name;";
		}
		$icon_result = mysql_query($icon_query) or die(mysql_error());

		$icon_ids = "";
		$icon_names = "";

		while ( $icon_detail = mysql_fetch_array($icon_result, MYSQL_ASSOC) )
		{
			$icon_ids[] = $icon_detail['Icon_ID'];
			$icon_names[] = $icon_detail['Icon_Name'];
		}
		$icon = buildSelect($icon, $icon_ids, $icon_names, "icon");

		$splat1 = buildSelect($splat1, $splat1_groups, $splat1_groups, "splat1");
		$splat2 = buildSelect($splat2, $splat2_groups, $splat2_groups, "splat2");
		
		$subsplat = <<<EOQ
<input type="text" name="subsplat" id="subsplat" value="$subsplat" size="20" maxlength="30">
EOQ;
		
		$age = <<<EOQ
<input type="text" name="age" id="age" value="$age" size="4" maxlength="4">
EOQ;

		$apparent_age = <<<EOQ
<input type="text" name="apparent_age" id="apparent_age" value="$apparent_age" size="4" maxlength="4">
EOQ;
	}
	else
	{
		// get name of icon
		$icon = $stats['Icon_Name'];
		
		// have a hidden form field for character dots
		$character_type_select = <<<EOQ
$character_type
<input type="hidden" name="character_type" id="character_type" value="$character_type">
EOQ;
	}
	
	if($edit_is_npc)
	{
		$is_npc_check = "";
		if($is_npc == 'Y')
		{
			$is_npc_check = "checked";
		}
		
		$is_npc = <<<EOQ
<input type="checkbox" name="is_npc" id="is_npc" value="Y" $is_npc_check>
EOQ;
	}
	
	if($edit_is_dead)
	{
		$statuses = array("Ok", "Imprisoned", "Hospitalized", "Torpored", "Dead");
		$status = buildSelect($status, $statuses, $statuses, "status");
	}
	
	// concept
	if($edit_concept)
	{
		$concept = <<<EOQ
<input type="text" name="concept" id="concept" value="$concept" size="50" maxlength="255">
EOQ;
	}
	
	// description
	if($edit_description)
	{
		$description = <<<EOQ
<input type="text" name="description" id="description" value="$description" size="50" maxlength="400">
EOQ;
	}
	
	// url
	if($edit_url)
	{
		$url = <<<EOQ
<input type="text" name="url" id="url" value="$url" size="50" maxlength="255">
EOQ;
	}
	
	// $edit_group, $edit_exit_line,
	// equipment
	if($edit_equipment)
	{
		$equipment_public = <<<EOQ
<input type="text" name="equipment_public" id="equipment_public" value="$equipment_public" size="50" maxlength="255">
EOQ;

		$equipment_hidden = <<<EOQ
<input type="text" name="equipment_hidden" id="equipment_hidden" value="$equipment_hidden" size="50" maxlength="255">
EOQ;
	}

	if($edit_public_effects)
	{
		$public_effects = <<<EOQ
<input type="text" name="public_effects" id="public_effects" value="$public_effects" size="50" maxlength="255">
EOQ;
	}
	
	if($edit_group)
	{
		$friends = <<<EOQ
<input type="text" name="friends" id="friends" value="$friends" size="25" maxlength="255">
EOQ;

		$safe_place = <<<EOQ
<input type="text" name="safe_place" id="safe_place" value="$safe_place" size="50" maxlength="255">
EOQ;

		$helper = <<<EOQ
<input type="text" name="helper" id="helper" value="$helper" size="50" maxlength="255">
EOQ;
	}
	
	if($edit_exit_line)
	{
		$exit_line = <<<EOQ
<input type="text" name="exit_line" id="exit_line" value="$exit_line" size="50" maxlength="255">
EOQ;
	}
	
	
	reset($attribute_list);
	
	while(list($key, $attribute) = each($attribute_list))
	{
		$attribute_dots = $attribute."_dots";
		$$attribute_dots = makeDots($attribute, $character_type, $max_dots, $$attribute, $edit_attributes, $calculate_derived);
	}
	
	reset($skill_list);
	
	while(list($key, $skill) = each($skill_list))
	{
		$skill_dots = $skill."_dots";
		$skill_spec = $skill."_spec";
		if($edit_skills)
		{
			$$skill_spec = <<<EOQ
<input type="text" name="$skill_spec" id="$skill_spec" value="${$skill_spec}" size="10" maxlength="100">
EOQ;
		}
			
		$$skill_dots = makeDots($skill, $character_type, $max_dots, $$skill, $edit_skills);
	}
	
	
	$power_trait_dots = makeDots("power_trait", $character_type, 10, $power_trait, $edit_perm_traits);
	$willpower_perm_dots = makeDots("willpower_perm", $character_type, 10, $willpower_perm, $edit_perm_traits);
	$willpower_temp_dots = makeDots("willpower_temp", $character_type, 10, $willpower_temp, (($edit_temp_traits && $is_sanctioned == "") || ($view_is_asst || $view_is_st || $view_is_head || $view_is_admin)));
	$morality_dots = makeDots("morality", $character_type, 10, $morality, $edit_perm_traits);
	$power_points_dots = makeDots("power_points", $character_type, $maxPowerPoints, $power_points, $edit_temp_traits);
	$health_dots = makeDots("health", $character_type, 15, $health, $edit_perm_traits);
	
	if($edit_perm_traits)
	{
		$size = <<<EOQ
<input type="text" name="size" id="size" size="3" maxlength="2" value="$size">
EOQ;

		$defense = <<<EOQ
<input type="text" name="defense" size="3" id="defense" maxlength="2" value="$defense">
EOQ;

		$initiative_mod = <<<EOQ
<input type="text" name="initiative_mod" id="initiative_mod" size="3" maxlength="2" value="$initiative_mod">
EOQ;

		$speed = <<<EOQ
<input type="text" name="speed" id="speed" size="3" maxlength="2" value="$speed">
EOQ;

		$armor = <<<EOQ
<input type="text" name="armor" id="armor" size="5" maxlength="4" value="$armor">
EOQ;
	}
	
	if($edit_temp_traits)
	{
		// edit health levels
		$wounds_bashing = <<<EOQ
<input type="text" name="wounds_bashing" id="wounds_bashing" value="$wounds_bashing" size="3" maxlength="2">
EOQ;

		$wounds_lethal = <<<EOQ
<input type="text" name="wounds_lethal" id="wounds_lethal" value="$wounds_lethal" size="3" maxlength="2">
EOQ;

		$wounds_aggravated = <<<EOQ
<input type="text" name="wounds_aggravated" id="wounds_aggravated" value="$wounds_aggravated" size="3" maxlength="2">
EOQ;
	}
	
	if($edit_powers)
	{
		$merits_edit = "";
		$powers_edit = "";
	}
	
	if($edit_history)
	{
		$history_edit = "";
	}
	
	if($edit_goals)
	{
		$goals_edit = "";
		$notes_edit = "";
	}
	
	$notes_box = "";
	if($edit_cell)
	{
		$cell_query = "select distinct Cell_ID from gm_permissions order by Cell_ID;";
		$cell_result = mysql_query($cell_query) or die(mysql_error());
		
		$cell_ids = "";
		while($cell_detail = mysql_fetch_array($cell_result, MYSQL_ASSOC))
		{
			$cell_ids[] = $cell_detail['Cell_ID'];
		}
		
		$cell_id = buildSelect($cell_id, $cell_ids, $cell_ids, "cell_id");
	}
	
	if($edit_login_note)
	{
		$login_note = <<<EOQ
<input type="text" name="login_note" id="login_note" value="$login_note" size="70" maxlength="250">
EOQ;
	}
	
	// create human readable version of status 
	$temp_asst = ($asst_sanctioned == "") ? "X" : $asst_sanctioned;
	$temp_sanc = ($is_sanctioned == "") ? "X" : $is_sanctioned;
	$temp_head = ($head_sanctioned == "") ? "X" : $head_sanctioned;
	
	$temp_status = $temp_sanc . $temp_asst;
	
	switch ($temp_status)
	{
		case 'YY':
		case 'YX':
		case 'YN':
			$view_status = "Sanctioned";
			break;
		case 'XY':
			$view_status = "Presanctioned";
			break;
		case 'XX':
			$view_status = "Unviewed";
			break;
		case 'XN':
		case 'NY':
		case 'NX':
		case 'NN':
			$view_status = 'Unapproved (Locked)';
			break;
	}
	
	if($show_st_notes_table)
	{
  	if($view_is_head)
  	{
    	$head_sanc_yes_check = ($head_sanctioned == 'Y') ? "checked" : "";
    	$head_sanc_no_check = ($head_sanctioned == 'N') ? "checked" : "";
    	$head_sanctioned = <<<EOQ
Yes: <input type="radio" name="head_sanctioned" value="Y" $head_sanc_yes_check>
No: <input type="radio" name="head_sanctioned" value="N" $head_sanc_no_check>
EOQ;
  	}
  	
  	if($view_is_st)
  	{
    	$sanc_yes_check = ($is_sanctioned == 'Y') ? "checked" : "";
    	$sanc_no_check = ($is_sanctioned == 'N') ? "checked" : "";
    	$is_sanctioned = <<<EOQ
Yes: <input type="radio" name="is_sanctioned" value="Y" $sanc_yes_check>
No: <input type="radio" name="is_sanctioned" value="N" $sanc_no_check>
EOQ;
  	}
  	
  	if($view_is_asst)
  	{
    	$asst_sanc_yes_check = ($asst_sanctioned == 'Y') ? "checked" : "";
    	$asst_sanc_no_check = ($asst_sanctioned == 'N') ? "checked" : "";
    	$asst_sanctioned = <<<EOQ
Yes: <input type="radio" name="asst_sanctioned" value="Y" $asst_sanc_yes_check>
No: <input type="radio" name="asst_sanctioned" value="N" $asst_sanc_no_check>
EOQ;
  	}
  	
  	if($edit_experience)
  	{
    	$current_experience = <<<EOQ
<input type="text" name="current_experience" value="$current_experience" size="5" maxlength="7">
EOQ;

    	$total_experience = <<<EOQ
<input type="text" name="total_experience" value="$total_experience" size="5" maxlength="7">
EOQ;
  	}
  	
		$st_notes_table = <<<EOQ
<table bgcolor="$table_bg_color" border="0" cellpadding="1" cellspacing="0" width="100%">
	<tr>
	  <td>
			<table border="0" cellpadding="2" cellspacing="1" class="$table_class" width="100%">
				<tr>
					<th colspan="4">
						Storyteller Information
					</th>
				</tr>
				<tr>
					<td bgcolor="#000000">
						Login Note: 
					</td>
					<td bgcolor="#000000" colspan="3">
						$login_note
					</td>
				</tr>
				<tr>
				  <td bgcolor="#000000" width="25%">
				    Created On:
				  </td>
				  <td bgcolor="#000000" width="25%">
				    $first_login
				  </td>
				  <td bgcolor="#000000" width="25%">
				    Last Login
				  </td>
				  <td bgcolor="#000000" width="25%">
				    $last_login
				  </td>
				</tr> 
				<tr>
				  <td bgcolor="#000000" width="25%">
				    Primary Profile
				  </td>
				  <td bgcolor="#000000" width="25%">
				    $stats[Name]
				  </td>
				  <td bgcolor="#000000" width="25%">
				    Cell ID
				  </td>
				  <td bgcolor="#000000" width="25%">
				    $cell_id
				  </td>
				</tr> 
				<tr>
				  <td bgcolor="#000000" width="25%">
				    Head Sanctioned
				  </td>
				  <td bgcolor="#000000" width="25%">
				    $head_sanctioned
				  </td>
				  <td bgcolor="#000000" width="25%">
				    Last ST Updated
				  </td>
				  <td bgcolor="#000000" width="25%">
				    $last_st_updated
				  </td>
				</tr> 
				<tr>
				  <td bgcolor="#000000" width="25%">
				    Is Sanctioned
				  </td>
				  <td bgcolor="#000000" width="25%">
				    $is_sanctioned
				  </td>
				  <td bgcolor="#000000" width="25%">
				    When Last ST Updated
				  </td>
				  <td bgcolor="#000000" width="25%">
				    $when_last_st_updated
				  </td>
				</tr> 
				<tr>
				  <td bgcolor="#000000" width="25%">
				    Pre-Sanctioned
				  </td>
				  <td bgcolor="#000000" width="25%">
				    $asst_sanctioned
				  </td>
				  <td bgcolor="#000000" width="25%">
				    Last Cell ST Updated
				  </td>
				  <td bgcolor="#000000" width="25%">
				    $last_asst_st_updated
				  </td>
				</tr> 
				<tr>
				  <td bgcolor="#000000" width="25%">
				    Status:
				  </td>
				  <td bgcolor="#000000" width="25%">
				    $view_status
				  </td>
				  <td bgcolor="#000000" width="25%">
				    When Last Cell ST Updated
				  </td>
				  <td bgcolor="#000000" width="25%">
				    $when_last_asst_st_updated
				  </td>
				</tr> 
				<tr>
				  <td bgcolor="#000000" width="25%">
				    Experience Unspent:
				  </td>
				  <td bgcolor="#000000" width="25%">
				    $current_experience
				  </td>
				  <td bgcolor="#000000" width="25%">
				    Total Experience Earned:
				  </td>
				  <td bgcolor="#000000" width="25%">
				    $total_experience
				  </td>
				</tr> 
				<tr>
				  <td bgcolor="#000000" colspan="2">
				    Past ST Updates:<br>
				    <textarea name="sheet_updates" rows="6" cols="40" readonly>$sheet_updates</textarea>
				    <br>
				    Your Updates to add: Write all updates you do to a character sheet here. *MANDATORY*<br>
				    <textarea name="new_sheet_updates" rows="6" cols="40"></textarea>
				  </td>
				  <td bgcolor="#000000" colspan="2">
				    Past ST Notes:<br>
				    <textarea name="gm_notes" rows="6" cols="40" readonly>$gm_notes</textarea>
				    <br>
				    Your Notes to add: Personal notes and comments about the character. Not a mandatory field.<br>
				    <textarea name="new_gm_notes" rows="6" cols="40"></textarea>
				  </td>
				</tr> 
			</table>
		</td>
	</tr>
</table>
EOQ;

	}   
	else
	{
		$st_notes_table = <<<EOQ
<table bgcolor="$table_bg_color" border="0" cellpadding="1" cellspacing="0" width="100%">
	<tr>
	  <td>
			<table border="0" cellpadding="2" cellspacing="1" class="$table_class" width="100%">
				<tr>
					<th colspan="3">
						Player Information
					</th>
				</tr>
				<tr>
					<td bgcolor="#000000" colspan="3">
						Login Note: 
						$login_note
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						Cell ID:
						$cell_id
					</td>
					<td bgcolor="#000000">
						XP for the Week:
						$potential_experience
					</td>
					<td bgcolor="#000000">
						Hours On:
						$hours_on
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000" width="34%">
				  	Status:
				  	$view_status
					</td>
					<td bgcolor="#000000" width="33%">
						Experience Unspent:
						$current_experience
					</td>
					<td bgcolor="#000000" width="33%">
						Total Experience Earned:
						$total_experience
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000" width="34%">
						Last ST to View:
						$last_st_updated
					</td>
					<td bgcolor="#000000" width="33%">
						Updated On:
						$when_last_st_updated
					</td>
					<td bgcolor="#000000" width="33%">
						Created On:
						$first_login
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
EOQ;
	}
	
	$admin_row = "";
	if($view_is_admin)
	{
		$admin_row = <<<EOQ
				<tr>
					<td bgcolor="#000000">
						<b>Is Deleted</b>
					</td>
					<td bgcolor="#000000">
						N
					</td>
					<td bgcolor="#000000">
						<b>XP Per Day</b>
					</td>
					<td bgcolor="#000000">
						.5
					</td>
				</tr>
EOQ;
	}
	
	$vitals_table = "Vitals Not Done Yet<br>";
	$information_table = "Information Not Done Yet<br>";
	$traits_table = "Traits Not Done Yet<br>";
	$history_table = "History Not Done Yet<br>";
	switch($character_type)
	{
		case 'Mortal':
			$vitals_table = <<<EOQ
<table bgcolor="$table_bg_color" border="0" cellpadding="1" cellspacing="0" width="100%">
	<tr>
	  <td>
			<table border="0" cellpadding="2" cellspacing="1" class="$table_class" width="100%">
				<tr>
					<th colspan="4">
						Vitals
					</th>
				</tr>
				<tr>
					<td bgcolor="#000000" width="15%">
						<b>Name</b>
					</td>
					<td bgcolor="#000000" width="35%">
						$character_name
					</td>
					<td bgcolor="#000000" width="15%">
						<b>Character Type</b>
					</td>
					<td bgcolor="#000000" width="35%">
						$character_type_select
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						<b>Location</b>
					</td>
					<td bgcolor="#000000">
						$location
					</td>
					<td bgcolor="#000000">
						<b>Sex:</b>
					</td>
					<td bgcolor="#000000">
						$sex
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000"><b> Virtue</b></td>
					<td bgcolor="#000000">$virtue</td>
					<td bgcolor="#000000"><b>Vice</b></td>
					<td bgcolor="#000000">$vice</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						<b>Icon</b>
					</td>
					<td bgcolor="#000000">
						$icon
					</td>
					<td bgcolor="#000000">
						<b>Age</b>
					</td>
					<td bgcolor="#000000">
						$age
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						<b>Is NPC</b>
					</td>
					<td bgcolor="#000000">
						$is_npc
					</td>
					<td bgcolor="#000000">
						<b>Status</b>
					</td>
					<td bgcolor="#000000">
						$status
					</td>
				</tr>
				$admin_row
			</table>
		</td>
	</tr>
</table>
EOQ;

			$information_table = <<<EOQ
<table bgcolor="$table_bg_color" border="0" cellpadding="1" cellspacing="0" width="100%">
	<tr>
	  <td>
			<table border="0" cellpadding="2" cellspacing="1" class="$table_class" width="100%">
				<tr>
					<th colspan="2">
						Information
					</th>
				</tr>
				<tr>
					<td bgcolor="#000000" width="25%">
						<b>Concept</b>
					</td>
					<td bgcolor="#000000" width="75%">
						$concept
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						<b>Description</b>
					</td>
					<td bgcolor="#000000">
						$description
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						<b>URL</b>
					</td>
					<td bgcolor="#000000">
						$url
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						<b>Daily Equipment</b>
					</td>
					<td bgcolor="#000000">
						$equipment_public
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						<b>Other Equipment</b>
					</td>
					<td bgcolor="#000000">
						$equipment_hidden
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						<b>Public Effects</b>
					</td>
					<td bgcolor="#000000">
						$public_effects
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						<b>Home</b>
					</td>
					<td bgcolor="#000000">
						$safe_place
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						<b>Exit Line</b>
					</td>
					<td bgcolor="#000000">
						$exit_line
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
EOQ;

			$traits_table = <<<EOQ
<table bgcolor="$table_bg_color" border="0" cellpadding="1" cellspacing="0" width="100%">
	<tr>
	  <td>
			<table border="0" cellpadding="2" cellspacing="1" class="$table_class" width="100%">
				<tr>
					<th colspan="6">
						Traits
					</th>
				</tr>
				<tr>
					<td bgcolor="#000000" width="15%">
						Health
					</td>
					<td bgcolor="#000000" colspan="2" width="50%">
						$health_dots
					</td>
				  <td colspan="1" bgcolor="#000000" width="15%">
				  	Size
				  </td>
				  <td colspan="2" bgcolor="#000000" width="20%">
				  	$size
				  </td>
				</tr>
				<tr>
				  <td colspan="1" bgcolor="#000000">
				    Wounds
				  </td>
				  <td colspan="2" bgcolor="#000000">
				    Bashing: $wounds_bashing Lethal: $wounds_lethal Agg: $wounds_aggravated
				  </td>
				  <td colspan="1" bgcolor="#000000">
				  	Defense
				  </td>
				  <td colspan="2" bgcolor="#000000">
				  	$defense
				  </td>
				</tr>
				<tr>
				  <td colspan="1" bgcolor="#000000">
				  	Morality
				  </td>
				  <td colspan="2" bgcolor="#000000">
				  	$morality_dots
				  </td>
				  <td colspan="1" bgcolor="#000000">
				  	Initiative Mod
				  </td>
				  <td colspan="2" bgcolor="#000000">
				  	$initiative_mod
				  </td>
				</tr>
				<tr>
				  <td bgcolor="#000000">
				  	Willpower Perm
				  </td>
				  <td colspan="2" bgcolor="#000000">
				  	$willpower_perm_dots
				  </td>
				  <td colspan="1" bgcolor="#000000">
				  	Speed
				  </td>
				  <td colspan="2" bgcolor="#000000">
				  	$speed
				  </td>
				</tr>
				<tr>
				  <td bgcolor="#000000">
				  	Willpower Temp
				  </td>
				  <td colspan="2" bgcolor="#000000">
				  	$willpower_temp_dots
				  </td>
				  <td colspan="1" bgcolor="#000000">
				  	Armor
				  </td>
				  <td colspan="2" bgcolor="#000000">
				  	$armor
				  </td>
				</tr>
			</table>
		</td>
	</tr>
</table>
EOQ;

			$history_table = <<<EOQ
<table bgcolor="$table_bg_color" border="0" cellpadding="1" cellspacing="0" width="100%">
	<tr>
	  <td>
			<table border="0" cellpadding="2" cellspacing="1" class="$table_class" width="100%">
				<tr>
					<th colspan="2">
						History
					</th>
				</tr>
				<tr>
					<td bgcolor="#000000" width="40%">
						<span class="highlight">Merits</span><br>
						<textarea rows="8" name="merits" id="merits" style="width:100%" $merits_edit>$merits</textarea>
					</td>
					<td bgcolor="#000000" width="60%">
						<span class="highlight">Misc Abilities</span><br>
						<textarea rows="8" name="powers" id="powers" style="width:100%" $powers_edit>$powers</textarea>
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000" width="40%">
						<span class="highlight">Flaws & Derangements</span><br>
						<textarea rows="8" name="flaws" id="flaws" style="width:100%" $merits_edit>$flaws</textarea>
					</td>
					<td bgcolor="#000000">
						<span class="highlight">History</span><br>
						<textarea rows="8" name="history" id="history" style="width:100%" $history_edit>$history</textarea>
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						<span class="highlight">Goals &amp; Beliefs</span><br>
						<textarea rows="8" name="goals" id="goals" style="width:100%" $goals_edit>$goals</textarea>
					</td>
					<td bgcolor="#000000" width="60%">
						<span class="highlight">Notes</span><br>
						<textarea rows="8" name="notes" id="notes" style="width:100%" $notes_edit>$notes</textarea>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
EOQ;
			break;
			
		case 'Psychic':
			$vitals_table = <<<EOQ
<table bgcolor="$table_bg_color" border="0" cellpadding="1" cellspacing="0" width="100%">
	<tr>
	  <td>
			<table border="0" cellpadding="2" cellspacing="1" class="$table_class" width="100%">
				<tr>
					<th colspan="4">
						Vitals
					</th>
				</tr>
				<tr>
					<td bgcolor="#000000" width="15%">
						<b>Name</b>
					</td>
					<td bgcolor="#000000" width="35%">
						$character_name
					</td>
					<td bgcolor="#000000" width="15%">
						<b>Character Type</b>
					</td>
					<td bgcolor="#000000" width="35%">
						$character_type_select
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						<b>Location</b>
					</td>
					<td bgcolor="#000000">
						$location
					</td>
					<td bgcolor="#000000">
						<b>Sex:</b>
					</td>
					<td bgcolor="#000000">
						$sex
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000"><b> Virtue</b></td>
					<td bgcolor="#000000">$virtue</td>
					<td bgcolor="#000000"><b>Vice</b></td>
					<td bgcolor="#000000">$vice</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						<b>Icon</b>
					</td>
					<td bgcolor="#000000">
						$icon
					</td>
					<td bgcolor="#000000">
						<b>Age</b>
					</td>
					<td bgcolor="#000000">
						$age
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						<b>Is NPC</b>
					</td>
					<td bgcolor="#000000">
						$is_npc
					</td>
					<td bgcolor="#000000">
						<b>Status</b>
					</td>
					<td bgcolor="#000000">
						$status
					</td>
				</tr>
				$admin_row
			</table>
		</td>
	</tr>
</table>
EOQ;

			$information_table = <<<EOQ
<table bgcolor="$table_bg_color" border="0" cellpadding="1" cellspacing="0" width="100%">
	<tr>
	  <td>
			<table border="0" cellpadding="2" cellspacing="1" class="$table_class" width="100%">
				<tr>
					<th colspan="2">
						Information
					</th>
				</tr>
				<tr>
					<td bgcolor="#000000" width="25%">
						<b>Concept</b>
					</td>
					<td bgcolor="#000000" width="75%">
						$concept
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						<b>Description</b>
					</td>
					<td bgcolor="#000000">
						$description
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						<b>URL</b>
					</td>
					<td bgcolor="#000000">
						$url
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						<b>Daily Equipment</b>
					</td>
					<td bgcolor="#000000">
						$equipment_public
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						<b>Other Equipment</b>
					</td>
					<td bgcolor="#000000">
						$equipment_hidden
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						<b>Public Effects</b>
					</td>
					<td bgcolor="#000000">
						$public_effects
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						<b>Home</b>
					</td>
					<td bgcolor="#000000">
						$safe_place
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						<b>Exit Line</b>
					</td>
					<td bgcolor="#000000">
						$exit_line
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
EOQ;

			$traits_table = <<<EOQ
<table bgcolor="$table_bg_color" border="0" cellpadding="1" cellspacing="0" width="100%">
	<tr>
	  <td>
			<table border="0" cellpadding="2" cellspacing="1" class="$table_class" width="100%">
				<tr>
					<th colspan="6">
						Traits
					</th>
				</tr>
				<tr>
					<td bgcolor="#000000" width="15%">
						Health
					</td>
					<td bgcolor="#000000" colspan="2" width="50%">
						$health_dots
					</td>
				  <td colspan="1" bgcolor="#000000" width="15%">
				  	Size
				  </td>
				  <td colspan="2" bgcolor="#000000" width="20%">
				  	$size
				  </td>
				</tr>
				<tr>
				  <td colspan="1" bgcolor="#000000">
				    Wounds
				  </td>
				  <td colspan="2" bgcolor="#000000">
				    Bashing: $wounds_bashing Lethal: $wounds_lethal Agg: $wounds_aggravated
				  </td>
				  <td colspan="1" bgcolor="#000000">
				  	Defense
				  </td>
				  <td colspan="2" bgcolor="#000000">
				  	$defense
				  </td>
				</tr>
				<tr>
				  <td colspan="1" bgcolor="#000000">
				  	Morality
				  </td>
				  <td colspan="2" bgcolor="#000000">
				  	$morality_dots
				  </td>
				  <td colspan="1" bgcolor="#000000">
				  	Initiative Mod
				  </td>
				  <td colspan="2" bgcolor="#000000">
				  	$initiative_mod
				  </td>
				</tr>
				<tr>
				  <td bgcolor="#000000">
				  	Willpower Perm
				  </td>
				  <td colspan="2" bgcolor="#000000">
				  	$willpower_perm_dots
				  </td>
				  <td colspan="1" bgcolor="#000000">
				  	Speed
				  </td>
				  <td colspan="2" bgcolor="#000000">
				  	$speed
				  </td>
				</tr>
				<tr>
				  <td bgcolor="#000000">
				  	Willpower Temp
				  </td>
				  <td colspan="2" bgcolor="#000000">
				  	$willpower_temp_dots
				  </td>
				  <td colspan="1" bgcolor="#000000">
				  	Armor
				  </td>
				  <td colspan="2" bgcolor="#000000">
				  	$armor
				  </td>
				</tr>
			</table>
		</td>
	</tr>
</table>
EOQ;

			$history_table = <<<EOQ
<table bgcolor="$table_bg_color" border="0" cellpadding="1" cellspacing="0" width="100%">
	<tr>
	  <td>
			<table border="0" cellpadding="2" cellspacing="1" class="$table_class" width="100%">
				<tr>
					<th colspan="2">
						History
					</th>
				</tr>
				<tr>
					<td bgcolor="#000000" width="40%">
						<span class="highlight">Merits</span><br>
						<textarea rows="8" name="merits" id="merits" style="width:100%" $merits_edit>$merits</textarea>
					</td>
					<td bgcolor="#000000" width="60%">
						<span class="highlight">Psychic Merits</span><br>
						<textarea rows="8" name="powers" id="powers" style="width:100%" $powers_edit>$powers</textarea>
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000" width="40%">
						<span class="highlight">Flaws & Derangements</span><br>
						<textarea rows="8" name="flaws" id="flaws" style="width:100%" $merits_edit>$flaws</textarea>
					</td>
					<td bgcolor="#000000">
						<span class="highlight">History</span><br>
						<textarea rows="8" name="history" id="history" style="width:100%" $history_edit>$history</textarea>
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						<span class="highlight">Goals &amp; Beliefs</span><br>
						<textarea rows="8" name="goals" id="goals" style="width:100%" $goals_edit>$goals</textarea>
					</td>
					<td bgcolor="#000000" width="60%">
						<span class="highlight">Notes</span><br>
						<textarea rows="8" name="notes" id="notes" style="width:100%" $notes_edit>$notes</textarea>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
EOQ;
			break;
			
		case 'Thaumaturge':
			$vitals_table = <<<EOQ
<table bgcolor="$table_bg_color" border="0" cellpadding="1" cellspacing="0" width="100%">
	<tr>
	  <td>
			<table border="0" cellpadding="2" cellspacing="1" class="$table_class" width="100%">
				<tr>
					<th colspan="4">
						Vitals
					</th>
				</tr>
				<tr>
					<td bgcolor="#000000" width="15%">
						<b>Name</b>
					</td>
					<td bgcolor="#000000" width="35%">
						$character_name
					</td>
					<td bgcolor="#000000" width="15%">
						<b>Character Type</b>
					</td>
					<td bgcolor="#000000" width="35%">
						$character_type_select
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						<b>Location</b>
					</td>
					<td bgcolor="#000000">
						$location
					</td>
					<td bgcolor="#000000">
						<b>Sex:</b>
					</td>
					<td bgcolor="#000000">
						$sex
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000"><b> Virtue</b></td>
					<td bgcolor="#000000">$virtue</td>
					<td bgcolor="#000000"><b>Vice</b></td>
					<td bgcolor="#000000">$vice</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						<b>Tradition</b>
					</td>
					<td bgcolor="#000000">
						$splat1
					</td>
					<td bgcolor="#000000">
						<b>Coven</b>
					</td>
					<td bgcolor="#000000">
						$friends
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						<b>Icon</b>
					</td>
					<td bgcolor="#000000">
						$icon
					</td>
					<td bgcolor="#000000">
						<b>Age</b>
					</td>
					<td bgcolor="#000000">
						$age
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						<b>Is NPC</b>
					</td>
					<td bgcolor="#000000">
						$is_npc
					</td>
					<td bgcolor="#000000">
						<b>Status</b>
					</td>
					<td bgcolor="#000000">
						$status
					</td>
				</tr>
				$admin_row
			</table>
		</td>
	</tr>
</table>
EOQ;

			$information_table = <<<EOQ
<table bgcolor="$table_bg_color" border="0" cellpadding="1" cellspacing="0" width="100%">
	<tr>
	  <td>
			<table border="0" cellpadding="2" cellspacing="1" class="$table_class" width="100%">
				<tr>
					<th colspan="2">
						Information
					</th>
				</tr>
				<tr>
					<td bgcolor="#000000" width="25%">
						<b>Concept</b>
					</td>
					<td bgcolor="#000000" width="75%">
						$concept
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						<b>Description</b>
					</td>
					<td bgcolor="#000000">
						$description
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						<b>URL</b>
					</td>
					<td bgcolor="#000000">
						$url
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						<b>Daily Equipment</b>
					</td>
					<td bgcolor="#000000">
						$equipment_public
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						<b>Other Equipment</b>
					</td>
					<td bgcolor="#000000">
						$equipment_hidden
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						<b>Public Effects</b>
					</td>
					<td bgcolor="#000000">
						$public_effects
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						<b>Home</b>
					</td>
					<td bgcolor="#000000">
						$safe_place
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						<b>Exit Line</b>
					</td>
					<td bgcolor="#000000">
						$exit_line
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
EOQ;

			$traits_table = <<<EOQ
<table bgcolor="$table_bg_color" border="0" cellpadding="1" cellspacing="0" width="100%">
	<tr>
	  <td>
			<table border="0" cellpadding="2" cellspacing="1" class="$table_class" width="100%">
				<tr>
					<th colspan="6">
						Traits
					</th>
				</tr>
				<tr>
					<td bgcolor="#000000" width="15%">
						Health
					</td>
					<td bgcolor="#000000" colspan="2" width="50%">
						$health_dots
					</td>
				  <td colspan="1" bgcolor="#000000" width="15%">
				  	Size
				  </td>
				  <td colspan="2" bgcolor="#000000" width="20%">
				  	$size
				  </td>
				</tr>
				<tr>
				  <td colspan="1" bgcolor="#000000">
				    Wounds
				  </td>
				  <td colspan="2" bgcolor="#000000">
				    Bashing: $wounds_bashing Lethal: $wounds_lethal Agg: $wounds_aggravated
				  </td>
				  <td colspan="1" bgcolor="#000000">
				  	Defense
				  </td>
				  <td colspan="2" bgcolor="#000000">
				  	$defense
				  </td>
				</tr>
				<tr>
				  <td colspan="1" bgcolor="#000000">
				  	Morality
				  </td>
				  <td colspan="2" bgcolor="#000000">
				  	$morality_dots
				  </td>
				  <td colspan="1" bgcolor="#000000">
				  	Initiative Mod
				  </td>
				  <td colspan="2" bgcolor="#000000">
				  	$initiative_mod
				  </td>
				</tr>
				<tr>
				  <td bgcolor="#000000">
				  	Willpower Perm
				  </td>
				  <td colspan="2" bgcolor="#000000">
				  	$willpower_perm_dots
				  </td>
				  <td colspan="1" bgcolor="#000000">
				  	Speed
				  </td>
				  <td colspan="2" bgcolor="#000000">
				  	$speed
				  </td>
				</tr>
				<tr>
				  <td bgcolor="#000000">
				  	Willpower Temp
				  </td>
				  <td colspan="2" bgcolor="#000000">
				  	$willpower_temp_dots
				  </td>
				  <td colspan="1" bgcolor="#000000">
				  	Armor
				  </td>
				  <td colspan="2" bgcolor="#000000">
				  	$armor
				  </td>
				</tr>
			</table>
		</td>
	</tr>
</table>
EOQ;

			$history_table = <<<EOQ
<table bgcolor="$table_bg_color" border="0" cellpadding="1" cellspacing="0" width="100%">
	<tr>
	  <td>
			<table border="0" cellpadding="2" cellspacing="1" class="$table_class" width="100%">
				<tr>
					<th colspan="2">
						History
					</th>
				</tr>
				<tr>
					<td bgcolor="#000000" width="40%">
						<span class="highlight">Merits</span><br>
						<textarea rows="8" name="merits" id="merits" style="width:100%" $merits_edit>$merits</textarea>
					</td>
					<td bgcolor="#000000" width="60%">
						<span class="highlight">Rituals</span><br>
						<textarea rows="8" name="powers" id="powers" style="width:100%" $powers_edit>$powers</textarea>
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000" width="40%">
						<span class="highlight">Flaws & Derangements</span><br>
						<textarea rows="8" name="flaws" id="flaws" style="width:100%" $merits_edit>$flaws</textarea>
					</td>
					<td bgcolor="#000000">
						<span class="highlight">History</span><br>
						<textarea rows="8" name="history" id="history" style="width:100%" $history_edit>$history</textarea>
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						<span class="highlight">Goals &amp; Beliefs</span><br>
						<textarea rows="8" name="goals" id="goals" style="width:100%" $goals_edit>$goals</textarea>
					</td>
					<td bgcolor="#000000" width="60%">
						<span class="highlight">Notes</span><br>
						<textarea rows="8" name="notes" id="notes" style="width:100%" $notes_edit>$notes</textarea>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
EOQ;
			break;
			
		case 'Werewolf':
			$vitals_table = <<<EOQ
<table bgcolor="$table_bg_color" border="0" cellpadding="1" cellspacing="0" width="100%">
	<tr>
	  <td>
			<table border="0" cellpadding="2" cellspacing="1" class="$table_class" width="100%">
				<tr>
					<th colspan="4">
						Vitals
					</th>
				</tr>
				<tr>
					<td bgcolor="#000000" width="15%">
						<b>Name</b>
					</td>
					<td bgcolor="#000000" width="35%">
						$character_name
					</td>
					<td bgcolor="#000000" width="15%">
						<b>Character Type</b>
					</td>
					<td bgcolor="#000000" width="35%">
						$character_type_select
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						<b>Location</b>
					</td>
					<td bgcolor="#000000">
						$location
					</td>
					<td bgcolor="#000000">
						<b>Sex:</b>
					</td>
					<td bgcolor="#000000">
						$sex
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						<b>Virtue</b>
					</td>
					<td bgcolor="#000000">
						$virtue
					</td>
					<td bgcolor="#000000">
						<b>Vice</b>
					</td>
					<td bgcolor="#000000">
						$vice
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						<b>Auspice</b>
					</td>
					<td bgcolor="#000000">
						$splat1
					</td>
					<td bgcolor="#000000">
						<b>Lodge</b>
					</td>
					<td bgcolor="#000000">
						$subsplat
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						<b>Tribe</b>
					</td>
					<td bgcolor="#000000">
						$splat2
					</td>
					<td bgcolor="#000000">
						<b>Icon</b>
					</td>
					<td bgcolor="#000000">
						$icon
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						<b>Age</b>
					</td>
					<td bgcolor="#000000">
						$age
					</td>
					<td bgcolor="#000000">
						<b>Pack</b>
					</td>
					<td bgcolor="#000000">
						$friends
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						<b>Is NPC</b>
					</td>
					<td bgcolor="#000000">
						$is_npc
					</td>
					<td bgcolor="#000000">
						<b>Status</b>
					</td>
					<td bgcolor="#000000">
						$status
					</td>
				</tr>
				$admin_row
			</table>
		</td>
	</tr>
</table>
EOQ;
		
			$information_table = <<<EOQ
<table bgcolor="$table_bg_color" border="0" cellpadding="1" cellspacing="0" width="100%">
	<tr>
	  <td>
			<table border="0" cellpadding="2" cellspacing="1" class="$table_class" width="100%">
				<tr>
					<th colspan="2">
						Information
					</th>
				</tr>
				<tr>
					<td bgcolor="#000000" width="25%">
						<b>Concept</b>
					</td>
					<td bgcolor="#000000" width="75%">
						$concept
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						<b>Description</b>
					</td>
					<td bgcolor="#000000">
						$description
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						<b>URL</b>
					</td>
					<td bgcolor="#000000">
						$url
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						<b>Daily Equipment</b>
					</td>
					<td bgcolor="#000000">
						$equipment_public
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						<b>Other Equipment</b>
					</td>
					<td bgcolor="#000000">
						$equipment_hidden
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						<b>Public Effects</b>
					</td>
					<td bgcolor="#000000">
						$public_effects
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						<b>Totem</b>
					</td>
					<td bgcolor="#000000">
						$helper
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						<b>Territory/Loci</b>
					</td>
					<td bgcolor="#000000">
						$safe_place
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						<b>Exit Line</b>
					</td>
					<td bgcolor="#000000">
						$exit_line
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
EOQ;

			$traits_table = <<<EOQ
<table bgcolor="$table_bg_color" border="0" cellpadding="1" cellspacing="0" width="100%">
	<tr>
	  <td>
			<table border="0" cellpadding="2" cellspacing="1" class="$table_class" width="100%">
				<tr>
					<th colspan="6">
						Traits
					</th>
				</tr>
				<tr>
					<td bgcolor="#000000" width="15%">
						Health
					</td>
					<td bgcolor="#000000" colspan="2" width="30%">
						$health_dots
					</td>
					  <td colspan="1" bgcolor="#000000" width="15%">
				    Wounds
				  </td>
				  <td colspan="2" bgcolor="#000000" width="40%">
				    Bashing: $wounds_bashing Lethal: $wounds_lethal Agg: $wounds_aggravated
				  </td>
			</tr>
				<tr>
				  <td colspan="1" bgcolor="#000000">
				  	Primal Urge
				  </td>
				  <td colspan="2" bgcolor="#000000">
				  	$power_trait_dots
				  </td>
				  <td colspan="1" bgcolor="#000000">
				  	Size
				  </td>
				  <td colspan="2" bgcolor="#000000">
				  	$size
				  </td>
				</tr>
				<tr>
				  <td colspan="1" bgcolor="#000000">
				  	Harmony
				  </td>
				  <td colspan="2" bgcolor="#000000">
				  	$morality_dots
				  </td>
				  <td colspan="1" bgcolor="#000000">
				  	Defense
				  </td>
				  <td colspan="2" bgcolor="#000000">
				  	$defense
				  </td>
				</tr>
				<tr>
				  <td bgcolor="#000000">
				  	Willpower Perm
				  </td>
				  <td colspan="2" bgcolor="#000000">
				  	$willpower_perm_dots
				  </td>
				  <td colspan="1" bgcolor="#000000">
				  	Initiative Mod
				  </td>
				  <td colspan="2" bgcolor="#000000">
				  	$initiative_mod
				  </td>
				</tr>
				<tr>
				  <td bgcolor="#000000">
				  	Willpower Temp
				  </td>
				  <td colspan="2" bgcolor="#000000">
				  	$willpower_temp_dots
				  </td>
				  <td colspan="1" bgcolor="#000000">
				  	Speed
				  </td>
				  <td colspan="2" bgcolor="#000000">
				  	$speed
				  </td>
				</tr>
				<tr>
				  <td bgcolor="#000000">
				  	Essence
				  </td>
				  <td colspan="2" bgcolor="#000000">
				  	$power_points_dots
				  </td>
				  <td colspan="1" bgcolor="#000000">
				  	Armor
				  </td>
				  <td colspan="2" bgcolor="#000000">
				  	$armor
				  </td>
				</tr>
			</table>
		</td>
	</tr>
</table>
EOQ;

			$history_table = <<<EOQ
<table bgcolor="$table_bg_color" border="0" cellpadding="1" cellspacing="0" width="100%">
	<tr>
	  <td>
			<table border="0" cellpadding="2" cellspacing="1" class="$table_class" width="100%">
				<tr>
					<th colspan="2">
						History
					</th>
				</tr>
				<tr>
					<td bgcolor="#000000" width="40%">
						<span class="highlight">Merits</span><br>
						<textarea rows="8" name="merits" id="merits" style="width:100%" $merits_edit>$merits</textarea>
					</td>
					<td bgcolor="#000000" width="60%">
						<span class="highlight">Gifts/Lists/Rites/Fetishes/Renown</span><br>
						<textarea rows="8" name="powers" id="powers" style="width:100%" $powers_edit>$powers</textarea>
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000" width="40%">
						<span class="highlight">Flaws & Derangements</span><br>
						<textarea rows="8" name="flaws" id="flaws" style="width:100%" $merits_edit>$flaws</textarea>
					</td>
					<td bgcolor="#000000">
						<span class="highlight">History</span><br>
						<textarea rows="8" name="history" id="history" style="width:100%" $history_edit>$history</textarea>
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						<span class="highlight">Goals &amp; Beliefs</span><br>
						<textarea rows="8" name="goals" id="goals" style="width:100%" $goals_edit>$goals</textarea>
					</td>
					<td bgcolor="#000000" width="60%">
						<span class="highlight">Notes</span><br>
						<textarea rows="8" name="notes" id="notes" style="width:100%" $notes_edit>$notes</textarea>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
EOQ;
			break;
			
		case 'Vampire':
			$vitals_table = <<<EOQ
<table bgcolor="$table_bg_color" border="0" cellpadding="1" cellspacing="0" width="100%">
	<tr>
	  <td>
			<table border="0" cellpadding="2" cellspacing="1" class="$table_class" width="100%">
				<tr>
					<th colspan="4">
						Vitals
					</th>
				</tr>
				<tr>
					<td bgcolor="#000000" width="15%">
						<b>Name</b>
					</td>
					<td bgcolor="#000000" width="35%">
						$character_name
					</td>
					<td bgcolor="#000000" width="15%">
						<b>Character Type</b>
					</td>
					<td bgcolor="#000000" width="35%">
						$character_type_select
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						<b>Location</b>
					</td>
					<td bgcolor="#000000">
						$location
					</td>
					<td bgcolor="#000000">
						<b>Sex:</b>
					</td>
					<td bgcolor="#000000">
						$sex
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						<b>Virtue</b>
					</td>
					<td bgcolor="#000000">
						$virtue
					</td>
					<td bgcolor="#000000">
						<b>Vice</b>
					</td>
					<td bgcolor="#000000">
						$vice
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						<b>Clan</b>
					</td>
					<td bgcolor="#000000">
						$splat1
					</td>
					<td bgcolor="#000000">
						<b>Bloodline</b>
					</td>
					<td bgcolor="#000000">
						$subsplat
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						<b>Covenant</b>
					</td>
					<td bgcolor="#000000">
						$splat2
					</td>
					<td bgcolor="#000000">
						<b>Icon</b>
					</td>
					<td bgcolor="#000000">
						$icon
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						<b>Age</b>
					</td>
					<td bgcolor="#000000">
						$age
					</td>
					<td bgcolor="#000000">
						<b>Apparent Age</b>
					</td>
					<td bgcolor="#000000">
						$apparent_age
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						<b>Is NPC</b>
					</td>
					<td bgcolor="#000000">
						$is_npc
					</td>
					<td bgcolor="#000000">
						<b>Status</b>
					</td>
					<td bgcolor="#000000">
						$status
					</td>
				</tr>
				$admin_row
			</table>
		</td>
	</tr>
</table>
EOQ;
		
			$information_table = <<<EOQ
<table bgcolor="$table_bg_color" border="0" cellpadding="1" cellspacing="0" width="100%">
	<tr>
	  <td>
			<table border="0" cellpadding="2" cellspacing="1" class="$table_class" width="100%">
				<tr>
					<th colspan="2">
						Information
					</th>
				</tr>
				<tr>
					<td bgcolor="#000000" width="25%">
						<b>Concept</b>
					</td>
					<td bgcolor="#000000" width="75%">
						$concept
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						<b>Description</b>
					</td>
					<td bgcolor="#000000">
						$description
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						<b>URL</b>
					</td>
					<td bgcolor="#000000">
						$url
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						<b>Daily Equipment</b>
					</td>
					<td bgcolor="#000000">
						$equipment_public
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						<b>Other Equipment</b>
					</td>
					<td bgcolor="#000000">
						$equipment_hidden
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						<b>Public Effects</b>
					</td>
					<td bgcolor="#000000">
						$public_effects
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						<b>Coterie</b>
					</td>
					<td bgcolor="#000000">
						$friends
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						<b>Haven</b>
					</td>
					<td bgcolor="#000000">
						$safe_place
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						<b>Exit Line</b>
					</td>
					<td bgcolor="#000000">
						$exit_line
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
EOQ;

			$traits_table = <<<EOQ
<table bgcolor="$table_bg_color" border="0" cellpadding="1" cellspacing="0" width="100%">
	<tr>
	  <td>
			<table border="0" cellpadding="2" cellspacing="1" class="$table_class" width="100%">
				<tr>
					<th colspan="6">
						Traits
					</th>
				</tr>
				<tr>
					<td bgcolor="#000000" width="15%">
						Health
					</td>
					<td bgcolor="#000000" colspan="2" width="30%">
						$health_dots
					</td>
					  <td colspan="1" bgcolor="#000000" width="15%">
				    Wounds
				  </td>
				  <td colspan="2" bgcolor="#000000" width="40%">
				    Bashing: $wounds_bashing Lethal: $wounds_lethal Agg: $wounds_aggravated
				  </td>
			</tr>
				<tr>
				  <td colspan="1" bgcolor="#000000">
				  	Blood Potency
				  </td>
				  <td colspan="2" bgcolor="#000000">
				  	$power_trait_dots
				  </td>
				  <td colspan="1" bgcolor="#000000">
				  	Size
				  </td>
				  <td colspan="2" bgcolor="#000000">
				  	$size
				  </td>
				</tr>
				<tr>
				  <td colspan="1" bgcolor="#000000">
				  	Humanity
				  </td>
				  <td colspan="2" bgcolor="#000000">
				  	$morality_dots
				  </td>
				  <td colspan="1" bgcolor="#000000">
				  	Defense
				  </td>
				  <td colspan="2" bgcolor="#000000">
				  	$defense
				  </td>
				</tr>
				<tr>
				  <td bgcolor="#000000">
				  	Willpower Perm
				  </td>
				  <td colspan="2" bgcolor="#000000">
				  	$willpower_perm_dots
				  </td>
				  <td colspan="1" bgcolor="#000000">
				  	Initiative Mod
				  </td>
				  <td colspan="2" bgcolor="#000000">
				  	$initiative_mod
				  </td>
				</tr>
				<tr>
				  <td bgcolor="#000000">
				  	Willpower Temp
				  </td>
				  <td colspan="2" bgcolor="#000000">
				  	$willpower_temp_dots
				  </td>
				  <td colspan="1" bgcolor="#000000">
				  	Speed
				  </td>
				  <td colspan="2" bgcolor="#000000">
				  	$speed
				  </td>
				</tr>
				<tr>
				  <td bgcolor="#000000">
				  	Blood
				  </td>
				  <td colspan="2" bgcolor="#000000">
				  	$power_points_dots
				  </td>
				  <td colspan="1" bgcolor="#000000">
				  	Armor
				  </td>
				  <td colspan="2" bgcolor="#000000">
				  	$armor
				  </td>
				</tr>
			</table>
		</td>
	</tr>
</table>
EOQ;

			$history_table = <<<EOQ
<table bgcolor="$table_bg_color" border="0" cellpadding="1" cellspacing="0" width="100%">
	<tr>
	  <td>
			<table border="0" cellpadding="2" cellspacing="1" class="$table_class" width="100%">
				<tr>
					<th colspan="2">
						History
					</th>
				</tr>
				<tr>
					<td bgcolor="#000000" width="40%">
						<span class="highlight">Merits</span><br>
						<textarea rows="8" name="merits" id="merits" style="width:100%" $merits_edit>$merits</textarea>
					</td>
					<td bgcolor="#000000" width="60%">
						<span class="highlight">Disciplines/Devotions/Rituals/Etc.</span><br>
						<textarea rows="8" name="powers" id="powers" style="width:100%" $powers_edit>$powers</textarea>
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000" width="40%">
						<span class="highlight">Flaws & Derangements</span><br>
						<textarea rows="8" name="flaws" id="flaws" style="width:100%" $merits_edit>$flaws</textarea>
					</td>
					<td bgcolor="#000000">
						<span class="highlight">History</span><br>
						<textarea rows="8" name="history" id="history" style="width:100%" $history_edit>$history</textarea>
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						<span class="highlight">Goals &amp; Beliefs</span><br>
						<textarea rows="8" name="goals" id="goals" style="width:100%" $goals_edit>$goals</textarea>
					</td>
					<td bgcolor="#000000" width="60%">
						<span class="highlight">Notes</span><br>
						<textarea rows="8" name="notes" id="notes" style="width:100%" $notes_edit>$notes</textarea>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
EOQ;
			break;
			
		case 'Mage':
			$vitals_table = <<<EOQ
<table bgcolor="$table_bg_color" border="0" cellpadding="1" cellspacing="0" width="100%">
	<tr>
	  <td>
			<table border="0" cellpadding="2" cellspacing="1" class="$table_class" width="100%">
				<tr>
					<th colspan="4">
						Vitals
					</th>
				</tr>
				<tr>
					<td bgcolor="#000000" width="15%">
						<b>Name</b>
					</td>
					<td bgcolor="#000000" width="35%">
						$character_name
					</td>
					<td bgcolor="#000000" width="15%">
						<b>Character Type</b>
					</td>
					<td bgcolor="#000000" width="35%">
						$character_type_select
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						<b>Location</b>
					</td>
					<td bgcolor="#000000">
						$location
					</td>
					<td bgcolor="#000000">
						<b>Sex:</b>
					</td>
					<td bgcolor="#000000">
						$sex
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						<b>Virtue</b>
					</td>
					<td bgcolor="#000000">
						$virtue
					</td>
					<td bgcolor="#000000">
						<b>Vice</b>
					</td>
					<td bgcolor="#000000">
						$vice
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						<b>Path</b>
					</td>
					<td bgcolor="#000000">
						$splat1
					</td>
					<td bgcolor="#000000">
						<b>Legacy</b>
					</td>
					<td bgcolor="#000000">
						$subsplat
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						<b>Order</b>
					</td>
					<td bgcolor="#000000">
						$splat2
					</td>
					<td bgcolor="#000000">
						<b>Icon</b>
					</td>
					<td bgcolor="#000000">
						$icon
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						<b>Age</b>
					</td>
					<td bgcolor="#000000">
						$age
					</td>
					<td bgcolor="#000000">
						<b>Cabal</b>
					</td>
					<td bgcolor="#000000">
						$friends
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						<b>Is NPC</b>
					</td>
					<td bgcolor="#000000">
						$is_npc
					</td>
					<td bgcolor="#000000">
						<b>Status</b>
					</td>
					<td bgcolor="#000000">
						$status
					</td>
				</tr>
				$admin_row
			</table>
		</td>
	</tr>
</table>
EOQ;
		
			$information_table = <<<EOQ
<table bgcolor="$table_bg_color" border="0" cellpadding="1" cellspacing="0" width="100%">
	<tr>
	  <td>
			<table border="0" cellpadding="2" cellspacing="1" class="$table_class" width="100%">
				<tr>
					<th colspan="2">
						Information
					</th>
				</tr>
				<tr>
					<td bgcolor="#000000" width="25%">
						<b>Concept</b>
					</td>
					<td bgcolor="#000000" width="75%">
						$concept
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						<b>Description</b>
					</td>
					<td bgcolor="#000000">
						$description
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						<b>URL</b>
					</td>
					<td bgcolor="#000000">
						$url
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						<b>Daily Equipment</b>
					</td>
					<td bgcolor="#000000">
						$equipment_public
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						<b>Other Equipment</b>
					</td>
					<td bgcolor="#000000">
						$equipment_hidden
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						<b>Public Effects</b>
					</td>
					<td bgcolor="#000000">
						$public_effects
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						<b>Familiar</b>
					</td>
					<td bgcolor="#000000">
						$helper
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						<b>Sanctum/Hallow</b>
					</td>
					<td bgcolor="#000000">
						$safe_place
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						<b>Exit Line</b>
					</td>
					<td bgcolor="#000000">
						$exit_line
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
EOQ;

			$traits_table = <<<EOQ
<table bgcolor="$table_bg_color" border="0" cellpadding="1" cellspacing="0" width="100%">
	<tr>
	  <td>
			<table border="0" cellpadding="2" cellspacing="1" class="$table_class" width="100%">
				<tr>
					<th colspan="6">
						Traits
					</th>
				</tr>
				<tr>
					<td bgcolor="#000000" width="15%">
						Health
					</td>
					<td bgcolor="#000000" colspan="2" width="30%">
						$health_dots
					</td>
					  <td colspan="1" bgcolor="#000000" width="15%">
				    Wounds
				  </td>
				  <td colspan="2" bgcolor="#000000" width="40%">
				    Bashing: $wounds_bashing Lethal: $wounds_lethal Agg: $wounds_aggravated
				  </td>
			</tr>
				<tr>
				  <td colspan="1" bgcolor="#000000">
				  	Gnosis
				  </td>
				  <td colspan="2" bgcolor="#000000">
				  	$power_trait_dots
				  </td>
				  <td colspan="1" bgcolor="#000000">
				  	Size
				  </td>
				  <td colspan="2" bgcolor="#000000">
				  	$size
				  </td>
				</tr>
				<tr>
				  <td colspan="1" bgcolor="#000000">
				  	Wisdom
				  </td>
				  <td colspan="2" bgcolor="#000000">
				  	$morality_dots
				  </td>
				  <td colspan="1" bgcolor="#000000">
				  	Defense
				  </td>
				  <td colspan="2" bgcolor="#000000">
				  	$defense
				  </td>
				</tr>
				<tr>
				  <td bgcolor="#000000">
				  	Willpower Perm
				  </td>
				  <td colspan="2" bgcolor="#000000">
				  	$willpower_perm_dots
				  </td>
				  <td colspan="1" bgcolor="#000000">
				  	Initiative Mod
				  </td>
				  <td colspan="2" bgcolor="#000000">
				  	$initiative_mod
				  </td>
				</tr>
				<tr>
				  <td bgcolor="#000000">
				  	Willpower Temp
				  </td>
				  <td colspan="2" bgcolor="#000000">
				  	$willpower_temp_dots
				  </td>
				  <td colspan="1" bgcolor="#000000">
				  	Speed
				  </td>
				  <td colspan="2" bgcolor="#000000">
				  	$speed
				  </td>
				</tr>
				<tr>
				  <td bgcolor="#000000">
				  	Mana
				  </td>
				  <td colspan="2" bgcolor="#000000">
				  	$power_points_dots
				  </td>
				  <td colspan="1" bgcolor="#000000">
				  	Armor
				  </td>
				  <td colspan="2" bgcolor="#000000">
				  	$armor
				  </td>
				</tr>
			</table>
		</td>
	</tr>
</table>
EOQ;

			$history_table = <<<EOQ
<table bgcolor="$table_bg_color" border="0" cellpadding="1" cellspacing="0" width="100%">
	<tr>
	  <td>
			<table border="0" cellpadding="2" cellspacing="1" class="$table_class" width="100%">
				<tr>
					<th colspan="2">
						History
					</th>
				</tr>
				<tr>
					<td bgcolor="#000000" width="40%">
						<span class="highlight">Merits</span><br>
						<textarea rows="8" name="merits" id="merits" style="width:100%" $merits_edit>$merits</textarea>
					</td>
					<td bgcolor="#000000" width="60%">
						<span class="highlight">Arcana/Rotes (remember page numbers)</span><br>
						<textarea rows="8" name="powers" id="powers" style="width:100%" $powers_edit>$powers</textarea>
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000" width="40%">
						<span class="highlight">Flaws & Derangements</span><br>
						<textarea rows="8" name="flaws" id="flaws" style="width:100%" $merits_edit>$flaws</textarea>
					</td>
					<td bgcolor="#000000">
						<span class="highlight">History</span><br>
						<textarea rows="8" name="history" id="history" style="width:100%" $history_edit>$history</textarea>
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						<span class="highlight">Goals &amp; Beliefs</span><br>
						<textarea rows="8" name="goals" id="goals" style="width:100%" $goals_edit>$goals</textarea>
					</td>
					<td bgcolor="#000000" width="60%">
						<span class="highlight">Notes</span><br>
						<textarea rows="8" name="notes" id="notes" style="width:100%" $notes_edit>$notes</textarea>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
EOQ;
			break;
			
		case 'Ghoul':
			$vitals_table = <<<EOQ
<table bgcolor="$table_bg_color" border="0" cellpadding="1" cellspacing="0" width="100%">
	<tr>
	  <td>
			<table border="0" cellpadding="2" cellspacing="1" class="$table_class" width="100%">
				<tr>
					<th colspan="4">
						Vitals
					</th>
				</tr>
				<tr>
					<td bgcolor="#000000" width="15%">
						<b>Name</b>
					</td>
					<td bgcolor="#000000" width="35%">
						$character_name
					</td>
					<td bgcolor="#000000" width="15%">
						<b>Character Type</b>
					</td>
					<td bgcolor="#000000" width="35%">
						$character_type_select
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						<b>Location</b>
					</td>
					<td bgcolor="#000000">
						$location
					</td>
					<td bgcolor="#000000">
						<b>Sex:</b>
					</td>
					<td bgcolor="#000000">
						$sex
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000"><b> Virtue</b></td>
					<td bgcolor="#000000">$virtue</td>
					<td bgcolor="#000000"><b>Vice</b></td>
					<td bgcolor="#000000">$vice</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						<b>Icon</b>
					</td>
					<td bgcolor="#000000">
						$icon
					</td>
					<td bgcolor="#000000">
						<b>Domitor</b>
					</td>
					<td bgcolor="#000000">
						$friends
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						<b>Age</b>
					</td>
					<td bgcolor="#000000">
						$age
					</td>
					<td bgcolor="#000000">
						<b>Apparent Age</b>
					</td>
					<td bgcolor="#000000">
						$apparent_age
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						<b>Is NPC</b>
					</td>
					<td bgcolor="#000000">
						$is_npc
					</td>
					<td bgcolor="#000000">
						<b>Status</b>
					</td>
					<td bgcolor="#000000">
						$status
					</td>
				</tr>
				$admin_row
			</table>
		</td>
	</tr>
</table>
EOQ;

			$information_table = <<<EOQ
<table bgcolor="$table_bg_color" border="0" cellpadding="1" cellspacing="0" width="100%">
	<tr>
	  <td>
			<table border="0" cellpadding="2" cellspacing="1" class="$table_class" width="100%">
				<tr>
					<th colspan="2">
						Information
					</th>
				</tr>
				<tr>
					<td bgcolor="#000000" width="25%">
						<b>Concept</b>
					</td>
					<td bgcolor="#000000" width="75%">
						$concept
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						<b>Description</b>
					</td>
					<td bgcolor="#000000">
						$description
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						<b>URL</b>
					</td>
					<td bgcolor="#000000">
						$url
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						<b>Daily Equipment</b>
					</td>
					<td bgcolor="#000000">
						$equipment_public
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						<b>Other Equipment</b>
					</td>
					<td bgcolor="#000000">
						$equipment_hidden
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						<b>Public Effects</b>
					</td>
					<td bgcolor="#000000">
						$public_effects
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						<b>Home</b>
					</td>
					<td bgcolor="#000000">
						$safe_place
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						<b>Exit Line</b>
					</td>
					<td bgcolor="#000000">
						$exit_line
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
EOQ;

			$traits_table = <<<EOQ
<table bgcolor="$table_bg_color" border="0" cellpadding="1" cellspacing="0" width="100%">
	<tr>
	  <td>
			<table border="0" cellpadding="2" cellspacing="1" class="$table_class" width="100%">
				<tr>
					<th colspan="6">
						Traits
					</th>
				</tr>
				<tr>
					<td bgcolor="#000000" width="15%">
						Health
					</td>
					<td bgcolor="#000000" colspan="2" width="50%">
						$health_dots
					</td>
				  <td colspan="1" bgcolor="#000000" width="15%">
				  	Size
				  </td>
				  <td colspan="2" bgcolor="#000000" width="20%">
				  	$size
				  </td>
				</tr>
				<tr>
				  <td colspan="1" bgcolor="#000000">
				    Wounds
				  </td>
				  <td colspan="2" bgcolor="#000000">
				    Bashing: $wounds_bashing Lethal: $wounds_lethal Agg: $wounds_aggravated
				  </td>
				  <td colspan="1" bgcolor="#000000">
				  	Defense
				  </td>
				  <td colspan="2" bgcolor="#000000">
				  	$defense
				  </td>
				</tr>
				<tr>
				  <td colspan="1" bgcolor="#000000">
				  	Mortality
				  </td>
				  <td colspan="2" bgcolor="#000000">
				  	$morality_dots
				  </td>
				  <td colspan="1" bgcolor="#000000">
				  	Initiative Mod
				  </td>
				  <td colspan="2" bgcolor="#000000">
				  	$initiative_mod
				  </td>
				</tr>
				<tr>
				  <td bgcolor="#000000">
				  	Willpower Perm
				  </td>
				  <td colspan="2" bgcolor="#000000">
				  	$willpower_perm_dots
				  </td>
				  <td colspan="1" bgcolor="#000000">
				  	Speed
				  </td>
				  <td colspan="2" bgcolor="#000000">
				  	$speed
				  </td>
				</tr>
				<tr>
				  <td bgcolor="#000000">
				  	Willpower Temp
				  </td>
				  <td colspan="2" bgcolor="#000000">
				  	$willpower_temp_dots
				  </td>
				  <td colspan="1" bgcolor="#000000">
				  	Armor
				  </td>
				  <td colspan="2" bgcolor="#000000">
				  	$armor
				  </td>
				</tr>
				<tr>
				  <td bgcolor="#000000">
				  	Blood
				  </td>
				  <td colspan="2" bgcolor="#000000">
				  	$power_points_dots
				  </td>
				  <td colspan="1" bgcolor="#000000">
				  	
				  </td>
				  <td colspan="2" bgcolor="#000000">
				  	
				  </td>
				</tr>
			</table>
		</td>
	</tr>
</table>
EOQ;

			$history_table = <<<EOQ
<table bgcolor="$table_bg_color" border="0" cellpadding="1" cellspacing="0" width="100%">
	<tr>
	  <td>
			<table border="0" cellpadding="2" cellspacing="1" class="$table_class" width="100%">
				<tr>
					<th colspan="2">
						History
					</th>
				</tr>
				<tr>
					<td bgcolor="#000000" width="40%">
						<span class="highlight">Merits</span><br>
						<textarea rows="8" name="merits" id="merits" style="width:100%" $merits_edit>$merits</textarea>
					</td>
					<td bgcolor="#000000" width="60%">
						<span class="highlight">Disciplines</span><br>
						<textarea rows="8" name="powers" id="powers" style="width:100%" $powers_edit>$powers</textarea>
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000" width="40%">
						<span class="highlight">Flaws & Derangements</span><br>
						<textarea rows="8" name="flaws" id="flaws" style="width:100%" $merits_edit>$flaws</textarea>
					</td>
					<td bgcolor="#000000">
						<span class="highlight">History</span><br>
						<textarea rows="8" name="history" id="history" style="width:100%" $history_edit>$history</textarea>
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						<span class="highlight">Goals &amp; Beliefs</span><br>
						<textarea rows="8" name="goals" id="goals" style="width:100%" $goals_edit>$goals</textarea>
					</td>
					<td bgcolor="#000000" width="60%">
						<span class="highlight">Notes</span><br>
						<textarea rows="8" name="notes" id="notes" style="width:100%" $notes_edit>$notes</textarea>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
EOQ;
			break;
			
		default:
			$sheet .= "Not implemented yet.  $character_type_select<br>";
			break;
	}
	
	// put together general pieces
	$attribute_table = <<<EOQ
<table bgcolor="$table_bg_color" border="0" cellpadding="1" cellspacing="0" width="100%">
	<tr>
	  <td>
			<table border="0" cellpadding="2" cellspacing="1" class="$table_class" width="100%">
				<tr>
					<th colspan="6">
						Attributes
					</th>
				</tr>
				<tr>
					<td bgcolor="#000000">
						<b>
							Intelligence
						</b>
					</td>
					<td bgcolor="#000000">
						$intelligence_dots
					</td>
					<td bgcolor="#000000">
						<b>
							Strength
						</b>
					</td>
					<td bgcolor="#000000">
						$strength_dots
					</td>
					<td bgcolor="#000000">
						<b>
							Presence
						</b>
					</td>
					<td bgcolor="#000000">
						$presence_dots
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						<b>
							Wits
						</b>
					</td>
					<td bgcolor="#000000">
						$wits_dots
					</td>
					<td bgcolor="#000000">
						<b>
							Dexterity
						</b>
					</td>
					<td bgcolor="#000000">
						$dexterity_dots
					</td>
					<td bgcolor="#000000">
						<b>
							Manipulation
						</b>
					</td>
					<td bgcolor="#000000">
						$manipulation_dots
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						<b>
							Resolve
						</b>
					</td>
					<td bgcolor="#000000">
						$resolve_dots
					</td>
					<td bgcolor="#000000">
						<b>
							Stamina
						</b>
					</td>
					<td bgcolor="#000000">
						$stamina_dots
					</td>
					<td bgcolor="#000000">
						<b>
							Composure
						</b>
					</td>
					<td bgcolor="#000000">
						$composure_dots
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
EOQ;

	$skill_table = <<<EOQ
<table bgcolor="$table_bg_color" border="0" cellpadding="1" cellspacing="0" width="100%">
	<tr>
	  <td>
			<table border="0" cellpadding="2" cellspacing="1" class="$table_class" width="100%">
				<tr>
					<th colspan="9">
						Skills
					</th>
				</tr>
				<tr>
				  <th colspan="3" bgcolor="#000000">
				  	Mental
				  </th>
				  <th colspan="3" bgcolor="#000000">
				  	Physical
				  </th>
				  <th colspan="3" bgcolor="#000000">
				  	Social
				  </th>
				</tr>
				<tr>
					<td bgcolor="#000000">
						Academics
				  </td>
					<td bgcolor="#000000">
						$academics_spec
					</td>
					<td bgcolor="#000000">
						$academics_dots
					</td>
					<td bgcolor="#000000">
						Athletics
				  </td>
					<td bgcolor="#000000">
						$athletics_spec
					</td>
					<td bgcolor="#000000">
						$athletics_dots
					</td>
					<td bgcolor="#000000">
						Animal Ken
				  </td>
					<td bgcolor="#000000">
						$animal_ken_spec
					</td>
					<td bgcolor="#000000">
						$animal_ken_dots
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						Computer
				  </td>
					<td bgcolor="#000000">
						$computer_spec
					</td>
					<td bgcolor="#000000">
						$computer_dots
					</td>
					<td bgcolor="#000000">
						Brawl
				  </td>
					<td bgcolor="#000000">
						$brawl_spec
					</td>
					<td bgcolor="#000000">
						$brawl_dots
					</td>
					<td bgcolor="#000000">
						Empathy
				  </td>
					<td bgcolor="#000000">
						$empathy_spec
					</td>
					<td bgcolor="#000000">
						$empathy_dots
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						Crafts
				  </td>
					<td bgcolor="#000000">
						$crafts_spec
					</td>
					<td bgcolor="#000000">
						$crafts_dots
					</td>
					<td bgcolor="#000000">
						Drive
				  </td>
					<td bgcolor="#000000">
						$drive_spec
					</td>
					<td bgcolor="#000000">
						$drive_dots
					</td>
					<td bgcolor="#000000">
						Expression
				  </td>
					<td bgcolor="#000000">
						$expression_spec
					</td>
					<td bgcolor="#000000">
						$expression_dots
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						Investigation
				  </td>
					<td bgcolor="#000000">
						$investigation_spec
					</td>
					<td bgcolor="#000000">
						$investigation_dots
					</td>
					<td bgcolor="#000000">
						Firearms
				  </td>
					<td bgcolor="#000000">
						$firearms_spec
					</td>
					<td bgcolor="#000000">
						$firearms_dots
					</td>
					<td bgcolor="#000000">
						Intimidation
				  </td>
					<td bgcolor="#000000">
						$intimidation_spec
					</td>
					<td bgcolor="#000000">
						$intimidation_dots
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						Medicine
				  </td>
					<td bgcolor="#000000">
						$medicine_spec
					</td>
					<td bgcolor="#000000">
						$medicine_dots
					</td>
					<td bgcolor="#000000">
						Larceny
				  </td>
					<td bgcolor="#000000">
						$larceny_spec
					</td>
					<td bgcolor="#000000">
						$larceny_dots
					</td>
					<td bgcolor="#000000">
						Persuasion
				  </td>
					<td bgcolor="#000000">
						$persuasion_spec
					</td>
					<td bgcolor="#000000">
						$persuasion_dots
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						Occult
				  </td>
					<td bgcolor="#000000">
						$occult_spec
					</td>
					<td bgcolor="#000000">
						$occult_dots
					</td>
					<td bgcolor="#000000">
						Stealth
				  </td>
					<td bgcolor="#000000">
						$stealth_spec
					</td>
					<td bgcolor="#000000">
						$stealth_dots
					</td>
					<td bgcolor="#000000">
						Socialize
				  </td>
					<td bgcolor="#000000">
						$socialize_spec
					</td>
					<td bgcolor="#000000">
						$socialize_dots
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						Politics
				  </td>
					<td bgcolor="#000000">
						$politics_spec
					</td>
					<td bgcolor="#000000">
						$politics_dots
					</td>
					<td bgcolor="#000000">
						Survival
				  </td>
					<td bgcolor="#000000">
						$survival_spec
					</td>
					<td bgcolor="#000000">
						$survival_dots
					</td>
					<td bgcolor="#000000">
						Streetwise
				  </td>
					<td bgcolor="#000000">
						$streetwise_spec
					</td>
					<td bgcolor="#000000">
						$streetwise_dots
					</td>
				</tr>
				<tr>
					<td bgcolor="#000000">
						Science
				  </td>
					<td bgcolor="#000000">
						$science_spec
					</td>
					<td bgcolor="#000000">
						$science_dots
					</td>
					<td bgcolor="#000000">
						Weaponry
				  </td>
					<td bgcolor="#000000">
						$weaponry_spec
					</td>
					<td bgcolor="#000000">
						$weaponry_dots
					</td>
					<td bgcolor="#000000">
						Subterfuge
				  </td>
					<td bgcolor="#000000">
						$subterfuge_spec
					</td>
					<td bgcolor="#000000">
						$subterfuge_dots
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
EOQ;
	
	// put sheet pieces together
	$sheet .= <<<EOQ
<table id="character_table" width="800">
<tr>
<td>
$show_sheet_table
$vitals_table
$information_table
$attribute_table
$skill_table
$traits_table
$history_table
$st_notes_table
$submit_button
</td>
</tr>
</table>	
EOQ;

	return $sheet;
}
?>