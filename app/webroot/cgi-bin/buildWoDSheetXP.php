<?php
use classes\core\helpers\Configuration;
use classes\core\helpers\FormHelper;
use classes\core\helpers\UserdataHelper;

function buildWoDSheetXP(
    $stats, $character_type = 'Mortal', $edit_show_sheet = false, $edit_name = false,
    $edit_vitals = false, $edit_is_npc = false, $edit_is_dead = false, /** @noinspection PhpUnusedParameterInspection */
    $edit_location = false,
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
    $element_type['attribute']    = 1;
    $element_type['skill']        = 2;
    $element_type['merit']        = 3;
    $element_type['supernatural'] = 4;
    $element_type['power_trait']  = 5;
    $element_type['morality']     = 6;

    // initialize sheet values
    $attribute_xp          = 135;
    $skill_xp              = 105;
    $merit_xp              = 32;
    $general_xp            = Configuration::read('GENERAL_XP');
    $supernatural_xp       = 0;
    $number_of_specialties = 3;
    $number_of_merits      = 5;

    //$character_types = array("Mortal", "Vampire", "Werewolf", "Mage", "Ghoul", "Psychic", "Thaumaturge", "Promethean", "Changeling", "Hunter", "Geist", "Purified", "Possessed");
    $character_types = array("Mortal", "Vampire", "Ghoul", "Werewolf", "Wolfblooded", "Mage", "Sleepwalker", "Changeling", "Geist");

    $max_dots = 7;

    $skill_list_proper = array("Academics", "Animal Ken", "Athletics", "Brawl", "Computer", "Crafts", "Drive", "Empathy", "Expression", "Firearms", "Intimidation", "Investigation", "Larceny", "Medicine", "Occult", "Persuasion", "Politics", "Science", "Socialize", "Stealth", "Streetwise", "Subterfuge", "Survival", "Weaponry");

    $skill_list_proper_mage = array("Academics", "Animal Ken", "Athletics", "Brawl", "Computer", "Crafts", "Drive", "Empathy", "Expression", "Firearms", "Intimidation", "Investigation", "Larceny", "Medicine", "Occult", "Persuasion", "Politics", "Science", "Socialize", "Stealth", "Streetwise", "Subterfuge", "Survival", "Weaponry", "Rote Specialty");


    $table_class = "normal_text";
    $input_class = "normal_input";

    $experience_help = "";
    $abilities_help  = "";
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
    $characterId    = 0;
    $character_name = "";
    $show_sheet     = "N";
    $view_password  = "";
    $hide_icon      = "N";

    $location      = "";
    $virtue        = "";
    $vice          = "";
    $splat1        = "";
    $splat1_groups = "";
    $splat2        = "";
    $splat2_groups = "";
    $subsplat      = "";
    $icon          = 'city.png';
    $sex           = "";
    $age           = "18+";
    $apparent_age  = "18+";
    $is_npc        = "N";
    $status        = "";

    $concept          = "";
    $description      = "";
    $url              = "";
    $equipment_public = "";
    $equipment_hidden = "";
    $public_effects   = "";
    $friends          = ""; // pack/coterie/whatever
    $helper           = ""; // Totem/Familiar/whatever
    $safe_place       = "";
    $exit_line        = "";
    $misc_powers      = "";

    $power_trait           = 1;
    $willpower_perm        = 0;
    $willpower_temp        = 0;
    $morality              = 7;
    $power_points          = 10;
    $maxPowerPoints        = 20;
    $average_power_points  = 0;
    $power_points_modifier = 0;
    $health                = 0;
    $size                  = 5;
    $defense               = 0;
    $initiative_mod        = 0;
    $speed                 = 0;
    $armor                 = "0/0";
    $wounds_bashing        = 0;
    $wounds_lethal         = 0;
    $wounds_aggravated     = 0;

    $history = "";
    $notes   = "";
    $goals   = "";

    $cell_id              = "";
    $login_note           = "";
    $current_experience   = 0;
    $total_experience     = 0;
    $bonus_received       = 0;
    $first_login          = "";
    $last_login           = "";
    $last_st_updated      = "";
    $when_last_st_updated = "";
    $gm_notes             = "";
    $sheet_updates        = "";
    $head_sanctioned      = "";
    $is_sanctioned        = "";
    $asst_sanctioned      = "";
    $view_status          = "";
    $bonus_attribute      = "";

    $attributes = InitializeAttributes();
    $skills     = InitializeSkills();

    // mods for ghouls
    if ($character_type == "Ghoul") {
        $morality     = 6;
        $power_points = GetPowerByName($attributes, "Stamina")->getPowerLevel();
    }

    $history_edit = "readonly";
    $goals_edit   = "readonly";
    $notes_edit   = "readonly";

    // test if stats were passed
    if ($stats != "") {
        // set sheet values based on passed stats
        $characterId    = $stats['id'];
        $character_name = $stats['character_name'];
        $show_sheet     = $stats['show_sheet'];
        $view_password  = $stats['view_password'];
        $hide_icon      = $stats['hide_icon'];

        $location     = $stats['city'];
        $virtue       = $stats['virtue'];
        $vice         = $stats['vice'];
        $splat1       = $stats['splat1'];
        $splat2       = $stats['splat2'];
        $subsplat     = $stats['subsplat'];
        $icon         = $stats['icon'];
        $sex          = $stats['sex'];
        $age          = $stats['age'];
        $apparent_age = $stats['apparent_age'];
        $is_npc       = $stats['is_npc'];
        $status       = $stats['status'];

        $concept          = $stats['concept'];
        $description      = $stats['description'];
        $url              = $stats['url'];
        $equipment_public = $stats['equipment_public'];
        $equipment_hidden = $stats['equipment_hidden'];
        $public_effects   = $stats['public_effects'];
        $friends          = $stats['friends']; // pack/coterie/whatever
        $helper           = $stats['helper']; // Totem/Familiar/whatever/Regnent
        $safe_place       = $stats['safe_place'];
        $exit_line        = $stats['exit_line'];
        $misc_powers      = $stats['misc_powers'];

        $power_trait           = $stats['power_stat'];
        $willpower_perm        = $stats['willpower_perm'];
        $willpower_temp        = $stats['willpower_temp'];
        $morality              = $stats['morality'];
        $power_points          = $stats['power_points'];
        $average_power_points  = $stats['average_power_points'];
        $power_points_modifier = $stats['power_points_modifier'];
        $health                = $stats['health'];
        $size                  = $stats['size'];
        $defense               = $stats['defense'];
        $initiative_mod        = $stats['initiative_mod'];
        $speed                 = $stats['speed'];
        $armor                 = $stats['armor'];
        $wounds_bashing        = $stats['wounds_bashing'];
        $wounds_lethal         = $stats['wounds_lethal'];
        $wounds_aggravated     = $stats['wounds_agg'];

        $history = $stats['history'];
        $notes   = $stats['character_notes'];
        $goals   = $stats['goals'];

        $current_experience   = $stats['current_experience'];
        $total_experience     = $stats['total_experience'];
        $bonus_received       = $stats['bonus_received'];
        $last_st_updated      = $stats['updated_by_username'];
        $when_last_st_updated = $stats['updated_on'];
        $gm_notes             = $stats['gm_notes'];
        $sheet_updates        = $stats['sheet_update'];
        $is_sanctioned        = $stats['is_sanctioned'];
        $asst_sanctioned      = $stats['asst_sanctioned'];
        $bonus_attribute      = $stats['bonus_attribute'];

        $attributes = getPowers($stats['id'], 'Attribute', NAMELEVEL, 0);
        $skills     = getPowers($stats['id'], 'Skill', NAMELEVEL, 0);
        if (!$edit_xp && ($bonus_attribute != '')) {
            foreach ($attributes as $attribute) {
                if ($attribute->getPowerName() == $bonus_attribute) {
                    $attribute->setPowerLevel($attribute->getPowerLevel() + 1);
                }
            }
        }
    }

    // set page colors based on type of character and supernatural XP
    switch ($character_type) {
        case 'Mortal':
            $table_class = "mortal_normal_text";
            break;
        case 'Sleepwalker':
            $table_class = "mage_normal_text";
            break;
        case 'Wolfblood':
            $table_class = "werewolf_normal_text";
            break;

        case 'Psychic':
            $table_class = "mortal_normal_text";
            break;

        case 'Thaumaturge':
            $table_class     = "mortal_normal_text";
            $splat1_groups   = array("Ceremonial Magician", "Hedge Witch", "Shaman", "Taoist Alchemist", "Vodoun", "Apostle");
            $supernatural_xp = 0;
            break;

        case 'Vampire':
            $table_class     = "vampire_normal_text";
            $splat1_groups   = array("Daeva", "Gangrel", "Mekhet", "Nosferatu", "Ventrue");
            $splat2_groups   = array("Carthian", "Circle of the Crone", "Invictus", "Lancea Sanctum", "Ordo Dracul", "Unaligned");
            $supernatural_xp = 20;
            break;

        case 'Werewolf':
            $table_class           = "werewolf_normal_text";
            $splat1_groups         = array("Rahu", "Cahalith", "Elodoth", "Ithaeur", "Irraka", "None");
            $splat2_groups         = array("Blood Talons", "Bone Shadows", "Hunters in Darkness", "Iron Masters", "Storm Lords", "Ghost Wolves", "Fire-Touched", "Ivory Claws", "Predator Kings");
            $supernatural_xp       = 16;
            $number_of_specialties = 4;
            break;

        case 'Mage':
            $table_class     = "mage_normal_text";
            $splat1_groups   = array("Acanthus", "Mastigos", "Moros", "Obrimos", "Thyrsus");
            $splat2_groups   = array("The Adamantine Arrows", "Free Council", "Guardians of the Veil", "The Mysterium", "The Silver Ladder", "Apostate", "Seer of the Throne", "Banisher");
            $supernatural_xp = 75;
            break;

        case 'Ghoul':
            $table_class     = "ghoul_normal_text";
            $supernatural_xp = 10;
            break;

        case 'Promethean':
            $table_class     = "promethean_normal_text";
            $splat1_groups   = array("Frankenstein", "Galatea", "Osiris", "Tammuz", "Uglan", "Pandoran", "Unfleshed", "Zeka");
            $splat2_groups   = array("Aurum", "Cuprum", "Ferrum", "Mercurius", "Stannum", "Centimani", "Aes", "Argentum", "Cobalus", "Plumbum");
            $supernatural_xp = 30;
            break;

        case 'Changeling':
            $table_class           = "changeling_normal_text";
            $splat1_groups         = array("Beast", "Darkling", "Elemental", "Fairest", "Ogre", "Wizened");
            $splat2_groups         = array("Spring", "Summer", "Autumn", "Winter", "Courtless");
            $supernatural_xp       = 40;
            $number_of_specialties = 4;
            break;

        case 'Hunter':
            $table_class   = "mortal_normal_text";
            $splat1_groups = array("Academic", "Artist", "Athlete", "Cop", "Criminal", "Detective", "Doctor", "Engineer", "Hacker", "Hit man", "Journalist", "Laborer", "Occultist", "Outdoorsman", "Professional", "Religious Leader", "Scientist", "Socialite", "Soldier", "Technician", "Vagrant");
            $splat2_groups = array("");
            break;

        case 'Geist':
            $table_class     = "geist_normal_text";
            $splat1_groups   = array("Advocate", "Bonepicker", "Celebrant", "Gatekeeper", "Mourner", "Necromancer", "Pilgrim", "Reaper");
            $splat2_groups   = array("Forgotten", "Prey", "Silent", "Stricken", "Torn");
            $supernatural_xp = 44;
            $maxPowerPoints  = 30;
            break;

        case 'Purified':
            $table_class     = "mortal_normal_text";
            $supernatural_xp = 52;
            $merit_xp        = 38;
            $skill_xp        = 141;
            break;

        case 'Possessed':
            $table_class     = "vampire_normal_text";
            $supernatural_xp = 30;
            break;

        default:
            $table_class = "mortal_normal_text";
            break;
    }

    $show_sheet_table = "";
    if ($edit_show_sheet) {
        $show_sheet_yes_check = ($show_sheet == 'Y') ? "checked" : "";
        $show_sheet_no_check  = ($show_sheet == 'N') ? "checked" : "";

        $hide_icon_yes_check = ($hide_icon == 'Y') ? "checked" : "";
        $hide_icon_no_check  = ($hide_icon == 'N') ? "checked" : "";

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
        if (!$characterId) {
            $submit_value = "Create Character";
        }

        $submit_button = <<<EOQ
<table class="character-sheet $table_class">
	<tr>
	  <td align="center">
	  	<input type="hidden" name="character_id" id="character_id" value="$characterId">
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
        $character_type_js     = "onChange=\"changeSheet(window.document.character_sheet.character_type.value)\";";
        $character_type_select = buildSelect($character_type, $character_types, $character_types, "character_type",
                                             $character_type_js);

        // location
        $locations = array("Savannah", "San Diego", "The City", "Side Game");
        $location  = buildSelect($location, $locations, $locations, "location");

        // sex
        $sexes = array("Male", "Female");
        $sex   = buildSelect($sex, $sexes, $sexes, "sex");

        // virtue & vice
        $virtues = array("Charity", "Faith", "Fortitude", "Hope", "Justice", "Prudence", "Temperance");
        $virtue  = buildSelect($virtue, $virtues, $virtues, "virtue");
        $vices   = array("Envy", "Gluttony", "Greed", "Lust", "Pride", "Sloth", "Wrath");
        $vice    = buildSelect($vice, $vices, $vices, "vice");

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
<input type="text" name="age" id="age" value="$age" size="3" maxlength="4">
EOQ;

        $apparent_age = <<<EOQ
<input type="text" name="apparent_age" id="apparent_age" value="$apparent_age" size="3" maxlength="4">
EOQ;
    }
    else {
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
        $status   = buildSelect($status, $statuses, $statuses, "status");
    }

    // concept
    if ($edit_concept) {
        $concept = <<<EOQ
<input type="text" name="concept" id="concept" value="$concept" size="50" maxlength="255">
EOQ;
    }

    // description
    if ($edit_description) {
        // icon
        $icon_query = "";
        if ($view_is_admin || $view_is_head) {
            $icon_query = "select * from icons where Admin_Viewable='Y' order by Icon_Name;";
        }
        else {
            if ($view_is_st) {
                $icon_query = "select * from icons where GM_Viewable='Y' order by Icon_Name;";
            }
            else {
                if ($view_is_asst) {
                    $icon_query = "select * from icons where Player_Viewable='Y' order by Icon_Name;";
                }
                else {
                    if ($icon_query == "") {
                        $icon_query = "select * from icons where Player_Viewable='Y' order by Icon_Name;";
                    }
                }
            }
        }
        $icon_result = mysql_query($icon_query) or die(mysql_error());

        $icon_ids   = "";
        $icon_names = "";

        while ($icon_detail = mysql_fetch_array($icon_result, MYSQL_ASSOC)) {
            $icon_ids[]   = $icon_detail['Icon_ID'];
            $icon_names[] = $icon_detail['Icon_Name'];
        }
        $icon = buildSelect($icon, $icon_ids, $icon_names, "icon");

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

    $power_trait_dots    = FormHelper::Dots("power_trait", $power_trait, $element_type['power_trait'], $character_type,
                                            10, $edit_perm_traits, false, $edit_xp);
    $willpower_perm_dots = FormHelper::Dots("willpower_perm", $willpower_perm, 0, $character_type, 10,
                                            $edit_perm_traits, false);
    $willpower_temp_dots = FormHelper::Dots("willpower_temp", $willpower_temp, 0, $character_type, 10,
        (($edit_temp_traits && $is_sanctioned == "") || ($view_is_asst || $view_is_st || $view_is_head || $view_is_admin)),
                                            false);
    $morality_dots       = FormHelper::Dots("morality", $morality, $element_type['morality'], $character_type, 10,
                                            $edit_perm_traits, false, $edit_xp);
    $power_points_dots   = FormHelper::Dots("power_points", $power_points, 0, $character_type, $maxPowerPoints, $edit_temp_traits,
                                            false);
    $health_dots         = FormHelper::Dots("health", $health, 0, $character_type, 15, $edit_perm_traits, false);

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
<input type="text" name="armor" id="armor" size="3" maxlength="4" value="$armor">
EOQ;

        $power_points_modifier = <<<EOQ
<input type="text" name="power_points_modifier" id="power_points_modifier" size="3" maxlength="4" value="$power_points_modifier">
EOQ;
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

    $powers = getPowers($characterId, 'Merit', NAMENOTE, 5);
    ob_start();
    ?>
    <table class="character-sheet <?php echo $table_class; ?>" id="merit_list">
        <tr>
            <th colspan="3">
                Merits
                <?php if ($edit_powers): ?>
                    <a href="#" onClick="addMerit();return false;">
                        <img src="/img/plus.png" title="Add Merit"/>
                    </a>
                <?php endif; ?>
            </th>
        </tr>
        <tr>
            <td class="header-row">
                Name
            </td>
            <td class="header-row">
                Note
            </td>
            <td class="header-row">
                Level
            </td>
        </tr>
        <?php foreach ($powers as $i => $power): ?>
            <?php $dots = FormHelper::Dots("merit${i}", $power->getPowerLevel(),
                                           $element_type['merit'], $character_type, $max_dots,
                                           $edit_powers, false, $edit_xp); ?>
            <tr>
                <td>
                    <?php if ($edit_powers): ?>
                        <label for="merit<?php echo $i; ?>_name"></label><input type="text"
                                                                                name="merit<?php echo $i; ?>_name"
                                                                                id="merit<?php echo $i; ?>_name"
                                                                                size="15"
                                                                                value="<?php echo $power->getPowerName(); ?>">
                    <?php else: ?>
                        <?php echo $power->getPowerName(); ?>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if ($edit_powers): ?>
                        <label for="merit<?php echo $i; ?>_note"></label><input type="text"
                                                                                name="merit<?php echo $i; ?>_note"
                                                                                id="merit<?php echo $i; ?>_note"
                                                                                size="20"
                                                                                value="<?php echo $power->getPowerNote(); ?>">
                    <?php else: ?>
                        <?php echo $power->getPowerNote(); ?>
                    <?php endif; ?>
                </td>
                <td>
                    <?php echo $dots; ?>
                    <input type="hidden" name="merit<?php echo $i; ?>_id" id="merit<?php echo $i; ?>_id"
                           value="<?php echo $power->getPowerID(); ?>">
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <?php
    $character_merit_list = ob_get_clean();

    $powers = getPowers($characterId, 'Flaw', NAMENOTE, 1);
    ob_start();
    ?>
    <table class="character-sheet <?php echo $table_class; ?>" id="flaw_list">
        <tr>
            <th colspan="1">
                Flaws
                <?php if ($edit_powers): ?>
                    <a href="#" onClick="addFlaw();return false;">
                        <img src="/img/plus.png" title="Add Flaw"/>
                    </a>
                <?php endif; ?>
            </th>
        </tr>
        <tr>
            <td class="header-row">
                Name
            </td>
        </tr>
        <?php foreach ($powers as $i => $power): ?>
            <tr>
                <td>
                    <?php if ($edit_powers): ?>
                        <label for="flaw<?php echo $i; ?>_name"></label><input type="text"
                                                                               name="flaw<?php echo $i; ?>_name"
                                                                               id="flaw<?php echo $i; ?>_name"
                                                                               size="15"
                                                                               value="<?php echo $power->getPowerName(); ?>">
                    <?php else: ?>
                        <?php echo $power->getPowerName(); ?>
                    <?php endif; ?>
                    <input type="hidden" name="flaw<?php echo $i; ?>_id" id="flaw<?php echo $i; ?>_id"
                           value="<?php echo $power->getPowerID(); ?>">
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <?php
    $character_flaw_list = ob_get_clean();

    $powers = getPowers($characterId, 'Misc', NAMENOTE, 1);
    ob_start();
    ?>
    <table class="character-sheet <?php echo $table_class; ?>" id="misc_list">
        <tr>
            <th colspan="3">
                Misc Traits
                <?php if ($edit_powers): ?>
                    <a href="#" onClick="addMiscTrait();return false;">
                        <img src="/img/plus.png" title="Add Misc Trait"/>
                    </a>
                <?php endif; ?>
            </th>
        </tr>
        <tr>
            <td class="header-row">
                Name
            </td>
            <td class="header-row">
                Note
            </td>
            <td class="header-row">
                Level
            </td>
        </tr>
        <?php foreach ($powers as $i => $power): ?>
            <tr>
                <td>
                    <?php if ($edit_powers): ?>
                        <label for="misc<?php echo $i; ?>_name"></label><input type="text"
                                                                               name="misc<?php echo $i; ?>_name"
                                                                               id="misc<?php echo $i; ?>_name"
                                                                               size="15"
                                                                               value="<?php echo $power->getPowerName(); ?>">
                    <?php else: ?>
                        <?php echo $power->getPowerName(); ?>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if ($edit_powers): ?>
                        <label for="misc<?php echo $i; ?>_note"></label><input type="text"
                                                                               name="misc<?php echo $i; ?>_note"
                                                                               id="misc<?php echo $i; ?>_note"
                                                                               size="15"
                                                                               value="<?php echo $power->getPowerNote(); ?>">
                    <?php else: ?>
                        <?php echo $power->getPowerNote(); ?>
                    <?php endif; ?>
                </td>
                <td>

                    <label for="misc<?php echo $i; ?>"></label>
                    <input type="text" name="misc<?php echo $i; ?>" id="misc<?php echo $i; ?>" size="3" maxlength="2"
                           value="<?php echo $power->getPowerLevel(); ?>"/>
                    <input type="hidden" name="misc<?php echo $i; ?>_id" id="misc<?php echo $i; ?>_id"
                           value="<?php echo $power->getPowerID(); ?>">
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <?php
    $characterMiscList = ob_get_clean();

    if ($edit_history) {
        $history_edit = "";
    }

    if ($edit_goals) {
        $goals_edit = "";
        $notes_edit = "";
    }

    $notes_box = "";
    if ($edit_cell) {
    }

    if ($edit_login_note) {
    }

    // create human readable version of status
    $temp_asst = ($asst_sanctioned == "") ? "X" : $asst_sanctioned;
    $temp_sanc = ($is_sanctioned == "") ? "X" : $is_sanctioned;

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
            $view_status = "New";
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
        }

        if ($view_is_st) {
            $sanc_yes_check = ($is_sanctioned == 'Y') ? "checked" : "";
            $sanc_no_check  = ($is_sanctioned == 'N') ? "checked" : "";
            $is_sanctioned  = <<<EOQ
Yes: <input type="radio" name="is_sanctioned" value="Y" $sanc_yes_check>
No: <input type="radio" name="is_sanctioned" value="N" $sanc_no_check>
EOQ;
        }

        if ($view_is_asst) {
            $asst_sanc_yes_check = ($asst_sanctioned == 'Y') ? "checked" : "";
            $asst_sanc_no_check  = ($asst_sanctioned == 'N') ? "checked" : "";
            $asst_sanctioned     = <<<EOQ
Yes: <input type="radio" name="asst_sanctioned" value="Y" $asst_sanc_yes_check>
No: <input type="radio" name="asst_sanctioned" value="N" $asst_sanc_no_check>
EOQ;
        }

        if ($edit_experience) {
            /*$current_experience = <<<EOQ
<input type="text" name="current_experience" value="$current_experience" size="5" maxlength="7">
EOQ;

            $total_experience = <<<EOQ
<input type="text" name="total_experience" value="$total_experience" size="5" maxlength="7">
EOQ;

            $bonus_received = <<<EOQ
<input type="text" name="bonus_received" value="$bonus_received" size="5" maxlength="7">
EOQ;*/
        }

        $monthlyBonusXPCap = 5;
        // this is bad, please don't do this me!e
        global $userdata;
        if(UserdataHelper::IsHead($userdata)) {
            $monthlyBonusXPCap = '99999';
        }


        ob_start();
?>
<table class="character-sheet $table_class">
    <tr>
        <th colspan="4">
            Storyteller Information
        </th>
    </tr>
    <tr>
        <td style="width:25%">
            Created On:
        </td>
        <td style="width:25%">
            <?php echo $first_login; ?>
        </td>
        <td style="width:25%">
            Last Login
        </td>
        <td style="width:25%">
            <?php echo $last_login; ?>
        </td>
    </tr>
    <tr>
        <td>
            Login Name:
        </td>
        <td>
            <?php echo $stats['username']; ?>
        </td>
        <td>
            Status:
        </td>
        <td>
            <?php echo $view_status; ?>
        </td>
    </tr>
    <tr>
        <td>
            Is Sanctioned
        </td>
        <td>
            <?php echo $is_sanctioned; ?>
        </td>
        <td>
            Last ST Updated
        </td>
        <td>
            <?php echo $last_st_updated; ?>
        </td>
    </tr>
    <tr>
        <td>
            Pre-Sanctioned
        </td>
        <td>
            <?php echo $asst_sanctioned; ?>
        </td>
        <td>
            When Last ST Updated
        </td>
        <td>
            <?php echo $when_last_st_updated; ?>
        </td>
    </tr>
    <tr>
        <td>
            Current Experience:
        </td>
        <td>
            <?php echo $current_experience; ?>
            <?php echo FormHelper::Hidden('current_experience', $current_experience); ?>
        </td>
        <td>
            Total Experience:
        </td>
        <td>
            <?php echo $total_experience; ?>
        </td>
    </tr>
    <tr>
        <td>
            Monthly Bonus XP Cap:
        </td>
        <td>
            <?php echo $monthlyBonusXPCap; ?>
            <?php echo FormHelper::Hidden('bonus_xp_cap', $monthlyBonusXPCap); ?>
        </td>
        <td>
            Bonus XP Received:
        </td>
        <td>
            <?php echo $bonus_received; ?>
            <?php echo FormHelper::Hidden('bonus_received', $bonus_received); ?>
        </td>
    </tr>
    <tr>
        <td>
            XP Spent
        </td>
        <td>
            <input type="text" name="xp_spent" id="xp-spent" value="0" />
        </td>
        <td>
            XP Gained
        </td>
        <td>
            <input type="text" name="xp_gained" id="xp-gained" value="0" />
        </td>
    </tr>
    <tr>
        <td>
            XP Adjustment Explanation
        </td>
        <td colspan="3">
            <input type="text" name="xp_note" id="xp-note" style="width: 98%;" value="" />
        </td>
    </tr>
    <tr>
        <td colspan="2" style="vertical-align: top;">
            Past ST Updates (archival)<br>
            <textarea name="sheet_updates" rows="6" cols="40" readonly><?php echo $sheet_updates; ?></textarea>
            <br>
<!--            Your Updates to add:<br>-->
<!--            <textarea name="new_sheet_updates" rows="6" cols="40"></textarea>-->
        </td>
        <td colspan="2">
            ST Notes: Personal notes and comments about the character. Not a mandatory field.<br>
            <textarea name="gm_notes" rows="6" cols="40" readonly><?php echo $gm_notes; ?></textarea>
            <br>
            Your Notes to add:<br>
            <textarea name="new_gm_notes" rows="6" cols="40"></textarea>
        </td>
    </tr>
</table>
<?php
        $st_notes_table = ob_get_clean();

    }
    else {
        ob_start();
        ?>
        <table class="character-sheet <?php echo $table_class; ?>">
            <tr>
                <th colspan="3">
                    Player Information
                </th>
            </tr>
            <tr>
                <td style="width:34%;">
                    Status:
                    <?php echo $view_status; ?>
                </td>
                <td style="width:33%;">
                    Experience Unspent:
                    <?php echo $current_experience; ?>
                </td>
                <td style="width:33%;">
                    Total Experience Earned:
                    <?php echo $total_experience; ?>
                </td>
            </tr>
            <tr>
                <td>
                    Last ST to View:
                    <?php echo $last_st_updated; ?>
                </td>
                <td>
                    Updated On:
                    <?php echo $when_last_st_updated; ?>
                </td>
                <td>
                </td>
            </tr>
            <tr>
                <td>
                    Monthly Bonus XP Cap: 5
                </td>
                <td>
                    Bonus Received: <?php echo $bonus_received; ?>
                </td>
                <td>
                </td>
            </tr>
        </table>
        <?php
        $st_notes_table = ob_get_clean();
    }

    $vitals_table      = "Vitals Not Done Yet<br>";
    $information_table = "Information Not Done Yet<br>";
    $traits_table      = "Traits Not Done Yet<br>";
    switch ($character_type) {
        case 'Mortal':
        case 'Wolfblooded':
        case 'Sleepwalker':
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
        ob_start();
        ?>
        <tr>
            <th colspan="6">
                <b>Experience Remaining</b>
                <?php echo $experience_help; ?>
            </th>
        </tr>
        <tr>
            <th colspan="6"
                style="background-color: transparent; border-top-left-radius:0; border-top-right-radius:0; color:#000; border:1px solid #898989;">
                <label for="attribute_xp">Attributes:</label>
                <input type="text" name="attribute_xp" id="attribute_xp" size="3" value="<?php echo $attribute_xp; ?>"
                       readonly>
                &nbsp;&nbsp;
                <label for="skill_xp">Skills:</label>
                <input type="text" name="skill_xp" id="skill_xp" size="3" value="<?php echo $skill_xp; ?>" readonly>
                &nbsp;&nbsp;
                <label for="merit_xp">Merits:</label>
                <input type="text" name="merit_xp" id="merit_xp" size="3" value="<?php echo $merit_xp; ?>" readonly>
                &nbsp;&nbsp;
                <label for="supernatural_xp">Supernatural:</label>
                <input type="text" name="supernatural_xp" id="supernatural_xp" size="3"
                       value="<?php echo $supernatural_xp; ?>" readonly>
                &nbsp;&nbsp;
                <label for="general_xp">General:</label>
                <input type="text" name="general_xp" id="general_xp" size="3" value="<?php echo $general_xp; ?>"
                       readonly>
            </th>
        </tr>
        <?php
        $xp_row = ob_get_clean();
    }

    $attributeIndex = 0;
    ob_start();
    ?>
    <table class="character-sheet <?php echo $table_class; ?>">
        <?php echo $xp_row; ?>
        <tr>
            <th colspan="6" style="text-align: center;">
                Attributes
                <span id="attribute_div"></span>
                <input type="hidden" name="bonus_attribute" id="bonus_attribute"
                       value="<?php echo $bonus_attribute; ?>">
            </th>
        </tr>
        <tr>
            <td>
                <b>Intelligence</b>
            </td>
            <td>
                <?php
                echo MakeBaseStatDots($attributes, 'Intelligence', 'attribute', $attributeIndex++,
                                      $element_type['attribute'], $character_type, $edit_attributes, $calculate_derived,
                                      $edit_xp, $max_dots);
                ?>
            </td>
            <td>
                <b>Strength</b>
            </td>
            <td>
                <?php
                echo MakeBaseStatDots($attributes, 'Strength', 'attribute', $attributeIndex++,
                                      $element_type['attribute'], $character_type, $edit_attributes, $calculate_derived,
                                      $edit_xp, $max_dots);
                ?>
            </td>
            <td>
                <b>Presence</b>
            </td>
            <td>
                <?php
                echo MakeBaseStatDots($attributes, 'Presence', 'attribute', $attributeIndex++,
                                      $element_type['attribute'], $character_type, $edit_attributes, $calculate_derived,
                                      $edit_xp, $max_dots);
                ?>
            </td>
        </tr>
        <tr>
            <td>
                <b>Wits</b>
            </td>
            <td>
                <?php
                echo MakeBaseStatDots($attributes, 'Wits', 'attribute', $attributeIndex++, $element_type['attribute'],
                                      $character_type, $edit_attributes, $calculate_derived, $edit_xp, $max_dots);
                ?>
            </td>
            <td>
                <b>Dexterity</b>
            </td>
            <td>
                <?php
                echo MakeBaseStatDots($attributes, 'Dexterity', 'attribute', $attributeIndex++,
                                      $element_type['attribute'], $character_type, $edit_attributes, $calculate_derived,
                                      $edit_xp, $max_dots);
                ?>
            </td>
            <td>
                <b>Manipulation</b>
            </td>
            <td>
                <?php
                echo MakeBaseStatDots($attributes, 'Manipulation', 'attribute', $attributeIndex++,
                                      $element_type['attribute'], $character_type, $edit_attributes, $calculate_derived,
                                      $edit_xp, $max_dots);
                ?>
            </td>
        </tr>
        <tr>
            <td>
                <b>Resolve</b>
            </td>
            <td>
                <?php
                echo MakeBaseStatDots($attributes, 'Resolve', 'attribute', $attributeIndex++,
                                      $element_type['attribute'], $character_type, $edit_attributes, $calculate_derived,
                                      $edit_xp, $max_dots);
                ?>
            </td>
            <td>
                <b>Stamina</b>
            </td>
            <td>
                <?php
                echo MakeBaseStatDots($attributes, 'Stamina', 'attribute', $attributeIndex++,
                                      $element_type['attribute'], $character_type, $edit_attributes, $calculate_derived,
                                      $edit_xp, $max_dots);
                ?>
            </td>
            <td>
                <b>Composure</b>
            </td>
            <td>
                <?php
                echo MakeBaseStatDots($attributes, 'Composure', 'attribute', $attributeIndex,
                                      $element_type['attribute'], $character_type, $edit_attributes, $calculate_derived,
                                      $edit_xp, $max_dots);
                ?>
            </td>
        </tr>
    </table>
    <?php
    $attribute_table = ob_get_clean();

    $powers       = getPowers($characterId, 'Specialty', NOTENAME, $number_of_specialties);
    $specialty_js = '';
    if ($edit_xp) {
        $specialty_js = " onChange=\"updateXP($element_type[skill])\" ";
    }
    if ($character_type == 'Mage') {
        $skill_list_proper = $skill_list_proper_mage;
    }
    ob_start();
    ?>
    <table class="character-sheet <?php echo $table_class; ?>" id="specialties_list">
        <tr>
            <th colspan="2">
                Specialties
                <?php if ($edit_powers): ?>
                    <a href="#" onClick="addSpecialty();return false;">
                        <img src="/img/plus.png" title="Add Specialty"/>
                    </a>
                <?php endif; ?>
            </th>
        </tr>
        <tr>
            <td style="width:50%;" class="header-row">
                Skill
            </td>
            <td style="width:50%;" class="header-row">
                Specialty
            </td>
        </tr>
        <?php foreach ($powers as $i => $power): ?>
            <tr>
                <td>
                    <?php if ($edit_skills): ?>
                        <?php echo buildSelect($power->getPowerNote(), $skill_list_proper, $skill_list_proper,
                                               "skill_spec${i}_selected", "class=\"$input_class\" $specialty_js"); ?>
                    <?php else: ?>
                        <?php echo $power->getPowerNote(); ?>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if ($edit_skills): ?>
                        <input type="text" name="skill_spec<?php echo $i; ?>"
                               id="skill_spec<?php echo $i; ?>" <?php echo $specialty_js; ?>
                               value="<?php echo $power->getPowerName(); ?>"/>
                    <?php else: ?>
                        <?php echo $power->getPowerName(); ?>
                    <?php endif; ?>
                    <input type="hidden" name="skill_spec<?php echo $i; ?>_id" id="skill_spec<?php echo $i; ?>_id"
                           value="<?php echo $power->getPowerID(); ?>">
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <?php
    $specialties_list = ob_get_clean();


    $skillIndex = 0;
    ob_start();
    ?>

    <div style="float:left;width:75%;">
    <table class="character-sheet <?php echo $table_class; ?>">
    <tr>
        <th colspan="2">
            Mental skills
        </th>
        <th colspan="2">
            Physical Skills
        </th>
        <th colspan="2">
            Social Skills
        </th>
    </tr>
    <tr style="vertical-align: top;">
        <td>
            Academics
        </td>
        <td>
            <?php
            echo MakeBaseStatDots($skills, 'Academics', 'skill', $skillIndex++, $element_type['skill'], $character_type,
                                  $edit_skills, $calculate_derived, $edit_xp, $max_dots);
            ?>
        </td>
        <td>
            Athletics
        </td>
        <td>
            <?php
            echo MakeBaseStatDots($skills, 'Athletics', 'skill', $skillIndex++, $element_type['skill'], $character_type,
                                  $edit_skills, $calculate_derived, $edit_xp, $max_dots);
            ?>
        </td>
        <td>
            Animal Ken
        </td>
        <td>
            <?php
            echo MakeBaseStatDots($skills, 'Animal Ken', 'skill', $skillIndex++, $element_type['skill'],
                                  $character_type, $edit_skills, $calculate_derived, $edit_xp, $max_dots);
            ?>
        </td>
    </tr>
    <tr>
        <td>
            Computer
        </td>
        <td>
            <?php
            echo MakeBaseStatDots($skills, 'Computer', 'skill', $skillIndex++, $element_type['skill'], $character_type,
                                  $edit_skills, $calculate_derived, $edit_xp, $max_dots);
            ?>
        </td>
        <td>
            Brawl
        </td>
        <td>
            <?php
            echo MakeBaseStatDots($skills, 'Brawl', 'skill', $skillIndex++, $element_type['skill'], $character_type,
                                  $edit_skills, $calculate_derived, $edit_xp, $max_dots);
            ?>
        </td>
        <td>
            Empathy
        </td>
        <td>
            <?php
            echo MakeBaseStatDots($skills, 'Empathy', 'skill', $skillIndex++, $element_type['skill'], $character_type,
                                  $edit_skills, $calculate_derived, $edit_xp, $max_dots);
            ?>
        </td>
    </tr>
    <tr>
        <td>
            Crafts
        </td>
        <td>
            <?php
            echo MakeBaseStatDots($skills, 'Crafts', 'skill', $skillIndex++, $element_type['skill'], $character_type,
                                  $edit_skills, $calculate_derived, $edit_xp, $max_dots);
            ?>
        </td>
        <td>
            Drive
        </td>
        <td>
            <?php
            echo MakeBaseStatDots($skills, 'Drive', 'skill', $skillIndex++, $element_type['skill'], $character_type,
                                  $edit_skills, $calculate_derived, $edit_xp, $max_dots);
            ?>
        </td>
        <td>
            Expression
        </td>
        <td>
            <?php
            echo MakeBaseStatDots($skills, 'Expression', 'skill', $skillIndex++, $element_type['skill'],
                                  $character_type, $edit_skills, $calculate_derived, $edit_xp, $max_dots);
            ?>
        </td>
    </tr>
    <tr>
        <td>
            Investigation
        </td>
        <td>
            <?php
            echo MakeBaseStatDots($skills, 'Investigation', 'skill', $skillIndex++, $element_type['skill'],
                                  $character_type, $edit_skills, $calculate_derived, $edit_xp, $max_dots);
            ?>
        </td>
        <td>
            Firearms
        </td>
        <td>
            <?php
            echo MakeBaseStatDots($skills, 'Firearms', 'skill', $skillIndex++, $element_type['skill'], $character_type,
                                  $edit_skills, $calculate_derived, $edit_xp, $max_dots);
            ?>
        </td>
        <td>
            Intimidation
        </td>
        <td>
            <?php
            echo MakeBaseStatDots($skills, 'Intimidation', 'skill', $skillIndex++, $element_type['skill'],
                                  $character_type, $edit_skills, $calculate_derived, $edit_xp, $max_dots);
            ?>
        </td>
    </tr>
    <tr>
        <td>
            Medicine
        </td>
        <td>
            <?php
            echo MakeBaseStatDots($skills, 'Medicine', 'skill', $skillIndex++, $element_type['skill'], $character_type,
                                  $edit_skills, $calculate_derived, $edit_xp, $max_dots);
            ?>
        </td>
        <td>
            Larceny
        </td>
        <td>
            <?php
            echo MakeBaseStatDots($skills, 'Larceny', 'skill', $skillIndex++, $element_type['skill'], $character_type,
                                  $edit_skills, $calculate_derived, $edit_xp, $max_dots);
            ?>
        </td>
        <td>
            Persuasion
        </td>
        <td>
            <?php
            echo MakeBaseStatDots($skills, 'Persuasion', 'skill', $skillIndex++, $element_type['skill'],
                                  $character_type, $edit_skills, $calculate_derived, $edit_xp, $max_dots);
            ?>
        </td>
    </tr>
    <tr>
        <td>
            Occult
        </td>
        <td>
            <?php
            echo MakeBaseStatDots($skills, 'Occult', 'skill', $skillIndex++, $element_type['skill'], $character_type,
                                  $edit_skills, $calculate_derived, $edit_xp, $max_dots);
            ?>
        </td>
        <td>
            Stealth
        </td>
        <td>
            <?php
            echo MakeBaseStatDots($skills, 'Stealth', 'skill', $skillIndex++, $element_type['skill'], $character_type,
                                  $edit_skills, $calculate_derived, $edit_xp, $max_dots);
            ?>
        </td>
        <td>
            Socialize
        </td>
        <td>
            <?php
            echo MakeBaseStatDots($skills, 'Socialize', 'skill', $skillIndex++, $element_type['skill'], $character_type,
                                  $edit_skills, $calculate_derived, $edit_xp, $max_dots);
            ?>
        </td>
    </tr>
    <tr>
        <td>
            Politics
        </td>
        <td>
            <?php
            echo MakeBaseStatDots($skills, 'Politics', 'skill', $skillIndex++, $element_type['skill'], $character_type,
                                  $edit_skills, $calculate_derived, $edit_xp, $max_dots);
            ?>
        </td>
        <td>
            Survival
        </td>
        <td>
            <?php
            echo MakeBaseStatDots($skills, 'Survival', 'skill', $skillIndex++, $element_type['skill'], $character_type,
                                  $edit_skills, $calculate_derived, $edit_xp, $max_dots);
            ?>
        </td>
        <td>
            Streetwise
        </td>
        <td>
            <?php
            echo MakeBaseStatDots($skills, 'Streetwise', 'skill', $skillIndex++, $element_type['skill'],
                                  $character_type, $edit_skills, $calculate_derived, $edit_xp, $max_dots);
            ?>
        </td>
    </tr>
    <tr>
        <td>
            Science
        </td>
        <td>
            <?php
            echo MakeBaseStatDots($skills, 'Science', 'skill', $skillIndex++, $element_type['skill'], $character_type,
                                  $edit_skills, $calculate_derived, $edit_xp, $max_dots);
            ?>
        </td>
        <td>
            Weaponry
        </td>
        <td>
            <?php
            echo MakeBaseStatDots($skills, 'Weaponry', 'skill', $skillIndex++, $element_type['skill'], $character_type,
                                  $edit_skills, $calculate_derived, $edit_xp, $max_dots);
            ?>
        </td>
        <td>
            Subterfuge
        </td>
        <td>
            <?php
            echo MakeBaseStatDots($skills, 'Subterfuge', 'skill', $skillIndex, $element_type['skill'], $character_type,
                                  $edit_skills, $calculate_derived, $edit_xp, $max_dots);
            ?>
        </td>
    </tr>
    </table>
    </div>
    <div style="width:25%;float:left;">
        <?php echo $specialties_list; ?>
    </div>
    <?php
    $skill_table = ob_get_clean();

    ob_start();
    ?>
    <table class="character-sheet <?php echo $table_class; ?>">
        <tr>
            <th colspan="2" style="text-align: center;">
                History
            </th>
        </tr>
        <tr>
            <td style="width: 40%;">
                <label for="goals">Goals &amp; Beliefs</label>
                <textarea rows="8" name="goals" id="goals"
                          style="width:100%" <?php echo $goals_edit; ?>><?php echo $goals; ?></textarea>
            </td>
            <td style="width: 60%;">
                <label for="misc_powers">Misc Powers/Abilities</label>
                <textarea rows="8" name="misc_powers" id="misc_powers"
                          style="width:100%" <?php echo $edit_powers; ?>><?php echo $misc_powers; ?></textarea>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <label for="history">History</label>
                <textarea rows="8" name="history" id="history"
                          style="width:100%" <?php echo $history_edit; ?>><?php echo $history; ?></textarea>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <label for="notes">Notes</label>
                <textarea rows="8" name="notes" id="notes"
                          style="width:100%" <?php echo $notes_edit; ?>><?php echo $notes; ?></textarea>
            </td>
        </tr>
    </table>
    <?php
    $history_table = ob_get_clean();

    // put sheet pieces together
    ob_start();
    ?>
    <!--<div id="character-tabs">
        <ul>
            <li><a href="#character_table">Sheet</a></li>
            <li><a href="#profile">Profile</a></li>
            <li><a href="#equipment">Equipment</a></li>
        </ul>
        <div id="character_table">-->
            <?php echo $show_sheet_table; ?>
            <?php echo $vitals_table; ?>
            <?php echo $information_table; ?>
            <?php echo $attribute_table; ?>
            <?php echo $skill_table; ?>
            <?php echo $traits_table; ?>
            <?php echo $history_table; ?>
            <?php echo $st_notes_table; ?>
        <!--</div>
        <div id="profile">
            <?php echo FormHelper::Textarea('public_profile', '', array('class' => 'profile')); ?>
        </div>
        <div id="equipment">
            Equipment list here!
        </div>
    </div>-->
    <div>
        <?php echo $submit_button; ?>
    </div>
    <script type="text/javascript" src="/js/tinymce/tinymce.min.js"></script>
    <script type="text/javascript">
        tinymce.init({
            selector : "textarea.profile",
            theme: 'modern',
            menubar  : false,
            height   : 200,
            plugins  : [
                "advlist autolink lists link image charmap print preview anchor",
                "searchreplace wordcount visualblocks code fullscreen",
                "insertdatetime media table contextmenu paste textcolor template"
            ],
            toolbar1  : "undo redo | bold italic | styleselect | forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent",
            toolbar2  : "template",
            templates: [
                {
                    title      : 'Test Template',
                    content        : 'Test Content here!',
                    description: 'A Test Template'
                }
            ]
        });
        $("#character-tabs").tabs();
    </script>
    <?php
    return ob_get_clean();
}

