<?php
use classes\log\CharacterLog;
use classes\log\data\ActionType;

/* @var array $userdata */

$page_title = "ST View";
$page_content = "";
$js = "";
$lookup_form = "";
$npc_login_link = "";
$sheet = "";
$logins = "";

// test if updating
if (isset($_POST['submit'])) {
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
    }

    if (!$viewed_sheet && $userdata['is_gm']) {
        $viewed_sheet = true;
        if (($character_detail['is_npc'] == 'Y')) {
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

    if (!$viewed_sheet && $userdata['is_asst']) {
        $viewed_sheet = true;
        $may_full_view = (($character_detail['Is_NPC'] == 'N') && (($character_detail['Is_Sanctioned'] == '') || ($character_detail['Cell_ID'] == $userdata['cell_id']) || ($character_detail['Cell_ID'] == 'No Preference') || ($character_detail['Cell_ID'] == '')));

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
    if ($viewed_sheet) {
        CharacterLog::LogAction($_POST['character_id'], ActionType::UpdateCharacter, 'ST Updated Sheet', $userdata['user_id']);
        updateWoDSheetXP($_POST, $edit_show_sheet, $edit_name, $edit_vitals, $edit_is_npc, $edit_is_dead, $edit_location, $edit_concept, $edit_description, $edit_url, $edit_equipment, $edit_public_effects, $edit_group, $edit_exit_line, $edit_attributes, $edit_skills, $edit_perm_traits, $edit_temp_traits, $edit_powers, $edit_history, $edit_goals, $edit_login_note, $edit_experience, $show_st_notes, $view_is_asst, $view_is_st, $view_is_head, $view_is_admin, $may_edit, $edit_cell);
    }
}

$edit_xp = "false";
$character_id = 0;

// look up character info
// test if viewing
$view_character_id = (isset($_GET['view_character_id'])) ? $_GET['view_character_id'] + 0 : 0;
$view_character_name = (isset($_POST['view_character_name'])) ? mysql_real_escape_string(htmlspecialchars($_POST['view_character_name'])) : "";

if ($view_character_id || $view_character_name) {
    // get character information
    if ($view_character_id) {
        $character_query = <<<EOQ
SELECT login.Name, Character_ID , Is_Sanctioned, Is_NPC, Head_Sanctioned, Character_Name, Cell_ID
FROM wod_characters INNER JOIN login On wod_characters.primary_login_id = login.id
WHERE character_id=$view_character_id;
EOQ;
    }
    if ($view_character_name) {
        $character_query = <<<EOQ
SELECT login.Name, Character_ID , Is_Sanctioned, Is_NPC, Head_Sanctioned, Character_Name, Cell_ID
FROM wod_characters INNER JOIN login On wod_characters.primary_login_id = login.id
WHERE character_name='$view_character_name';
EOQ;
    }

    $character_result = mysql_query($character_query) or die(mysql_error());

    if (mysql_num_rows($character_result)) {
        // found character
        $character_detail = mysql_fetch_array($character_result, MYSQL_ASSOC);
        CharacterLog::LogAction($character_detail['Character_ID'], ActionType::ViewCharacter, 'ST View', $userdata['user_id']);

        $viewSheet = true;
        if (!$userdata['is_admin']
            && (($userdata['is_asst'] && ($userdata['cell_id'] != $character_detail['Cell_ID']))
                || ($userdata['is_gm'] && ($userdata['cell_id'] != $character_detail['Cell_ID'])))
        ) {
            $viewSheet = false;
        }

        if ($viewSheet) {
            $character_id = $character_detail['Character_ID'];
            if ($userdata['is_asst']) {
                if (($character_detail['Is_NPC'] == 'N') && ($character_detail['Is_Sanctioned'] == '')) {
                    $edit_xp = "true";
                }
            }

            if ($userdata['is_gm']) {
                if (($character_detail['Is_Sanctioned'] == '') && ($character_detail['Is_NPC'] == 'N')) {
                    $edit_xp = "true";
                }
            }

            if ($userdata['is_head'] || $userdata['is_admin']) {
                if ($character_detail['Is_Sanctioned'] == '') {
                    $edit_xp = "true";
                }
            }

            $page_content = <<<EOQ
<a href="/bluebook.php?action=st_list&character_id=$character_detail[Character_ID]">View Bluebook</a><br />
<a href="/character.php?action=log&character_id=$character_detail[Character_ID]">View Character Log</a><br />
<form name="character_sheet" id="character_sheet" method="post" action="$_SERVER[PHP_SELF]?action=st_view_xp">
<a href="storyteller_index.php?action=profile_lookup&profile_name=$character_detail[Name]">View all of $character_detail[Name]'s characters</a>
<div align="center" name="charSheet" id="charSheet">Loading Character Sheet...
</div>
</form>
EOQ;

            //echo "$character_detail[Is_NPC] : $character_detail[Head_Sanctioned]<br>";
            if (($character_detail['Is_NPC'] == 'Y') && ($character_detail['Head_Sanctioned'] == 'Y')) {
                $npc_login_link = <<<EOQ
<div align="center">
<a href="character_interface.php?character_id=$character_detail[Character_ID]&log_npc=y" target="_blank">Log in as $character_detail[Character_Name]</a><br>
<a href="notes.php?action=character&character_id=$character_detail[Character_ID]&log_npc=y" target="_blank">View NPC Notes</a></div>
<br>
EOQ;

            }
        } else {
            $page_content = "You may not view that sheet.";
        }
    }
}


$java_script .= <<<EOQ
<script src="js/xmlHTTP.js" type="text/javascript"></script>
<script src="js/create_character_xp.js" type="text/javascript"></script>
<script>
    $(function() {
       loadCharacterSTView($character_id, $edit_xp);
    });
</script>
EOQ;

// build page
$lookup_form = <<<EOQ
<form method="post" action="$_SERVER[PHP_SELF]?action=st_view_xp">
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


$page_content = <<<EOQ
<div align="left">
<span class="highlight">Quick Links</span>: 
<a href="storyteller_index.php">Storyteller Homepage</a> -
<a href="index.php">WantonWicked Homepage</a>
$lookup_form
</div>
$npc_login_link
$page_content
$logins
EOQ;
