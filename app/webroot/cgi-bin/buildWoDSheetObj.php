<?php
function buildWoDSheetObj(
    $stats, $character_type = 'Mortal', $edit_show_sheet = false, $edit_name = false,
    $edit_vitals = false, $edit_is_npc = false, $edit_is_dead = false,
    $edit_concept = false, $edit_description = false, $edit_url = false, $edit_equipment = false,
    $edit_public_effects = false, $edit_group = false, $edit_exit_line = false, $edit_is_npc = false,
    $edit_attributes = false, $edit_skills = false, $edit_perm_traits = false, $edit_temp_traits = false,
    $edit_powers = false, $edit_history = false, $edit_goals = false, $edit_login_note = false,
    $edit_experience = false, $show_st_notes_table = false, $view_is_asst = false, $view_is_st = false,
    $view_is_head = false, $view_is_admin = false, $may_edit = false, $edit_cell = false,
    $calculate_derived = false, $edit_xp = true)
{
    // initialize sheet
    $sheet = "";

    // element types
    $element_type['attribute'] = 1;
    $element_type['skill'] = 2;
    $element_type['merit'] = 3;
    $element_type['supernatural'] = 4;
    $element_type['power_trait'] = 5;
    $element_type['morality'] = 6;

    // initialize sheet values
    $attribute_xp = 135;
    $skill_xp = 105;
    $merit_xp = 32;
    $general_xp = 35;
    $supernatural_xp = 0;
    $number_of_specialties = 3;
    $number_of_merits = 5;

    //$character_types = array("Mortal", "Vampire", "Werewolf", "Mage", "Ghoul", "Psychic", "Thaumaturge", "Promethean", "Changeling", "Hunter", "Geist", "Purified", "Possessed");
    $character_types = array("Mortal", "Vampire", "Werewolf", "Mage", "Ghoul", "Changeling");

    $max_dots = 7;
    $attribute_list = array("strength", "dexterity", "stamina", "presence", "manipulation", "composure", "intelligence", "wits", "resolve");

    $skill_list_proper = array("Academics", "Animal Ken", "Athletics", "Brawl", "Computer", "Crafts", "Drive", "Empathy", "Expression", "Firearms", "Intimidation", "Investigation", "Larceny", "Medicine", "Occult", "Persuasion", "Politics", "Science", "Socialize", "Stealth", "Streetwise", "Subterfuge", "Survival", "Weaponry");

    $skill_list_proper_mage = array("Academics", "Animal Ken", "Athletics", "Brawl", "Computer", "Crafts", "Drive", "Empathy", "Expression", "Firearms", "Intimidation", "Investigation", "Larceny", "Medicine", "Occult", "Persuasion", "Politics", "Science", "Socialize", "Stealth", "Streetwise", "Subterfuge", "Survival", "Weaponry", "Rote Specialty");

    $skill_list = array("academics", "computer", "crafts", "investigation", "medicine", "occult", "politics", "science", "athletics", "brawl", "drive", "firearms", "larceny", "stealth", "survival", "weaponry", "animal_ken", "empathy", "expression", "intimidation", "persuasion", "socialize", "streetwise", "subterfuge");

    /*$table_bg_color = "#000000";
    $cell_bg_color = "#000000";*/
    $table_class = "normal_text";
    $input_class = "normal_input";

    $experience_help = "";
    $abilities_help = "";
    if ($edit_xp) {
        $experience_help = <<<EOQ
<span id="xp_sheet_xp_help_button" class="xp_sheet_help" onMouseOver="showHelp('xp_sheet_xp_help_box', event);") onMouseOut="hideHelp('xp_sheet_xp_help_box');">What's This?</span>
<div id="xp_sheet_xp_help_box" class="xp_sheet_help_box">
  These fields report how much XP you have left to spend on each part of the sheet. 
  The general XP field is used when you run out of XP in one of the other fields, say from buying extra attributes or skills.
</div>  				
EOQ;

        $abilities_help = <<<EOQ
<span id="xp_sheet_abilities_help_button" class="xp_sheet_help" onMouseOver="showHelp('xp_sheet_abilities_help_box', event);") onMouseOut="hideHelp('xp_sheet_abilities_help_box');">What's This?</span>
<div id="xp_sheet_abilities_help_box" class="xp_sheet_help_box">
  Below are fields for your characters abilities (disciplines, gifts, etc.). If you run out of space for merits, you can click the <b>Add Merit</b> link. The other abilities all have links to add more rows as necessary.