function MakeBaseStatDots($powers, $powerName, $powerType, $position, $element_type, $character_type, $edit, $calculate_derived, $edit_xp, $max_dots)
{
    $power  = GetPowerByName($powers, $powerName);
    $output = FormHelper::Hidden($powerType . $position . '_id', $power->getPowerID());
    $output .= FormHelper::Hidden($powerType . $position . '_name', $power->getPowerName());
    $output .= FormHelper::Dots($powerType . $position, $power->getPowerLevel(), $element_type, $character_type,
                                $max_dots, $edit, $calculate_derived, $edit_xp);

    return $output;
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
        switch ($sort_order) {
            case NAMELEVEL:
                $order_by = "power_name, power_level";
                break;
            case NOTELEVEL:
                $order_by = "power_note, power_level";
                break;
            case NOTENAME:
                $order_by = "power_note, power_name";
                break;
            case NAMENOTE:
                $order_by = "power_name, power_note";
                break;
            default:
                $order_by = "power_name, power_level";
                break;
        }


        $query = "select * from character_powers where character_id = $character_id and power_type = '$power_type' Order by $order_by;";
        $result = mysql_query($query) or die(mysql_error());

        while ($detail = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $power = new Power();

            $power->setPowerName($detail['power_name']);
            $power->setPowerNote($detail['power_note']);
            $power->setPowerLevel($detail['power_level']);
            $power->setPowerID($detail['id']);

            $power_list[] = $power;
        }
    }
    else {
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
    $renown->setPowerLevel(0);
    $renown->setPowerID(0);

    $renown_list["rituals"] = $renown;

    if ($character_id) {
        $query = "select * from character_powers where character_id = $character_id and power_type = 'Renown' Order by power_name;";
        $result = mysql_query($query) or die(mysql_error());

        while ($detail = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $renown = new Power();
            $renown->setPowerName($detail["power_name"]);
            $renown->setPowerLevel($detail["power_level"]);
            $renown->setPowerID($detail["id"]);
            $renown_name = strtolower($detail["power_name"]);

            $renown_list[$renown_name] = $renown;
        }

        $query = "select * from character_powers where character_id = $character_id and power_type = 'Rituals' Order by power_name;";
        $result = mysql_query($query) or die(mysql_error());

        while ($detail = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $renown = new Power();
            $renown->setPowerName($detail["power_name"]);
            $renown->setPowerLevel($detail["power_level"]);
            $renown->setPowerID($detail["id"]);

            $renown_list["rituals"] = $renown;
        }
    }

    return $renown_list;
}

