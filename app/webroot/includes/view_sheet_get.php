<?php
/* @var array $userdata */
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Content-Type: application/html");

$character_type = (isset($_GET['character_type'])) ? ($_GET['character_type']) : "Mortal";
$character_id = (isset($_GET['character_id'])) ? $_GET['character_id'] + 0 : 0;
$type = (isset($_GET['type'])) ? $_GET['type'] : "";
$stats = '';

// test if character is in the database
if ($character_id) {
    switch ($type) {
        case 'view_own':
            $character_query = <<<EOQ
SELECT 
	wod.*,
	gm_login.Name as ST_Name
FROM 
	wod_characters AS wod 
	INNER JOIN login_character_index AS lci ON wod.character_id = lci.character_id
	LEFT JOIN login AS gm_login on wod.last_st_updated = gm_login.id
WHERE lci.login_id = $userdata[user_id]
   AND wod.character_id = $character_id;
EOQ;

            $stats = ExecuteQueryItem($character_query);

            if (!$stats) {
                die();
            }

            break;

        case 'st_view':
            $character_query = <<<EOQ
SELECT login.*, wod_characters.*, gm_login.Name as ST_Name, asst_login.Name as Asst_Name
FROM ((wod_characters INNER JOIN login ON wod_characters.primary_login_id = login.id) LEFT JOIN login AS gm_login on wod_characters.last_st_updated = gm_login.id) LEFT JOIN login AS asst_login ON wod_characters.last_asst_st_updated = asst_login.id
WHERE character_id=$character_id;
EOQ;

            $stats = ExecuteQueryItem($character_query);

            if (!$stats) {
                die();
            }
            break;

        default:
            break;
    }
}

switch ($type) {
    case 'st_view':
        $character_type = (isset($_GET['character_type'])) ? ($_GET['character_type']) : $stats['Character_Type'];
        // determine what sort of view to use
        $viewed_sheet = false;

        if ($userdata['is_admin']) {
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
            $edit_xp = false;
            if ($stats['Is_Sanctioned'] == '') {
                $edit_xp = true;
            }
        }

        if (!$viewed_sheet && $userdata['is_head']) {
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
            $edit_xp = false;
            if ($stats['Is_Sanctioned'] == '') {
                $edit_xp = true;
            }
        }

        if (!$viewed_sheet && $userdata['is_gm']) {
            $viewed_sheet = true;
            if (($stats['is_sanctioned'] == 'Y') && ($stats['is_npc'] == 'Y')) {
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
                $view_is_st = false;
                $view_is_head = false;
                $view_is_admin = false;
                $may_edit = true;
                $edit_cell = false;
                $calculate_derived = false;
                $edit_xp = false;
            } else {
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
                $edit_xp = false;
                if ($stats['Is_Sanctioned'] == '') {
                    $edit_xp = true;
                }
            }
        }

        if (!$viewed_sheet && $userdata['is_asst']) {
            $viewed_sheet = true;
            $may_full_view = (($stats['Is_NPC'] == 'N') && (($stats['Is_Sanctioned'] == '') || ($stats['Cell_ID'] == $userdata['cell_id']) || ($stats['Cell_ID'] == 'No Preference') || ($stats['Cell_ID'] == '')));

            if ($may_full_view) {
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
                $edit_xp = false;
                if ($stats['Is_Sanctioned'] == '') {
                    $edit_xp = true;
                }
            } else {
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
                $edit_cell = false;
                $calculate_derived = false;
                $edit_xp = false;
            }
        }
        break;

    case 'view_own':
        $character_type = (isset($_GET['character_type'])) ? ($_GET['character_type']) : $stats['Character_Type'];
        if (($stats['Asst_Sanctioned'] == 'Y') || ($stats['Is_Sanctioned'] == 'Y') || ($stats['Head_Sanctioned'] == 'Y')) {
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
            $edit_xp = false;
        } else {
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
            $edit_experience = true;
            $show_st_notes = false;
            $view_is_asst = false;
            $view_is_st = false;
            $view_is_head = false;
            $view_is_admin = false;
            $may_edit = true;
            $edit_cell = true;
            $calculate_derived = true;
            $edit_xp = true;
        }
        break;

    case 'new':
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
        $edit_experience = true;
        $show_st_notes = false;
        $view_is_asst = false;
        $view_is_st = false;
        $view_is_head = false;
        $view_is_admin = false;
        $may_edit = false;
        if ($userdata['user_id'] != 1) {
            $may_edit = true;
        }
        $edit_cell = true;
        $calculate_derived = true;
        $edit_xp = true;
        break;

    default:
        $edit_show_sheet = false;
        $edit_name = false;
        $edit_vitals = false;
        $edit_is_npc = false;
        $edit_is_dead = false;
        $edit_location = false;
        $edit_concept = false;
        $edit_description = false;
        $edit_url = false;
        $edit_equipment = false;
        $edit_public_effects = false;
        $edit_group = false;
        $edit_exit_line = false;
        $edit_is_npc = false;
        $edit_attributes = false;
        $edit_skills = false;
        $edit_perm_traits = false;
        $edit_temp_traits = false;
        $edit_powers = false;
        $edit_history = false;
        $edit_goals = false;
        $edit_login_note = false;
        $edit_experience = false;
        $show_st_notes = false;
        $view_is_asst = false;
        $view_is_st = false;
        $view_is_head = false;
        $view_is_admin = false;
        $may_edit = false;
        $edit_cell = false;
        $calculate_derived = false;
        $edit_xp = false;
        break;
}

$character_sheet .= buildWoDSheetXP($stats, $character_type, $edit_show_sheet, $edit_name, $edit_vitals, $edit_is_npc, $edit_is_dead, $edit_location, $edit_concept, $edit_description, $edit_url, $edit_equipment, $edit_public_effects, $edit_group, $edit_exit_line, $edit_is_npc, $edit_attributes, $edit_skills, $edit_perm_traits, $edit_temp_traits, $edit_powers, $edit_history, $edit_goals, $edit_login_note, $edit_experience, $show_st_notes, $view_is_asst, $view_is_st, $view_is_head, $view_is_admin, $may_edit, $edit_cell, $calculate_derived, $edit_xp);

echo $character_sheet;
die();