</div>  				
EOQ;
    }

    // set page colors based on type of character and supernatural XP
    switch ($character_type) {
        case 'Mortal':
            $table_class = "mortal_normal_text";
            break;

        case 'Psychic':
            $table_class = "mortal_normal_text";
            break;

        case 'Thaumaturge':
            $table_class = "mortal_normal_text";
            $splat1_groups = array("Ceremonial Magician", "Hedge Witch", "Shaman", "Taoist Alchemist", "Vodoun", "Apostle");
            $supernatural_xp = 0;
            break;

        case 'Vampire':
            $table_class = "vampire_normal_text";
            $splat1_groups = array("Daeva", "Gangrel", "Mekhet", "Nosferatu", "Ventrue");
            $splat2_groups = array("Carthian", "Circle of the Crone", "Invictus", "Lancea Sanctum", "Ordo Dracul", "Unaligned");
            $supernatural_xp = 40;
            break;

        case 'Werewolf':
            $table_class = "werewolf_normal_text";
            $splat1_groups = array("Rahu", "Cahalith", "Elodoth", "Ithaeur", "Irraka", "None");
            $splat2_groups = array("Blood Talons", "Bone Shadows", "Hunters in Darkness", "Iron Masters", "Storm Lords", "Ghost Wolves", "Fire-Touched", "Ivory Claws", "Predator Kings");
            $supernatural_xp = 40;
            $number_of_specialties = 4;
            break;

        case 'Mage':
            $table_class = "mage_normal_text";
            $splat1_groups = array("Acanthus", "Mastigos", "Moros", "Obrimos", "Thyrsus");
            $splat2_groups = array("The Adamantine Arrows", "Free Council", "Guardians of the Veil", "The Mysterium", "The Silver Ladder", "Apostate", "Seer of the Throne", "Banisher");
            $supernatural_xp = 75;
            break;

        case 'Ghoul':
            $table_class = "ghoul_normal_text";
            $supernatural_xp = 10;
            break;

        case 'Promethean':
            $table_class = "promethean_normal_text";
            $splat1_groups = array("Frankenstein", "Galatea", "Osiris", "Tammuz", "Uglan", "Pandoran", "Unfleshed", "Zeka");
            $splat2_groups = array("Aurum", "Cuprum", "Ferrum", "Mercurius", "Stannum", "Centimani", "Aes", "Argentum", "Cobalus", "Plumbum");
            $supernatural_xp = 30;
            break;

        case 'Changeling':
            $table_class = "changeling_normal_text";
            $splat1_groups = array("Beast", "Darkling", "Elemental", "Fairest", "Ogre", "Wizened");
            $splat2_groups = array("Spring", "Summer", "Autumn", "Winter", "Courtless");
            $supernatural_xp = 40;
            $apparent_age = "0";
            $number_of_specialties = 4;
            break;

        case 'Hunter':
            $table_class = "mortal_normal_text";
            $splat1_groups = array("Academic", "Artist", "Athlete", "Cop", "Criminal", "Detective", "Doctor", "Engineer", "Hacker", "Hit man", "Journalist", "Laborer", "Occultist", "Outdoorsman", "Professional", "Religious Leader", "Scientist", "Socialite", "Soldier", "Technician", "Vagrant");
            $splat2_groups = array("");
            break;

        case 'Geist':
            $table_class = "mortal_normal_text";
            $splat1_groups = array("Advocate", "Bonepicker", "Celebrant", "Gatekeeper", "Mourner", "Necromancer", "Pilgrim", "Reaper");
            $splat2_groups = array("Forgotten", "Prey", "Silent", "Stricken", "Torn");
            $supernatural_xp = 44;
            break;

        case 'Purified':
            $table_class = "mortal_normal_text";
            $supernatural_xp = 52;
            $merit_xp = 38;
            $skill_xp = 141;
            break;

        case 'Possessed':
            $table_class = "vampire_normal_text";
            $supernatural_xp = 30;
            break;

        default:
            $table_class = "mortal_normal_text";
            break;
    }


    // mods for ghouls
    if ($character_type == "Ghoul") {
        $morality = 6;
        $power_points = $stamina;
    }

    $next_power_stat_increase = "";

    // test if stats were passed
    if ($stats != "") {
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
        $misc_powers = $stats['Misc_Powers'];

        $proper_attribute_list = array("Strength", "Dexterity", "Stamina", "Presence", "Manipulation", "Composure", "Intelligence", "Wits", "Resolve");

        while (list($key, $attribute) = each($attribute_list)) {
            $$attribute = $stats[$proper_attribute_list[$key]];
        }

        reset($attribute_list);

        $proper_skill_list = array("Academics", "Computer", "Crafts", "Investigation", "Medicine", "Occult", "Politics", "Science", "Athletics", "Brawl", "Drive", "Firearms", "Larceny", "Stealth", "Survival", "Weaponry", "Animal_Ken", "Empathy", "Expression", "Intimidation", "Persuasion", "Socialize", "Streetwise", "Subterfuge");

        while (list($key, $skill) = each($skill_list)) {
            $$skill = $stats[$proper_skill_list[$key]];
            $skill_spec = $skill . "_spec";
            $proper_skill_spec = $proper_skill_list[$key] . "_Spec";
            $$skill_spec = $stats[$proper_skill_spec];
        }

        reset($skill_list);

        $power_trait = $stats['Power_Stat'];
        $willpower_perm = $stats['Willpower_Perm'];
        $willpower_temp = $stats['Willpower_Temp'];
        $morality = $stats['Morality'];
        $power_points = $stats['Power_Points'];
        $average_power_points = $stats['Average_Power_Points'];
        $power_points_modifier = $stats['Power_Points_Modifier'];
        $health = $stats['Health'];
        $size = $stats['Size'];
        $defense = $stats['Defense'];
        $initiative_mod = $stats['Initiative_Mod'];
        $speed = $stats['Speed'];
        $armor = $stats['Armor'];
        $wounds_bashing = $stats['Wounds_Bashing'];
        $wounds_lethal = $stats['Wounds_Lethal'];
        $wounds_aggravated = $stats['Wounds_Agg'];

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
        $bonus_attribute = $stats['Bonus_Attribute'];

        if (!$edit_xp && ($bonus_attribute != '')) {

            ${strtolower($bonus_attribute)}++;
        }

        $next_power_stat_increase = (($stats['Next_Power_Stat_Increase'] != "") && ($stats['Next_Power_Stat_Increase'] != "1969-12-31"))
            ? date('m/d/Y', strtotime($stats['Next_Power_Stat_Increase']))
            : date('m/d/Y', mktime(0, 0, 0, date('n') + 6, date('j'), date('Y')));
    }

    $show_sheet_table = "";
    if ($edit_show_sheet) {
        $show_sheet_yes_check = ($show_sheet == 'Y') ? "checked" : "";
        $show_sheet_no_check = ($show_sheet == 'N') ? "checked" : "";

        $hide_icon_yes_check = ($hide_icon == 'Y') ? "checked" : "";
        $hide_icon_no_check = ($hide_icon == 'N') ? "checked" : "";

        $show_sheet_table = <<<EOQ
<table class="character-sheet $table_class">
    <tr>
        <th colspan="2" align="center">
            Sheet Sharing
        </td>
    </tr>
    <tr>
        <td width="50%">
            Show sheet to Others:
            Yes: <input type="radio" name="show_sheet" id="show_sheet" value="Y" $show_sheet_yes_check>
            No: <input type="radio" name="show_sheet" id="show_sheet" value="N" $show_sheet_no_check>
        </td>
        <td width="50%">
            Password to View:
            <input type="text" name="view_password" id="view_password" value="$view_password" size="20" maxlength="30">
        </td>
    </tr>
    <tr>
      <td colspan="2">
        Use General Icon:
            Yes: <input type="radio" name="hide_icon" id="hide_icon" value="Y" $hide_icon_yes_check>
            No: <input type="radio" name="hide_icon" id="hide_icon" value="N" $hide_icon_no_check>
      </td>
</table>
EOQ;
    }

    $submit_button = "";
    if ($may_edit) {
        $submit_value = "Update Character";
        if (!$character_id) {
            $submit_value = "Create Character";
        }

        $submit_button = <<<EOQ
<table class="character-sheet $table_class">
	<tr>
	  <td align="center">
	  	<input type="hidden" name="character_id" id="character_id" value="$character_id">
	  	<input type="submit" name="submit" value="$submit_value" onClick="SubmitCharacter();return false;">
	  </td>
	</tr>
</table>
EOQ;

    }
    // create sheet values
    if ($edit_name) {
        $character_name = <<<EOQ
<input type="text" name="character_name" id="character_name" value="$character_name" size="30" maxlength="30">
EOQ;
    }

    if ($edit_vitals) {
        // edit character type
        $character_type_js = "onChange=\"changeSheet(window.document.character_sheet.character_type.value)\";";
        $character_type_select = buildSelect($character_type, $character_types, $character_types, "character_type", $character_type_js);

        // location
        $locations = array("Savannah", "San Diego", "The City", "Side Game");
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
        if ($view_is_admin || $view_is_head) {
            $icon_query = "select * from icons where Admin_Viewable='Y' order by Icon_Name;";
        } else if ($view_is_gm) {
            $icon_query = "select * from icons where GM_Viewable='Y' order by Icon_Name;";
        } else if ($view_is_asst) {
            $icon_query = "select * from icons where Player_Viewable='Y' order by Icon_Name;";
        } else if ($icon_query == "") {
            $icon_query = "select * from icons where Player_Viewable='Y' order by Icon_Name;";
        }
        $icon_result = mysql_query($icon_query) or die(mysql_error());

        $icon_ids = "";
        $icon_names = "";

        while ($icon_detail = mysql_fetch_array($icon_result, MYSQL_ASSOC)) {
            $icon_ids[] = $icon_detail['Icon_ID'];
            $icon_names[] = $icon_detail['Icon_Name'];
        }
        $icon = buildSelect($icon, $icon_ids, $icon_names, "icon");

        $splat1_select_js = "";
        $splat2_select_js = "";
        if ($edit_xp) {
            if ($character_type == 'Vampire') {
                $splat1_select_js = " onChange=\"updateBonusAttribute();\" ";
            }
            if ($character_type == 'Mage') {
                $splat1_select_js = " onChange=\"displayBonusDot();\" ";
                $splat2_select_js = " onChange=\"updateXP($element_type[merit]);\" ";
            }
            if ($character_type == 'Werewolf') {
                $splat1_select_js = " onChange=\"displayFreeWerewolfRenown();updateXP($element_type[supernatural]);\" ";
                $splat2_select_js = " onChange=\"displayFreeWerewolfRenown();updateXP($element_type[supernatural]);\" ";
            }
            if ($character_type == "Thaumaturge") {
                $splat1_select_js = " onChange=\"addThaumaturgeDefiningMerit();updateXP($element_type[merit]);\" ";
            }
        }

        $splat1 = buildSelect($splat1, $splat1_groups, $splat1_groups, "splat1", $splat1_select_js);
        $splat2 = buildSelect($splat2, $splat2_groups, $splat2_groups, "splat2", $splat2_select_js);

        $subsplat = <<<EOQ
<input type="text" name="subsplat" id="subsplat" value="$subsplat" size="20" maxlength="30">
EOQ;

        $age = <<<EOQ
<input type="text" name="age" id="age" value="$age" size="4" maxlength="4">
EOQ;

        $apparent_age = <<<EOQ
<input type="text" name="apparent_age" id="apparent_age" value="$apparent_age" size="4" maxlength="4">
EOQ;
    } else {
        // get name of icon
        $icon = $stats['Icon_Name'];

        // have a hidden form field for character dots
        $character_type_select = <<<EOQ
$character_type
<input type="hidden" name="character_type" id="character_type" value="$character_type">
EOQ;
    }

    if ($edit_is_npc) {
        $is_npc_check = "";
        if ($is_npc == 'Y') {
            $is_npc_check = "checked";
        }

        $is_npc = <<<EOQ
<input type="checkbox" name="is_npc" id="is_npc" value="Y" $is_npc_check>
EOQ;
    }

    if ($edit_is_dead) {
        $statuses = array("Ok", "Imprisoned", "Hospitalized", "Torpored", "Dead");
        $status = buildSelect($status, $statuses, $statuses, "status");
    }

    // concept
    if ($edit_concept) {
        $concept = <<<EOQ
<input type="text" name="concept" id="concept" value="$concept" size="50" maxlength="255">
EOQ;
    }

    // description
    if ($edit_description) {
        $description = <<<EOQ
<input type="text" name="description" id="description" value="$description" size="50" maxlength="400">
EOQ;
    }

    // url
    if ($edit_url) {
        $url = <<<EOQ
<input type="text" name="url" id="url" value="$url" size="50" maxlength="255">
EOQ;
    }

    // $edit_group, $edit_exit_line,
    // equipment
    if ($edit_equipment) {
        $equipment_public = <<<EOQ
<input type="text" name="equipment_public" id="equipment_public" value="$equipment_public" size="50" maxlength="255">
EOQ;

        $equipment_hidden = <<<EOQ
<input type="text" name="equipment_hidden" id="equipment_hidden" value="$equipment_hidden" size="50" maxlength="255">
EOQ;
    }

    if ($edit_public_effects) {
        $public_effects = <<<EOQ
<input type="text" name="public_effects" id="public_effects" value="$public_effects" size="50" maxlength="255">
EOQ;
    }

    if ($edit_group) {
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

    if ($edit_exit_line) {
        $exit_line = <<<EOQ
<input type="text" name="exit_line" id="exit_line" value="$exit_line" size="50" maxlength="255">
EOQ;
    }


    reset($attribute_list);

    while (list($key, $attribute) = each($attribute_list)) {
        $attribute_dots = $attribute . "_dots";
        $$attribute_dots = makeDotsXP($attribute, $element_type['attribute'], $character_type, $max_dots, $$attribute, $edit_attributes, $calculate_derived, $edit_xp);
    }

    reset($skill_list);

    while (list($key, $skill) = each($skill_list)) {
        $skill_dots = $skill . "_dots";
        $skill_spec = $skill . "_spec";
        if ($edit_skills) {
            $$skill_spec = <<<EOQ
<input type="text" name="$skill_spec" id="$skill_spec" value="${$skill_spec}" size="10" maxlength="100">
EOQ;
        }

        $$skill_dots = makeDotsXP($skill, $element_type['skill'], $character_type, $max_dots, $$skill, $edit_skills, false, $edit_xp);
    }


    $power_trait_dots = makeDotsXP("power_trait", $element_type['power_trait'], $character_type, 10, $power_trait, $edit_perm_traits, false, $edit_xp);
    $willpower_perm_dots = makeDotsXP("willpower_perm", 0, $character_type, 10, $willpower_perm, $edit_perm_traits, false);
    $willpower_temp_dots = makeDotsXP("willpower_temp", 0, $character_type, 10, $willpower_temp, (($edit_temp_traits && $is_sanctioned == "") || ($view_is_asst || $view_is_st || $view_is_head || $view_is_admin)), false);
    $morality_dots = makeDotsXP("morality", $element_type['morality'], $character_type, 10, $morality, $edit_perm_traits, false, $edit_xp);
    $power_points_dots = makeDotsXP("power_points", 0, $character_type, 20, $power_points, $edit_temp_traits, false);
    $health_dots = makeDotsXP("health", 0, $character_type, 15, $health, $edit_perm_traits, false);

    if ($edit_perm_traits) {
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

        $power_points_modifier = <<<EOQ
<input type="text" name="power_points_modifier" id="power_points_modifier" size="5" maxlength="4" value="$power_points_modifier">
EOQ;

        if ($stats) {
            $next_power_stat_increase = <<<EOQ
<input type="text" name="next_power_stat_increase" id="next_power_stat_increase" size="10" maxlength="10" value="$next_power_stat_increase">
EOQ;
        }
    }

    if ($edit_temp_traits) {
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

    $character_merit_list = "";

    if ($edit_powers) {
        // update merits
        $character_merit_list = <<<EOQ
<a href="#" onClick="addMerit();return false;">Add Merit</a><br>
EOQ;
    }

    $merit_js = "";
    if ($edit_xp) {
        $merit_js = " onChange=\"updateXP($element_type[merit]);\" ";
    }

    $character_merit_list .= <<<EOQ
<table name="merit_list" id="merit_list" border="0" cellspacing="1" cellpadding="1" class="normal_text">
  <tr>
    <th>
      Merit Name
    </th>
    <th>
      Notes
    </th>
    <th>
      Level
    </th>
  </tr>
EOQ;

    $merits = getPowers($character_id, "Merit", NAMENOTE, 5);

    // process merit list
    for ($i = 0; $i < sizeof($merits); $i++) {
        $merit_dots = makeDotsXP("merit${i}", $element_type['merit'], $character_type, $max_dots, $merits[$i]->getPowerLevel(), $edit_powers, false, $edit_xp);

        $merit_name = $merits[$i]->getPowerName();
        $merit_note = $merits[$i]->getPowerNote();
        $merit_id = $merits[$i]->getPowerID();

        if ($edit_powers) {
            $merit_name = <<<EOQ
<input type="text" name="merit${i}_name" id="merit${i}_name" size="15" maxlength="40" class="$input_class" value="$merit_name" $merit_js>
EOQ;
            $merit_note = <<<EOQ
<input type="text" name="merit${i}_note" id="merit${i}_note" size="20" maxlength="40" class="$input_class" value="$merit_note" $merit_js>
EOQ;
        }

        $character_merit_list .= <<<EOQ
<tr>
<td>
$merit_name
</td>
<td>
$merit_note
</td>
<td>
$merit_dots
<input type="hidden" name="merit${i}_id" id="merit${i}_id" value="$merit_id">
</td>
</tr>
EOQ;
    }

    $character_merit_list .= "</table>";

    // flaws
    $character_flaw_list = "";

    if ($edit_powers) {
        // update flaws
        $character_flaw_list = <<<EOQ
<a href="#" onClick="addFlaw();return false;">Add Flaw/Derangement</a><br>
EOQ;
    }

    $character_flaw_list .= <<<EOQ
<table name="flaw_list" id="flaw_list" border="0" cellspacing="1" cellpadding="1" class="normal_text">
  <tr>
    <th>
      Flaw Name
    </th>
  </tr>
EOQ;

    $flaws = getPowers($character_id, 'Flaw', NAMENOTE, 2);

    // make blank list
    for ($i = 0; $i < sizeof($flaws); $i++) {
        $flaw_name = $flaws[$i]->getPowerName();
        $flaw_id = $flaws[$i]->getPowerID();

        if ($edit_powers) {
            $flaw_name = <<<EOQ
<input type="text" name="flaw${i}_name" id="flaw${i}_name" size="15" maxlength="40" class="$input_class" value="$flaw_name" $flaw_js>
EOQ;
        }

        $character_flaw_list .= <<<EOQ
<tr>
<td>
$flaw_name
<input type="hidden" name="flaw${i}_id" id="flaw${i}_id" value="$flaw_id">
</td>
</tr>
EOQ;
    }

    $character_flaw_list .= "</table>";


    if ($edit_history) {
        $history_edit = "";
    }

    if ($edit_goals) {
        $goals_edit = "";
        $notes_edit = "";
    }

    $notes_box = "";
    if ($edit_cell) {
        $cell_query = "select distinct Cell_ID from gm_permissions order by Cell_ID;";
        $cell_result = mysql_query($cell_query) or die(mysql_error());

        $cell_ids = "";
        while ($cell_detail = mysql_fetch_array($cell_result, MYSQL_ASSOC)) {
            $cell_ids[] = $cell_detail['Cell_ID'];
        }

        $cell_id = buildSelect($cell_id, $cell_ids, $cell_ids, "cell_id");
    }

    if ($edit_login_note) {
        $login_note = <<<EOQ
<input type="text" name="login_note" id="login_note" value="$login_note" size="70" maxlength="250">
EOQ;
    }

    // create human readable version of status
    $temp_asst = ($asst_sanctioned == "") ? "X" : $asst_sanctioned;
    $temp_sanc = ($is_sanctioned == "") ? "X" : $is_sanctioned;
    $temp_head = ($head_sanctioned == "") ? "X" : $head_sanctioned;

    $temp_status = $temp_sanc . $temp_asst;

    switch ($temp_status) {
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

    if ($show_st_notes_table) {
        if ($view_is_head) {
            $head_sanc_yes_check = ($head_sanctioned == 'Y') ? "checked" : "";
            $head_sanc_no_check = ($head_sanctioned == 'N') ? "checked" : "";
            $head_sanctioned = <<<EOQ
Yes: <input type="radio" name="head_sanctioned" value="Y" $head_sanc_yes_check>
No: <input type="radio" name="head_sanctioned" value="N" $head_sanc_no_check>
EOQ;
        }

        if ($view_is_st) {
            $sanc_yes_check = ($is_sanctioned == 'Y') ? "checked" : "";
            $sanc_no_check = ($is_sanctioned == 'N') ? "checked" : "";
            $is_sanctioned = <<<EOQ
Yes: <input type="radio" name="is_sanctioned" value="Y" $sanc_yes_check>
No: <input type="radio" name="is_sanctioned" value="N" $sanc_no_check>
EOQ;
        }

        if ($view_is_asst) {
            $asst_sanc_yes_check = ($asst_sanctioned == 'Y') ? "checked" : "";
            $asst_sanc_no_check = ($asst_sanctioned == 'N') ? "checked" : "";
            $asst_sanctioned = <<<EOQ
Yes: <input type="radio" name="asst_sanctioned" value="Y" $asst_sanc_yes_check>
No: <input type="radio" name="asst_sanctioned" value="N" $asst_sanc_no_check>
EOQ;
        }

        if ($edit_experience) {
            $current_experience = <<<EOQ
<input type="text" name="current_experience" value="$current_experience" size="5" maxlength="7">
EOQ;

            $total_experience = <<<EOQ
<input type="text" name="total_experience" value="$total_experience" size="5" maxlength="7">
EOQ;
        }

        $st_notes_table = <<<EOQ
<table class="character-sheet $table_class">
    <tr>
        <th colspan="4">
            Storyteller Information
        </th>
    </tr>
    <tr>
        <td>
            Login Note: 
        </td>
        <td colspan="3">
            $login_note
        </td>
    </tr>
    <tr>
      <td width="25%">
        Created On:
      </td>
      <td width="25%">
        $first_login
      </td>
      <td width="25%">
        Last Login
      </td>
      <td width="25%">
        $last_login
      </td>
    </tr> 
    <tr>
      <td width="25%">
        Primary Profile
      </td>
      <td width="25%">
        $stats[Name]
      </td>
      <td width="25%">
        Cell ID
      </td>
      <td width="25%">
        $cell_id
      </td>
    </tr> 
    <tr>
      <td width="25%">
        Head Sanctioned
      </td>
      <td width="25%">
        $head_sanctioned
      </td>
      <td width="25%">
        Last ST Updated
      </td>
      <td width="25%">
        $last_st_updated
      </td>
    </tr> 
    <tr>
      <td width="25%">
        Is Sanctioned
      </td>
      <td width="25%">
        $is_sanctioned
      </td>
      <td width="25%">
        When Last ST Updated
      </td>
      <td width="25%">
        $when_last_st_updated
      </td>
    </tr> 
    <tr>
      <td width="25%">
        Pre-Sanctioned
      </td>
      <td width="25%">
        $asst_sanctioned
      </td>
      <td width="25%">
        Last Cell ST Updated
      </td>
      <td width="25%">
        $last_asst_st_updated
      </td>
    </tr> 
    <tr>
      <td width="25%">
        Status:
      </td>
      <td width="25%">
        $view_status
      </td>
      <td width="25%">
        When Last Cell ST Updated
      </td>
      <td width="25%">
        $when_last_asst_st_updated
      </td>
    </tr> 
    <tr>
      <td width="25%">
        Experience Unspent:
      </td>
      <td width="25%">
        $current_experience
      </td>
      <td width="25%">
        Total Experience Earned:
      </td>
      <td width="25%">
        $total_experience
      </td>
    </tr> 
    <tr>
      <td colspan="2">
        Past ST Updates: Write all updates you do to a character sheet here. *MANDATORY*<br>
        <textarea name="sheet_updates" rows="6" cols="40" readonly>$sheet_updates</textarea>
        <br>
        Your Updates to add:<br>
        <textarea name="new_sheet_updates" rows="6" cols="40"></textarea>
      </td>
      <td colspan="2">
        Past ST Notes: Personal notes and comments about the character. Not a mandatory field.<br>
        <textarea name="gm_notes" rows="6" cols="40" readonly>$gm_notes</textarea>
        <br>
        Your Notes to add:<br>
        <textarea name="new_gm_notes" rows="6" cols="40"></textarea>
      </td>
    </tr> 
</table>
EOQ;

    } else {
        $st_notes_table = <<<EOQ
<table class="character-sheet $table_class">
    <tr>
        <th colspan="3" align="center">
            Player Information
        </th>
    </tr>
    <tr>
        <td colspan="3">
            Login Note:
            $login_note
        </td>
    </tr>
    <tr>
        <td>
            Cell ID:
            $cell_id
        </td>
        <td>
        </td>
        <td>
        </td>
    </tr>
    <tr>
        <td width="34%">
        Status:
        $view_status
        </td>
        <td width="33%">
            Experience Unspent:
            $current_experience
        </td>
        <td width="33%">
            Total Experience Earned:
            $total_experience
        </td>
    </tr>
    <tr>
        <td width="34%">
            Last ST to View:
            $last_st_updated
        </td>
        <td width="33%">
            Updated On:
            $when_last_st_updated
        </td>
        <td width="33%">
            Created On:
            $first_login
        </td>
    </tr>
</table>
EOQ;
    }

    $vitals_table = "Vitals Not Done Yet<br>";
    $information_table = "Information Not Done Yet<br>";
    $traits_table = "Traits Not Done Yet<br>";
    switch ($character_type) {
        case 'Mortal':
            /** @noinspection PhpIncludeInspection */
            include 'includes/build_sheet_mortal.php';
            break;

        case 'Psychic':
            /** @noinspection PhpIncludeInspection */
            include 'includes/build_sheet_psychic.php';
            break;

        case 'Thaumaturge':
            /** @noinspection PhpIncludeInspection */
            include 'includes/build_sheet_thaumaturge.php';
            break;

        case 'Werewolf':
            /** @noinspection PhpIncludeInspection */
            include 'includes/build_sheet_werewolf.php';
            break;

        case 'Vampire':
            /** @noinspection PhpIncludeInspection */
            include 'includes/build_sheet_vampire.php';
            break;

        case 'Mage':
            /** @noinspection PhpIncludeInspection */
            include 'includes/build_sheet_mage.php';
            break;

        case 'Ghoul':
            /** @noinspection PhpIncludeInspection */
            include 'includes/build_sheet_ghoul.php';
            break;

        case 'Promethean':
            /** @noinspection PhpIncludeInspection */
            include 'includes/build_sheet_promethean.php';
            break;

        case 'Changeling':
            /** @noinspection PhpIncludeInspection */
            include 'includes/build_sheet_changeling.php';
            break;

        case 'Hunter':
            /** @noinspection PhpIncludeInspection */
            include 'includes/build_sheet_hunter.php';
            break;

        case 'Geist':
            /** @noinspection PhpIncludeInspection */
            include 'includes/build_sheet_geist.php';
            break;

        case 'Purified':
            /** @noinspection PhpIncludeInspection */
            include 'includes/build_sheet_purified.php';
            break;

        case 'Possessed':
            /** @noinspection PhpIncludeInspection */
            include 'includes/build_sheet_possessed.php';
            break;

        default:
            $sheet .= "Not implemented yet.  $character_type_select<br>";
            break;
    }

    // put together general pieces
    $xp_row = "";
    if ($edit_xp) {
        $xp_row = <<<EOQ
<tr>
    <th colspan="6">
        <b>Experience Remaining</b>
        $experience_help
    </th>
</tr>
<tr>
    <th colspan="6">
        Attributes: <input type="text" name="attribute_xp" id="attribute_xp" size="3" value="$attribute_xp" readonly>
        &nbsp;&nbsp;
        Skills: <input type="text" name="skill_xp" id="skill_xp" size="3" value="$skill_xp" readonly>
        &nbsp;&nbsp;
        Merits: <input type="text" name="merit_xp" id="merit_xp" size="3" value="$merit_xp" readonly>
        &nbsp;&nbsp;
        Supernatural: <input type="text" name="supernatural_xp" id="supernatural_xp" size="3" value="$supernatural_xp" readonly>
        &nbsp;&nbsp;
        General: <input type="text" name="general_xp" id="general_xp" size="3" value="$general_xp" readonly>
    </th>
</tr>
EOQ;
    }
    /** @var $intelligence_dots string */
    /** @var $strength_dots string */
    /** @var $presence_dots string */
    /** @var $wits_dots string */
    /** @var $dexterity_dots string */
    /** @var $manipulation_dots string */
    /** @var $resolve_dots string */
    /** @var $stamina_dots string */
    /** @var $composure_dots string */
    $attribute_table = <<<EOQ
<table class="character-sheet $table_class">
    $xp_row
    <tr>
        <th colspan="6" align="center">
            Attributes
            <span id="attribute_div"></span>
            <input type="hidden" name="bonus_attribute" id="bonus_attribute" value="$bonus_attribute">
        </th>
    </tr>
    <tr>
        <td>
            <b>
                Intelligence
            </b>
        </td>
        <td>
            $intelligence_dots
        </td>
        <td>
            <b>
                Strength
            </b>
        </td>
        <td>
            $strength_dots
        </td>
        <td>
            <b>
                Presence
            </b>
        </td>
        <td>
            $presence_dots
        </td>
    </tr>
    <tr>
        <td>
            <b>
                Wits
            </b>
        </td>
        <td>
            $wits_dots
        </td>
        <td>
            <b>
                Dexterity
            </b>
        </td>
        <td>
            $dexterity_dots
        </td>
        <td>
            <b>
                Manipulation
            </b>
        </td>
        <td>
            $manipulation_dots
        </td>
    </tr>
    <tr>
        <td>
            <b>
                Resolve
            </b>
        </td>
        <td>
            $resolve_dots
        </td>
        <td>
            <b>
                Stamina
            </b>
        </td>
        <td>
            $stamina_dots
        </td>
        <td>
            <b>
                Composure
            </b>
        </td>
        <td>
            $composure_dots
        </td>
    </tr>
</table>
EOQ;

    // make list of specialties
    $specialties_list = "";

    if ($edit_skills) {
        $specialties_list .= <<<EOQ
<a href="#" onClick="addSpecialty();return false;">Add Specialty</a><br>
EOQ;

    }

    $specialties_list .= <<<EOQ
<table name="specialties_list" id="specialties_list">
    <tr>
        <th>
            Skill
        </th>
        <th>
            Specialty
        </th>
  </tr>
EOQ;

    $specialty_js = "";
    if ($edit_xp) {
        $specialty_js = " onChange=\"updateXP($element_type[skill])\" ";
    }

    // get specialties
    $specialties = getPowers($character_id, "Specialty", NOTENAME, $number_of_specialties);
    for ($i = 0; $i < sizeof($specialties); $i++) {
        $specialty_skill = $specialties[$i]->getPowerNote();
        $specialty_name = $specialties[$i]->getPowerName();
        $specialty_id = $specialties[$i]->getPowerID();

        if ($character_type == 'Mage') {
            $specialties_dropdown = buildSelect($specialty_skill, $skill_list_proper_mage, $skill_list_proper_mage, "skill_spec${i}_selected", "class=\"$input_class\" $specialty_js");
        } else {
            $specialties_dropdown = buildSelect($specialty_skill, $skill_list_proper, $skill_list_proper, "skill_spec${i}_selected", "class=\"$input_class\" $specialty_js");
        }

        if ($edit_skills) {
            $specialty_skill = $specialties_dropdown;
            $specialty_name = <<<EOQ
<input type="text" name="skill_spec${i}" id="skill_spec${i}" class="$input_class" $specialty_js value="$specialty_name">
EOQ;
        }

        $specialties_list .= <<<EOQ
  <tr>
    <td>
      $specialty_skill
    </td>
    <td>
      $specialty_name
      <input type="hidden" name="skill_spec${i}_id" id="skill_spec${i}_id" value="$specialty_id">
    </td>
  </tr>
EOQ;

    }

    $specialties_list .= "</table>";

    /** @var $academics_dots string */
    /** @var $athletics_dots string */
    /** @var $animal_ken_dots string */
    /** @var $computer_dots string */
    /** @var $brawl_dots string */
    /** @var $empathy_dots string */
    /** @var $crafts_dots string */
    /** @var $drive_dots string */
    /** @var $expression_dots string */
    /** @var $investigation_dots string */
    /** @var $firearms_dots string */
    /** @var $intimidation_dots string */
    /** @var $medicine_dots string */
    /** @var $larceny_dots string */
    /** @var $persuasion_dots string */
    /** @var $occult_dots string */
    /** @var $stealth_dots string */
    /** @var $socialize_dots string */
    /** @var $politics_dots string */
    /** @var $survival_dots string */
    /** @var $streetwise_dots string */
    /** @var $science_dots string */
    /** @var $weaponry_dots string */
    /** @var $subterfuge_dots string */
    $skill_table = <<<EOQ
<table class="character-sheet $table_class">
    <tr>
        <th colspan="7" align="center">
            Skills
        </th>
    </tr>
    <tr>
      <th colspan="2">
        Mental
      </th>
      <th colspan="2">
        Physical
      </th>
      <th colspan="2">
        Social
      </th>
      <th>
        Specialties
      </th>
    </tr>
    <tr valign="top">
        <td>
            Academics
        </td>
        <td>
            $academics_dots
        </td>
        <td>
            Athletics
        </td>
        <td>
            $athletics_dots
        </td>
        <td>
            Animal Ken
        </td>
        <td>
            $animal_ken_dots
        </td>
        <td rowspan="11" valign="top">
            $specialties_list
        </td>
    </tr>
    <tr>
        <td>
            Computer
        </td>
        <td>
            $computer_dots
        </td>
        <td>
            Brawl
        </td>
        <td>
            $brawl_dots
        </td>
        <td>
            Empathy
        </td>
        <td>
            $empathy_dots
        </td>
    </tr>
    <tr>
        <td>
            Crafts
        </td>
        <td>
            $crafts_dots
        </td>
        <td>
            Drive
        </td>
        <td>
            $drive_dots
        </td>
        <td>
            Expression
        </td>
        <td>
            $expression_dots
        </td>
    </tr>
    <tr>
        <td>
            Investigation
        </td>
        <td>
            $investigation_dots
        </td>
        <td>
            Firearms
        </td>
        <td>
            $firearms_dots
        </td>
        <td>
            Intimidation
        </td>
        <td>
            $intimidation_dots
        </td>
    </tr>
    <tr>
        <td>
            Medicine
        </td>
        <td>
            $medicine_dots
        </td>
        <td>
            Larceny
        </td>
        <td>
            $larceny_dots
        </td>
        <td>
            Persuasion
        </td>
        <td>
            $persuasion_dots
        </td>
    </tr>
    <tr>
        <td>
            Occult
        </td>
        <td>
            $occult_dots
        </td>
        <td>
            Stealth
        </td>
        <td>
            $stealth_dots
        </td>
        <td>
            Socialize
        </td>
        <td>
            $socialize_dots
        </td>
    </tr>
    <tr>
        <td>
            Politics
        </td>
        <td>
            $politics_dots
        </td>
        <td>
            Survival
        </td>
        <td>
            $survival_dots
        </td>
        <td>
            Streetwise
        </td>
        <td>
            $streetwise_dots
        </td>
    </tr>
    <tr>
        <td>
            Science
        </td>
        <td>
            $science_dots
        </td>
        <td>
            Weaponry
        </td>
        <td>
            $weaponry_dots
        </td>
        <td>
            Subterfuge
        </td>
        <td>
            $subterfuge_dots
        </td>
    </tr>
    <tr>
      <td colspan="6">
      </td>
    </tr>
</table>
EOQ;

    $history_table = <<<EOQ
<table class="character-sheet $table_class">
    <tr>
        <th colspan="2" align="center">
            History
        </th>
    </tr>
    <tr>
        <td width="40%">
            <span class="highlight"><span class="$table_class">Goals &amp; Beliefs</span></span><br>
            <textarea rows="8" name="goals" id="goals" style="width:100%" $goals_edit>$goals</textarea>
        </td>
        <td width="60%">
            <span class="highlight"><span class="$table_class">Misc Powers/Abilities</span></span><br>
            <textarea rows="8" name="misc_powers" id="misc_powers" style="width:100%" $edit_powers>$misc_powers</textarea>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <span class="highlight"><span class="$table_class">History</span></span><br>
            <textarea rows="8" name="history" id="history" style="width:100%" $history_edit>$history</textarea>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <span class="highlight"><span class="$table_class">Notes</span></span><br>
            <textarea rows="8" name="notes" id="notes" style="width:100%" $notes_edit>$notes</textarea>
        </td>
    </tr>
</table>
EOQ;

    // put sheet pieces together
    $sheet .= <<<EOQ
<table id="character_table"width="800px">
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

/**
 * @param $character_id
 * @param $power_type
 * @param $sort_order
 * @param $number_of_blanks
 * @return Power[]
 */
function getPowers($character_id, $power_type, $sort_order, $number_of_blanks)
{
    $power_list = array();

    if ($character_id) {
        $order_by = "";
        switch ($sort_order) {
            case NAMELEVEL:
                $order_by = "PowerName, PowerLevel";
                break;
            case NOTELEVEL:
                $order_by = "PowerNote, PowerLevel";
                break;
            case NOTENAME:
                $order_by = "PowerNote, PowerName";
                break;
            case NAMENOTE:
                $order_by = "PowerName, PowerNote";
                break;
            default:
                $order_by = "PowerName, PowerLevel";
                break;
        }


        $query = "select * from wod_characters_powers where characterID = $character_id and powerType = '$power_type' Order by $order_by;";
        $result = mysql_query($query) or die(mysql_error());

        while ($detail = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $power = new Power();

            $power->setPowerName($detail['PowerName']);
            $power->setPowerNote($detail['PowerNote']);
            $power->setPowerLevel($detail['PowerLevel']);
            $power->setPowerID($detail['PowerID']);

            $power_list[] = $power;
        }
    } else {
        for ($i = 0; $i < $number_of_blanks; $i++) {
            $power = new Power();

            $power->setPowerName("");
            $power->setPowerNote("");
            $power->setPowerLevel(0);
            $power->setPowerID(0);

            $power_list[] = $power;
        }
    }

    return $power_list;
}

function getRenownsRituals($character_id)
{
    $renown_list = array();

    $renowns = array("purity", "honor", "glory", "wisdom", "cunning");

    for ($i = 0; $i < 5; $i++) {
        $renown = new Power();
        $renown->setPowerName("");
        $renown->setPowerLevel("");
        $renown->setPowerID(0);

        $renown_list[$renowns[$i]] = $renown;
    }

    $renown = new Power();
    $renown->setPowerName("");
    $renown->setPowerLevel($detail["PowerLevel"]);
    $renown->setPowerID($detail["PowerID"]);

    $renown_list["rituals"] = $renown;

    if ($character_id) {
        $query = "select * from wod_characters_powers where characterID = $character_id and powerType = 'Renown' Order by PowerName;";
        $result = mysql_query($query) or die(mysql_error());

        while ($detail = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $renown = new Power();
            $renown->setPowerName($detail["PowerName"]);
            $renown->setPowerLevel($detail["PowerLevel"]);
            $renown->setPowerID($detail["PowerID"]);
            $renown_name = strtolower($detail["PowerName"]);

            $renown_list[$renown_name] = $renown;
        }

        $query = "select * from wod_characters_powers where characterID = $character_id and powerType = 'Rituals' Order by PowerName;";
        $result = mysql_query($query) or die(mysql_error());

        while ($detail = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $renown = new Power();
            $renown->setPowerName($detail["PowerName"]);
            $renown->setPowerLevel($detail["PowerLevel"]);
            $renown->setPowerID($detail["PowerID"]);

            $renown_list["rituals"] = $renown;
        }
    }

    return $renown_list;
}

class Power
{
    var $power_name;
    var $power_note;
    var $power_level;
    var $power_id;

    function getPowerName()
    {
        return $this->power_name;
    }

    function setPowerName($power_name)
    {
        $this->power_name = $power_name;
    }

    function getPowerNote()
    {
        return $this->power_note;
    }

    function setPowerNote($power_note)
    {
        $this->power_note = $power_note;
    }

    function getPowerLevel()
    {
        return $this->power_level;
    }

    function setPowerLevel($power_level)
    {
        $this->power_level = $power_level;
    }

    function getPowerID()
    {
        return $this->power_id;
    }

    function setPowerID($power_id)
    {
        $this->power_id = $power_id;
    }
}