function InitializeAttributes()
{
    $attribute_list = array("strength", "dexterity", "stamina", "presence", "manipulation", "composure", "intelligence", "wits", "resolve");
    $attributes     = array();
    foreach ($attribute_list as $attribute) {
        $power = new Power();
        $power->setPowerLevel(1);
        $power->setPowerName(ucfirst($attribute));
        $attributes[] = $power;
    }

    return $attributes;
}

function InitializeSkills()
{
    $skill_list = array("academics", "computer", "crafts", "investigation", "medicine", "occult", "politics", "science", "athletics", "brawl", "drive", "firearms", "larceny", "stealth", "survival", "weaponry", "animal ken", "empathy", "expression", "intimidation", "persuasion", "socialize", "streetwise", "subterfuge");
    $skills     = array();
    foreach ($skill_list as $skill) {
        $power = new Power();
        $power->setPowerLevel(0);
        $power->setPowerName(ucwords(str_replace('_', ' ', $skill)));
        $skills[] = $power;
    }

    return $skills;
}


/**
 * @param Power[] $powers
 * @param string $name
 * @return \Power
 */
function GetPowerByName($powers, $name)
{
    foreach ($powers as $power) {
        if (strtolower($power->getPowerName()) == strtolower($name)) {
            return $power;
        }
    }

    return null;
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