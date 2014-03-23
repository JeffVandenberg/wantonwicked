<?php
use classes\character\helper\CharacterHelper;
use classes\core\repository\Database;

function updateWoDSheetXP($stats, $edit_show_sheet = false, $edit_name = false,
                          $edit_vitals = false, $edit_is_npc = false, $edit_is_dead = false,
    /** @noinspection PhpUnusedParameterInspection */
                          $edit_location = false, $edit_concept = false, $edit_description = false,
                          $edit_url = false, $edit_equipment = false, $edit_public_effects = false,
                          $edit_group = false, $edit_exit_line = false, $edit_attributes = false,
                          $edit_skills = false, $edit_perm_traits = false, $edit_temp_traits = false,
                          $edit_powers = false, $edit_history = false, $edit_goals = false,
    /** @noinspection PhpUnusedParameterInspection */
                          $edit_login_note = false, $edit_experience = false, $show_st_notes_table = false,
                          $view_is_asst = false, $view_is_st = false, $view_is_head = false,
    /** @noinspection PhpUnusedParameterInspection */
                          $view_is_admin = false, $may_edit = false, $edit_cell = false)
{
    $error = '';
    global $userdata;

    $db = new Database();

    // start to process
    // attempt to process character
    $str_to_find    = array("'", "\"");
    $str_to_replace = array("-", "-");
    $character_name = mysql_real_escape_string(
        htmlspecialchars(
            str_replace(
                $str_to_find,
                $str_to_replace,
                stripslashes($stats['character_name']))
        )
    );
    $character_id   = (int)$stats['character_id'];

    // verify that character name isn't in use already
    $name_check_query = "select id from characters where character_name='$character_name' and id != $character_id;";
    $name_check_result = $db->Query($name_check_query)->Single();
    if (($name_check_result !== false) && ($edit_name)) {
        // warn that there is already a character with that name
        $error .= <<<EOQ
There is already a character with that name, please give the character a different name.
EOQ;
    }
    else {
        // set values
        $now            = date('Y-m-d h:i:s');
        $show_sheet     = $stats['show_sheet'];
        $view_password  = mysql_real_escape_string($stats['view_password']);
        $character_type = mysql_real_escape_string($stats['character_type']);
        $city           = mysql_real_escape_string($stats['location']);
        $age            = (int)$stats['age'];
        $sex            = mysql_real_escape_string($stats['sex']);
        $apparent_age   = (int)$stats['apparent_age'];
        $concept        = mysql_real_escape_string(htmlspecialchars($stats['concept']));
        $description    = mysql_real_escape_string(htmlspecialchars($stats['description']));
        if ($description == "") {
            $description = "I need a description";
        }

        $url        = mysql_real_escape_string(htmlspecialchars($stats['url']));
        $safe_place = mysql_real_escape_string(htmlspecialchars($stats['safe_place']));
        $friends    = isset($stats['friends']) ? mysql_real_escape_string(htmlspecialchars($stats['friends'])) : "";
        $helper     = isset($stats['helper']) ? mysql_real_escape_string(htmlspecialchars($stats['helper'])) : "";
        $exit_line  = mysql_real_escape_string(htmlspecialchars($stats['exit_line']));
        if ($exit_line == "") {
            $exit_line = "I need an exit line";
        }

        $icon     = mysql_real_escape_string($stats['icon']);
        $is_npc   = isset($stats['is_npc']) ? "Y" : "N";
        $virtue   = mysql_real_escape_string($stats['virtue']);
        $vice     = mysql_real_escape_string($stats['vice']);
        $splat1   = mysql_real_escape_string($stats['splat1']);
        $splat2   = mysql_real_escape_string($stats['splat2']);
        $subsplat = mysql_real_escape_string(htmlspecialchars($stats['subsplat']));

        $size                  = (int)$stats['size'];
        $speed                 = (int)$stats['speed'];
        $initiative_mod        = (int)$stats['initiative_mod'];
        $defense               = (int)$stats['defense'];
        $armor                 = (int)$stats['armor'];
        $health                = (int)$stats['health'];
        $wounds_agg            = (int)$stats['wounds_agg'];
        $wounds_lethal         = (int)$stats['wounds_lethal'];
        $wounds_bashing        = (int)$stats['wounds_bashing'];
        $willpower_perm        = (int)$stats['willpower_perm'];
        $willpower_temp        = (int)$stats['willpower_temp'];
        $power_stat            = isset($stats['power_trait']) ? (int)$stats['power_trait'] : 0;
        $power_points          = isset($stats['power_points']) ? (int)$stats['power_points'] : 0;
        $power_points_modifier = (int)$stats['power_points_modifier'];
        $morality              = (int)$stats['morality'];
        $equipment_public      = mysql_real_escape_string(htmlspecialchars($stats['equipment_public']));
        $equipment_hidden      = mysql_real_escape_string(htmlspecialchars($stats['equipment_hidden']));
        $public_effects        = mysql_real_escape_string(htmlspecialchars($stats['public_effects']));
        $history               = mysql_real_escape_string(htmlspecialchars($stats['history']));
        $character_notes       = mysql_real_escape_string(htmlspecialchars($stats['notes']));
        $goals                 = mysql_real_escape_string(htmlspecialchars($stats['goals']));
        $hide_icon             = mysql_real_escape_string($stats['hide_icon']);
        $status                = mysql_real_escape_string($stats['status']);
        $bonus_attribute       = mysql_real_escape_string($stats['bonus_attribute']);
        $current_experience    = (int)$stats['current_experience'];
        $total_experience      = (int)$stats['total_experience'];
        $bonus_received        = (int)$stats['bonus_received'];
        $misc_powers           = mysql_real_escape_string(htmlspecialchars($stats['misc_powers']));

        // check for bonus dot from sanctioning
        if (($stats['is_sanctioned'] != '') && ($stats['bonus_attribute'] != '')) {
            CharacterHelper::IncreaseAttribute($stats, $stats['bonus_attribute']);
            $bonus_attribute = '';
        }

        $trans_query = "begin;";
        $trans_result = mysql_query($trans_query) or die(mysql_error());

        if (!$character_id) {
            $insert_query = <<<EOQ
INSERT INTO characters
(
    user_id,
    character_name,
    show_sheet,
    view_password,
    character_type,
    city,
    age,
    sex,
    apparent_age,
    concept,
    description,
    url,
    safe_place,
    friends,
    icon,
    is_npc,
    virtue,
    vice,
    splat1,
    splat2,
    subsplat,
    size,
    speed,
    initiative_mod,
    defense,
    armor,
    health,
    wounds_agg,
    wounds_lethal,
    wounds_bashing,
    willpower_perm,
    willpower_temp,
    power_stat,
    power_points,
    power_points_modifier,
    morality,
    equipment_public,
    equipment_hidden,
    public_effects,
    history,
    character_notes,
    goals,
    hide_icon,
    helper,
    `status`,
    is_sanctioned,
    asst_sanctioned,
    bonus_attribute,
    misc_powers
)
VALUES
(
    $userdata[user_id],
    '$character_name',
    '$show_sheet',
    '$view_password',
    '$character_type',
    '$city',
    $age,
    '$sex',
    $apparent_age,
    '$concept',
    '$description',
    '$url',
    '$safe_place',
    '$friends',
    '$icon',
    '$is_npc',
    '$virtue',
    '$vice',
    '$splat1',
    '$splat2',
    '$subsplat',
    $size,
    $speed,
    $initiative_mod,
    $defense,
    '$armor',
    $health,
    $wounds_agg,
    $wounds_lethal,
    $wounds_bashing,
    $willpower_perm,
    $willpower_temp,
    $power_stat,
    $power_points,
    $power_points_modifier,
    $morality,
    '$equipment_public',
    '$equipment_hidden',
    '$public_effects',
    '$history',
    '$character_notes',
    '$goals',
    '$hide_icon',
    '$helper',
    '$status',
    '',
    '',
    '$bonus_attribute',
    '$misc_powers'
);
EOQ;
            $db->Query($insert_query)->Execute();
            $character_id = $db->GetInsertId();
        }
        else {
            //echo "Do General Update!<br>";
            // start query
            $update_query = "UPDATE characters SET ";

            // run through permissions
            if ($edit_show_sheet) {
                $update_query .= "show_sheet = '$show_sheet', view_password='$view_password', hide_icon = '$hide_icon', ";
            }

            if ($edit_name) {
                if (trim($character_name) == "") {
                    $character_name = "Character " . $stats['character_id'];
                }

                $update_query .= "character_name = '$character_name', ";
            }

            if ($edit_vitals) {
                $update_query .= "character_type = '$character_type', city = '$city', sex = '$sex', virtue = '$virtue', vice = '$vice', splat1 = '$splat1', splat2 = '$splat2', subsplat = '$subsplat', age = $age, apparent_age = $apparent_age, ";
            }

            if ($edit_is_npc) {
                $update_query .= "is_npc = '$is_npc', ";
            }

            if ($edit_is_dead) {
                $update_query .= "status = '$status', ";
            }

            if ($edit_concept) {
                $update_query .= "concept = '$concept', ";
            }

            if ($edit_description) {
                $update_query .= "description = '$description', ";
                $update_query .= "icon = '$icon', ";
            }

            if ($edit_url) {
                $update_query .= "url = '$url', ";
            }

            if ($edit_equipment) {
                $update_query .= "equipment_public = '$equipment_public', equipment_hidden = '$equipment_hidden', ";
            }

            if ($edit_public_effects) {
                $update_query .= "public_effects = '$public_effects', ";
            }

            if ($edit_group) {
                $update_query .= "friends = '$friends', helper = '$helper', safe_place = '$safe_place', ";
            }

            if ($edit_exit_line) {
                $update_query .= "exit_line = '$exit_line', ";
            }

            if ($edit_perm_traits) {
                $update_query .= "power_stat = $power_stat, willpower_perm = $willpower_perm, morality = $morality, health = $health, size = $size, defense = $defense, initiative_mod = $initiative_mod, speed = $speed, armor = '$armor', power_points_modifier = $power_points_modifier, ";
            }

            if ($edit_temp_traits) {
                $update_query .= "power_points = $power_points, willpower_temp = $willpower_temp, wounds_agg = $wounds_agg, wounds_lethal = $wounds_lethal, wounds_bashing = $wounds_bashing, ";
            }

            if ($edit_history) {
                $update_query .= "history = '$history', ";
            }

            if ($edit_powers) {
                $update_query .= "misc_powers = '$misc_powers', ";
            }

            if ($edit_goals) {
                $update_query .= "goals = '$goals', character_notes = '$character_notes', ";
            }


            if ($show_st_notes_table) {
                // check for sanctioned info
                if ($view_is_head) {
                }

                if ($view_is_st) {
                    $update_query .= "is_sanctioned = '$stats[is_sanctioned]', updated_by_id = $userdata[user_id], updated_on = '$now', ";
                }

                if ($view_is_asst) {
                }

                if ($edit_experience) {
                    $update_query .= "current_experience = $current_experience, total_experience = $total_experience, bonus_received = $bonus_received, ";
                }

                // add ST Updates field
                $short_now     = date('Y-m-d');
                $sheet_updates = <<<EOQ
$stats[sheet_updates]
$stats[new_sheet_updates]
$userdata[username] on $short_now
EOQ;
                $sheet_updates = mysql_real_escape_string(htmlspecialchars($sheet_updates));
                $update_query .= "sheet_update = '$sheet_updates', ";

                // test if new st notes
                if (!empty($stats['new_gm_notes'])) {
                    $gm_notes = <<<EOQ
$stats[gm_notes]
$stats[new_gm_notes]
$userdata[username] on $short_now
EOQ;
                    $gm_notes = mysql_real_escape_string(htmlspecialchars($gm_notes));
                    $update_query .= "gm_notes = '$gm_notes', ";
                }
            }

            if ($edit_cell) {
            }

            if ($may_edit) {
                if ($update_query != "update characters set ") {
                    $update_query = substr($update_query, 0, strlen($update_query) - 2);
                    $update_query .= " where id = $stats[character_id];";
                    Database::GetInstance()->Query($update_query)->Execute();
                }
            }
        }

        if ($edit_attributes) {
            SavePower('Attribute', 'attribute', $stats, $character_id);
        }

        // save details
        if ($edit_skills) {
            SavePower('Skill', 'skill', $stats, $character_id);
            saveSpecialtiesXP($stats, $character_id);
        }

        if ($edit_powers) {
            SavePower('Merit', 'merit', $stats, $character_id);
            SavePower('Flaw', 'flaw', $stats, $character_id);
            //saveMeritsXP($stats, $character_id);
            //saveFlawsXP($stats, $character_id);
            $powers = array();
            switch ($character_type) {
                case "Mortal":
                    SavePower('Misc', 'misc', $stats, $character_id);
                    //saveMiscXP($stats, $character_id);
                    break;
                case "Vampire":
                case "Ghoul":
                case "Possessed":
                    SavePower('ICDisc', 'icdisc', $stats, $character_id);
                    SavePower('OOCDisc', 'oocdisc', $stats, $character_id);
                    SavePower('Devotion', 'devotion', $stats, $character_id);
//                    saveICDiscXP($stats, $character_id);
//                    saveOOCDiscXP($stats, $character_id);
//                    saveDevotionsXP($stats, $character_id);
                    break;
                case "Werewolf":
                    SavePower('AffGift', 'affgift', $stats, $character_id);
                    SavePower('NonAffGift', 'nonaffgift', $stats, $character_id);
                    SavePower('Ritual', 'ritual', $stats, $character_id);

//                    saveAffGiftXP($stats, $character_id);
//                    saveNonAffGiftXP($stats, $character_id);
//                    saveRitualXP($stats, $character_id);
                    saveRitualsRenownXP($stats, $character_id);
                    break;
                case "Mage":
                    SavePower('RulingArcana', 'rulingarcana', $stats, $character_id);
                    SavePower('CommonArcana', 'commonarcana', $stats, $character_id);
                    SavePower('InferiorArcana', 'inferiorarcana', $stats, $character_id);
                    SavePower('Rote', 'rote', $stats, $character_id);

//                    saveRulingArcanaXP($stats, $character_id);
//                    saveCommonArcanaXP($stats, $character_id);
//                    saveInferiorArcanaXP($stats, $character_id);
//                    saveRoteXP($stats, $character_id);
                    break;
                case "Psychic":
                    savePsychicMeritXP($stats, $character_id);
                    break;
                case "Thaumaturge":
                    saveThaumaturgeMeritXP($stats, $character_id);
                    break;
                case "Promethean":
                    saveBestowmentXP($stats, $character_id);
                    saveAffTransXP($stats, $character_id);
                    saveNonAffTransXP($stats, $character_id);
                    break;
                case "Changeling":
                    $powers = array(
                        'AffContract'    => 'affcont',
                        'NonAffContract' => 'nonaffcont',
                        'GoblinContract' => 'gobcont'
                    );
                    break;
                case "Hunter":
                    saveEndowmentXP($stats, $character_id);
                    saveTacticXP($stats, $character_id);
                    break;
                case "Geist":
                    $powers = array(
                        'Key'           => 'key',
                        'Manifestation' => 'manifestation',
                        'Ceremonies'    => 'ceremony'
                    );
                    break;
                case "Purified":
                    saveNuminaXP($stats, $character_id);
                    saveSiddhiXP($stats, $character_id);
                    break;
                default:
                    // do  nothing
            }
            foreach ($powers as $key => $value) {
                SavePower($key, $value, $stats, $character_id);
            }

        }

        $trans_query = "commit;";
        $trans_result = mysql_query($trans_query) or die(mysql_error());
    }

    return $error;
}

function SavePower($power_type, $fieldName, $stats, $character_id)
{
    $i = 0;
    while (isset($stats["${fieldName}${i}_name"])) {
        $name  = mysql_real_escape_string(htmlspecialchars($stats["${fieldName}${i}_name"]));
        $note  = mysql_real_escape_string(htmlspecialchars($stats["${fieldName}${i}_note"]));
        $id    = (int)$stats["${fieldName}${i}_id"];
        $level = (int)$stats["${fieldName}$i"];

        $query = "";
        if ($name != "") {
            // a name is assigned
            if ($id > 0) {
                // update
                $query = <<<EOQ
UPDATE
    character_powers
SET
    power_name='$name',
    power_note='$note',
    power_level='$level'
WHERE
    id = $id;
EOQ;
            }
            else {
                // insert
                $query = <<<EOQ
INSERT INTO
    character_powers
    (
        power_type,
        power_name,
        power_note,
        power_level,
        character_id
    )
VALUES
    (
        '$power_type',
        '$name',
        '$note',
        $level,
        $character_id
    );
EOQ;
            }
        }
        else {
            // check to see if we delete
            if ($id) {
                // delete
                $query = "DELETE FROM character_powers WHERE id = $id;";
            }
        }

        if ($query != "") {
            Database::GetInstance()->Query($query)->Execute();
        }
        $i++;
    }
}

function saveSpecialtiesXP($stats, $character_id)
{
    //echo "Saving Skill Specialties<br>";
    $i = 0;
    while (isset($stats["skill_spec$i"])) {
        $skill_spec_selected = mysql_real_escape_string(htmlspecialchars($stats["skill_spec${i}_selected"]));
        $skill_spec_id       = $stats["skill_spec${i}_id"] + 0;

        $skill_spec = mysql_real_escape_string($stats["skill_spec$i"]);

        $query = "";
        if ($skill_spec != "") {
            // found skill_spec
            if ($skill_spec_id) {
                // update
                $query = "update character_powers set power_name='$skill_spec', power_note='$skill_spec_selected' where id = $skill_spec_id;";
            }
            else {
                // insert
                $query = "insert into character_powers (power_type, power_name, power_note, character_id) values ('Specialty', '$skill_spec', '$skill_spec_selected', $character_id);";
            }
        }
        else {
            // check to see if we delete
            if ($skill_spec_id) {
                // delete
                $query = "delete from character_powers where id = $skill_spec_id;";
            }
        }

        if ($query != "") {
            //echo $query."<br>";
            $result = mysql_query($query) or die(mysql_error());
        }

        $i++;
    }
}

function saveMeritsXP($stats, $character_id)
{
    //echo "Saving merits<br>";
    $i = 0;
    while (isset($stats["merit$i"])) {
        $merit_name = htmlspecialchars($stats["merit${i}_name"]);
        $merit_note = htmlspecialchars($stats["merit${i}_note"]);
        $merit_id   = $stats["merit${i}_id"] + 0;
        $merit      = $stats["merit$i"] + 0;

        $query = "";
        if ($merit_name != "") {
            // found merit
            if ($merit_id) {
                // update
                $query = "update character_powers set power_name='$merit_name', power_note='$merit_note', power_level='$merit' where id = $merit_id;";
            }
            else {
                // insert
                $query = "insert into character_powers (power_type, power_name, power_note, power_level, character_id) values ('Merit', '$merit_name', '$merit_note', $merit, $character_id);";
            }
        }
        else {
            // check to see if we delete
            if ($merit_id) {
                // delete
                $query = "delete from character_powers where id = $merit_id;";
            }
        }

        if ($query != "") {
            //echo $query."<br>";
            $result = mysql_query($query) or die(mysql_error());
        }

        $i++;
    }
}

function saveFlawsXP($stats, $character_id)
{
    //echo "Saving flaws<br>";
    $i = 0;
    while (isset($stats["flaw${i}_name"])) {
        $flaw_name = htmlspecialchars($stats["flaw${i}_name"]);
        $flaw_id   = $stats["flaw${i}_id"] + 0;

        $query = "";
        if ($flaw_name != "") {
            // found flaw
            if ($flaw_id) {
                // update
                $query = "update character_powers set power_name='$flaw_name' where id = $flaw_id;";
            }
            else {
                // insert
                $query = "insert into character_powers (power_type, power_name, character_id) values ('Flaw', '$flaw_name', $character_id);";
            }
        }
        else {
            // check to see if we delete
            if ($flaw_id) {
                // delete
                $query = "delete from character_powers where id = $flaw_id;";
            }
        }

        if ($query != "") {
            //echo $query."<br>";
            $result = mysql_query($query) or die(mysql_error());
        }

        $i++;
    }
}

function saveMiscXP($stats, $character_id)
{
    //echo "Saving misc<br>";
    $i = 0;
    while (isset($stats["misc${i}_name"])) {
        $misc_name = htmlspecialchars($stats["misc${i}_name"]);
        //$misc_level = $stats["misc${i}_level"] + 0;
        $misc_id = $stats["misc${i}_id"] + 0;

        $query = "";
        if ($misc_name != "") {
            // found misc
            if ($misc_id) {
                // update
                $query = "update character_powers set power_name='$misc_name' where id = $misc_id;";
            }
            else {
                // insert
                $query = "insert into character_powers (power_type, power_name, character_id) values ('Misc', '$misc_name', $character_id);";
            }
        }
        else {
            // check to see if we delete
            if ($misc_id) {
                // delete
                $query = "delete from character_powers where id = $misc_id;";
            }
        }

        if ($query != "") {
            //echo $query."<br>";
            $result = mysql_query($query) or die(mysql_error());
        }

        $i++;
    }
}

function saveICDiscXP($stats, $character_id)
{
    $i = 0;
    while (isset($stats["icdisc$i"])) {
        $icdisc_name = htmlspecialchars($stats["icdisc${i}_name"]);
        $icdisc_id   = $stats["icdisc${i}_id"] + 0;
        $icdisc_note = '';
        $icdisc      = $stats["icdisc$i"] + 0;

        $query = "";
        if ($icdisc_name != "") {
            // found icdisc
            if ($icdisc_id) {
                // update
                $query = <<<EOQ
UPDATE
    character_powers
SET
    power_name='$icdisc_name',
    power_level='$icdisc'
WHERE
    id = $icdisc_id;
EOQ;

            }
            else {
                // insert
                $query = <<<EOQ
INSERT INTO
    character_powers
    (
        power_type,
        power_name,
        power_note,
        power_level,
        character_id
    )
VALUES
    (
        'ICDisc',
        '$icdisc_name',
        '$icdisc_note',
        $icdisc,
        $character_id
    );
EOQ;
            }
        }
        else {
            // check to see if we delete
            if ($icdisc_id) {
                // delete
                $query = "delete from character_powers where id = $icdisc_id;";
            }
        }

        if ($query != "") {
            //echo $query."<br>";
            $result = mysql_query($query) or die(mysql_error());
        }

        $i++;
    }
}

function saveOOCDiscXP($stats, $character_id)
{
    //echo "Saving oocdiscs<br>";
    $i = 0;
    while (isset($stats["oocdisc$i"])) {
        $oocdisc_name = htmlspecialchars($stats["oocdisc${i}_name"]);
        $oocdisc_note = '';
        $oocdisc_id   = $stats["oocdisc${i}_id"] + 0;
        $oocdisc      = $stats["oocdisc$i"] + 0;

        $query = "";
        if ($oocdisc_name != "") {
            // found oocdisc
            if ($oocdisc_id) {
                // update
                $query = "update character_powers set power_name='$oocdisc_name', power_level='$oocdisc' where id = $oocdisc_id;";
            }
            else {
                // insert
                $query = "insert into character_powers (power_type, power_name, power_note, power_level, character_id) values ('OOCDisc', '$oocdisc_name', '$oocdisc_note', $oocdisc, $character_id);";
            }
        }
        else {
            // check to see if we delete
            if ($oocdisc_id) {
                // delete
                $query = "delete from character_powers where id = $oocdisc_id;";
            }
        }

        if ($query != "") {
            //echo $query."<br>";
            $result = mysql_query($query) or die(mysql_error());
        }

        $i++;
    }
}

function saveDevotionsXP($stats, $character_id)
{
    //echo "Saving devotions<br>";
    $i = 0;
    while (isset($stats["devotion${i}_cost"])) {
        $devotion_name = htmlspecialchars($stats["devotion${i}_name"]);
        $devotion_id   = $stats["devotion${i}_id"] + 0;
        $devotion_note = '';
        $devotion      = $stats["devotion${i}_cost"] + 0;

        $query = "";
        if ($devotion_name != "") {
            // found devotion
            if ($devotion_id) {
                // update
                $query = "update character_powers set power_name='$devotion_name', power_level='$devotion' where id = $devotion_id;";
            }
            else {
                // insert
                $query = "insert into character_powers (power_type, power_name, power_note, power_level, character_id) values ('Devotion', '$devotion_name', '$devotion_note', $devotion, $character_id);";
            }
        }
        else {
            // check to see if we delete
            if ($devotion_id) {
                // delete
                $query = "delete from character_powers where id = $devotion_id;";
            }
        }

        if ($query != "") {
            //echo $query."<br>";
            $result = mysql_query($query) or die(mysql_error());
        }

        $i++;
    }
}

function saveAffGiftXP($stats, $character_id)
{
    //echo "Saving affgifts<br>";
    $i = 0;
    while (isset($stats["affgift$i"])) {
        $affgift_name = htmlspecialchars($stats["affgift${i}_name"]);
        $affgift_list = htmlspecialchars($stats["affgift${i}_note"]);
        $affgift_id   = $stats["affgift${i}_id"] + 0;
        $affgift      = $stats["affgift$i"] + 0;

        $query = "";
        if ($affgift_name != "") {
            // found affgift
            if ($affgift_id) {
                // update
                $query = "update character_powers set power_name='$affgift_name', power_note='$affgift_list', power_level='$affgift' where id = $affgift_id;";
            }
            else {
                // insert
                $query = "insert into character_powers (power_type, power_name, power_note, power_level, character_id) values ('AffGift', '$affgift_name', '$affgift_list', $affgift, $character_id);";
            }
        }
        else {
            // check to see if we delete
            if ($affgift_id) {
                // delete
                $query = "delete from character_powers where id = $affgift_id;";
            }
        }

        if ($query != "") {
            //echo $query."<br>";
            $result = mysql_query($query) or die(mysql_error());
        }

        $i++;
    }
}

function saveNonAffGiftXP($stats, $character_id)
{
    //echo "Saving nonaffgifts<br>";
    $i = 0;
    while (isset($stats["nonaffgift$i"])) {
        $nonaffgift_name = htmlspecialchars($stats["nonaffgift${i}_name"]);
        $nonaffgift_list = htmlspecialchars($stats["nonaffgift${i}_note"]);
        $nonaffgift_id   = $stats["nonaffgift${i}_id"] + 0;
        $nonaffgift      = $stats["nonaffgift$i"] + 0;

        $query = "";
        if ($nonaffgift_name != "") {
            // found nonaffgift
            if ($nonaffgift_id) {
                // update
                $query = "update character_powers set power_name='$nonaffgift_name', power_note='$nonaffgift_list', power_level='$nonaffgift' where id = $nonaffgift_id;";
            }
            else {
                // insert
                $query = "insert into character_powers (power_type, power_name, power_note, power_level, character_id) values ('NonAffGift', '$nonaffgift_name', '$nonaffgift_list', $nonaffgift, $character_id);";
            }
        }
        else {
            // check to see if we delete
            if ($nonaffgift_id) {
                // delete
                $query = "delete from character_powers where id = $nonaffgift_id;";
            }
        }

        if ($query != "") {
            //echo $query."<br>";
            $result = mysql_query($query) or die(mysql_error());
        }

        $i++;
    }
}

function saveRitualXP($stats, $character_id)
{
    //echo "Saving rituals<br>";
    $i = 0;
    while (isset($stats["ritual$i"])) {
        $ritual_name = htmlspecialchars($stats["ritual${i}_name"]);
        $ritual_id   = $stats["ritual${i}_id"] + 0;
        $ritual      = $stats["ritual$i"] + 0;

        $query = "";
        if ($ritual_name != "") {
            // found ritual
            if ($ritual_id) {
                // update
                $query = "update character_powers set power_name='$ritual_name', power_level='$ritual' where id = $ritual_id;";
            }
            else {
                // insert
                $query = "insert into character_powers (power_type, power_name, power_note, power_level, character_id) values ('Ritual', '$ritual_name', '', $ritual, $character_id);";
            }
        }
        else {
            // check to see if we delete
            if ($ritual_id) {
                // delete
                $query = "delete from character_powers where id = $ritual_id;";
            }
        }

        if ($query != "") {
            //echo $query."<br>";
            $result = mysql_query($query) or die(mysql_error());
        }

        $i++;
    }
}

function saveRitualsRenownXP($stats, $character_id)
{
    //echo "Saving renowns<br>";
    $renowns = array("purity", "glory", "honor", "wisdom", "cunning");

    for ($i = 0; $i < 5; $i++) {
        $renown_name  = $renowns[$i];
        $renown_level = $stats[$renowns[$i]] + 0;
        $renown_id    = $stats[$renowns[$i] . "_id"] + 0;

        if ($renown_id) {
            // update
            $query = "update character_powers set power_level='$renown_level' where id = $renown_id;";
        }
        else {
            // insert
            $query = "insert into character_powers (power_type, power_name, power_note, power_level, character_id) values ('Renown', '$renown_name', '', $renown_level, $character_id);";
        }

        if ($query != "") {
            //echo "$renown_name: " . $query."<br>";
            $result = mysql_query($query) or die(mysql_error());
        }
    }

    $rituals    = $stats["rituals"] + 0;
    $rituals_id = $stats["rituals_id"] + 0;
    if ($rituals_id) {
        $query = "update character_powers set power_level='$rituals' where id = $rituals_id;";
    }
    else {
        $query = "insert into character_powers (power_type, power_name, power_note, power_level, character_id) values ('Rituals', 'Rituals', '', $rituals, $character_id);";
    }

    if ($query != "") {
        //echo $query."<br>";
        $result = mysql_query($query) or die(mysql_error());
    }
}

function saveRulingArcanaXP($stats, $character_id)
{
    //echo "Saving rulingarcanas<br>";
    $i = 0;
    while (isset($stats["rulingarcana$i"])) {
        $rulingarcana_name = htmlspecialchars($stats["rulingarcana${i}_name"]);
        $rulingarcana_id   = $stats["rulingarcana${i}_id"] + 0;
        $rulingarcana_note = '';
        $rulingarcana      = $stats["rulingarcana$i"] + 0;

        $query = "";
        if ($rulingarcana_name != "") {
            // found rulingarcana
            if ($rulingarcana_id) {
                // update
                $query = "update character_powers set power_name='$rulingarcana_name', power_level='$rulingarcana' where id = $rulingarcana_id;";
            }
            else {
                // insert
                $query = "insert into character_powers (power_type, power_name, power_note, power_level, character_id) values ('RulingArcana', '$rulingarcana_name', '$rulingarcana_note', $rulingarcana, $character_id);";
            }
        }
        else {
            // check to see if we delete
            if ($rulingarcana_id) {
                // delete
                $query = "delete from character_powers where id = $rulingarcana_id;";
            }
        }

        if ($query != "") {
            //echo $query."<br>";
            $result = mysql_query($query) or die(mysql_error());
        }

        $i++;
    }
}

function saveCommonArcanaXP($stats, $character_id)
{
    //echo "Saving commonarcanas<br>";
    $i = 0;
    while (isset($stats["commonarcana$i"])) {
        $commonarcana_name = htmlspecialchars($stats["commonarcana${i}_name"]);
        $commonarcana_id   = $stats["commonarcana${i}_id"] + 0;
        $commonarcana_note = '';
        $commonarcana      = $stats["commonarcana$i"] + 0;

        $query = "";
        if ($commonarcana_name != "") {
            // found commonarcana
            if ($commonarcana_id) {
                // update
                $query = "update character_powers set power_name='$commonarcana_name', power_level='$commonarcana' where id = $commonarcana_id;";
            }
            else {
                // insert
                $query = "insert into character_powers (power_type, power_name, power_note, power_level, character_id) values ('CommonArcana', '$commonarcana_name', '$commonarcana_note', $commonarcana, $character_id);";
            }
        }
        else {
            // check to see if we delete
            if ($commonarcana_id) {
                // delete
                $query = "delete from character_powers where id = $commonarcana_id;";
            }
        }

        if ($query != "") {
            //echo $query."<br>";
            $result = mysql_query($query) or die(mysql_error());
        }

        $i++;
    }
}

function saveInferiorArcanaXP($stats, $character_id)
{
    //echo "Saving inferiorarcanas<br>";
    $i = 0;
    while (isset($stats["inferiorarcana$i"])) {
        $inferiorarcana_name = htmlspecialchars($stats["inferiorarcana${i}_name"]);
        $inferiorarcana_id   = $stats["inferiorarcana${i}_id"] + 0;
        $inferiorarcana_note = '';
        $inferiorarcana      = $stats["inferiorarcana$i"] + 0;

        $query = "";
        if ($inferiorarcana_name != "") {
            // found inferiorarcana
            if ($inferiorarcana_id) {
                // update
                $query = "update character_powers set power_name='$inferiorarcana_name', power_level='$inferiorarcana' where id = $inferiorarcana_id;";
            }
            else {
                // insert
                $query = "insert into character_powers (power_type, power_name, power_note, power_level, character_id) values ('InferiorArcana', '$inferiorarcana_name', '$inferiorarcana_note', $inferiorarcana, $character_id);";
            }
        }
        else {
            // check to see if we delete
            if ($inferiorarcana_id) {
                // delete
                $query = "delete from character_powers where id = $inferiorarcana_id;";
            }
        }

        if ($query != "") {
            //echo $query."<br>";
            $result = mysql_query($query) or die(mysql_error());
        }

        $i++;
    }
}

function saveRoteXP($stats, $character_id)
{
    $i = 0;
    while (isset($stats["rote${i}_level"])) {
        $rote_name = htmlspecialchars($stats["rote${i}_name"]);
        $rote_note = htmlspecialchars($stats["rote${i}_note"]);
        $rote_id   = $stats["rote${i}_id"] + 0;
        $rote      = $stats["rote${i}_level"] + 0;


        $query = "";
        if ($rote_name != "") {
            // found rote
            if ($rote_id) {
                // update
                $query = "update character_powers set power_name='$rote_name', power_note='$rote_note', power_level='$rote' where id = $rote_id;";
            }
            else {
                // insert
                $query = "insert into character_powers (power_type, power_name, power_note, power_level, character_id) values ('Rote', '$rote_name', '$rote_note', $rote, $character_id);";
            }
        }
        else {
            // check to see if we delete
            if ($rote_id) {
                // delete
                $query = "delete from character_powers where id = $rote_id;";
            }
        }

        if ($query != "") {
            //echo $query."<br>";
            $result = mysql_query($query) or die(mysql_error());
        }

        $i++;
    }
}

function savePsychicMeritXP($stats, $character_id)
{
    $i = 0;
    while (isset($stats["psychicmerit${i}"])) {
        $psychicmerit_name = htmlspecialchars($stats["psychicmerit${i}_name"]);
        $psychicmerit_note = htmlspecialchars($stats["psychicmerit${i}_note"]);
        $psychicmerit_id   = $stats["psychicmerit${i}_id"] + 0;
        $psychicmerit      = $stats["psychicmerit${i}"] + 0;


        $query = "";
        if ($psychicmerit_name != "") {
            // found psychicmerit
            if ($psychicmerit_id) {
                // update
                $query = "update character_powers set power_name='$psychicmerit_name', power_note='$psychicmerit_note', power_level='$psychicmerit' where id = $psychicmerit_id;";
            }
            else {
                // insert
                $query = "insert into character_powers (power_type, power_name, power_note, power_level, character_id) values ('PsychicMerit', '$psychicmerit_name', '$psychicmerit_note', $psychicmerit, $character_id);";
            }
        }
        else {
            // check to see if we delete
            if ($psychicmerit_id) {
                // delete
                $query = "delete from character_powers where id = $psychicmerit_id;";
            }
        }

        if ($query != "") {
            //echo $query."<br>";
            $result = mysql_query($query) or die(mysql_error());
        }

        $i++;
    }
}

function saveThaumaturgeMeritXP($stats, $character_id)
{
    $i = 0;
    while (isset($stats["thaumaturgemerit${i}"])) {
        $thaumaturgemerit_name = htmlspecialchars($stats["thaumaturgemerit${i}_name"]);
        $thaumaturgemerit_note = htmlspecialchars($stats["thaumaturgemerit${i}_note"]);
        $thaumaturgemerit_id   = $stats["thaumaturgemerit${i}_id"] + 0;
        $thaumaturgemerit      = $stats["thaumaturgemerit${i}"] + 0;


        $query = "";
        if ($thaumaturgemerit_name != "") {
            // found thaumaturgemerit
            if ($thaumaturgemerit_id) {
                // update
                $query = "update character_powers set power_name='$thaumaturgemerit_name', power_note='$thaumaturgemerit_note', power_level='$thaumaturgemerit' where id = $thaumaturgemerit_id;";
            }
            else {
                // insert
                $query = "insert into character_powers (power_type, power_name, power_note, power_level, character_id) values ('ThaumaturgeMerit', '$thaumaturgemerit_name', '$thaumaturgemerit_note', $thaumaturgemerit, $character_id);";
            }
        }
        else {
            // check to see if we delete
            if ($thaumaturgemerit_id) {
                // delete
                $query = "delete from character_powers where id = $thaumaturgemerit_id;";
            }
        }

        if ($query != "") {
            //echo $query."<br>";
            $result = mysql_query($query) or die(mysql_error());
        }

        $i++;
    }
}

function saveBestowmentXP($stats, $character_id)
{
    //echo "Saving bestowments<br>";
    $i = 0;
    while (isset($stats["bestowment${i}_cost"])) {
        $bestowment_name = htmlspecialchars($stats["bestowment${i}_name"]);
        $bestowment_id   = $stats["bestowment${i}_id"] + 0;
        $bestowment      = $stats["bestowment${i}_cost"] + 0;

        $query = "";
        if ($bestowment_name != "") {
            // found bestowment
            if ($bestowment_id) {
                // update
                $query = "update character_powers set power_name='$bestowment_name', power_level='$bestowment' where id = $bestowment_id;";
            }
            else {
                // insert
                $query = "insert into character_powers (power_type, power_name, power_note, power_level, character_id) values ('Bestowment', '$bestowment_name', '', $bestowment, $character_id);";
            }
        }
        else {
            // check to see if we delete
            if ($bestowment_id) {
                // delete
                $query = "delete from character_powers where id = $bestowment_id;";
            }
        }

        if ($query != "") {
            //echo $query."<br>";
            $result = mysql_query($query) or die(mysql_error());
        }

        $i++;
    }
}

function saveAffTransXP($stats, $character_id)
{
    //echo "Saving afftranss<br>";
    $i = 0;
    while (isset($stats["afftrans$i"])) {
        $afftrans_name = htmlspecialchars($stats["afftrans${i}_name"]);
        $afftrans_list = htmlspecialchars($stats["afftrans${i}_list"]);
        $afftrans_id   = $stats["afftrans${i}_id"] + 0;
        $afftrans      = $stats["afftrans$i"] + 0;

        $query = "";
        if ($afftrans_name != "") {
            // found afftrans
            if ($afftrans_id) {
                // update
                $query = "update character_powers set power_name='$afftrans_name', power_note='$afftrans_list', power_level='$afftrans' where id = $afftrans_id;";
            }
            else {
                // insert
                $query = "insert into character_powers (power_type, power_name, power_note, power_level, character_id) values ('AffTrans', '$afftrans_name', '$afftrans_list', $afftrans, $character_id);";
            }
        }
        else {
            // check to see if we delete
            if ($afftrans_id) {
                // delete
                $query = "delete from character_powers where id = $afftrans_id;";
            }
        }

        if ($query != "") {
            //echo $query."<br>";
            $result = mysql_query($query) or die(mysql_error());
        }

        $i++;
    }
}

function saveNonAffTransXP($stats, $character_id)
{
    //echo "Saving nonafftranss<br>";
    $i = 0;
    while (isset($stats["nonafftrans$i"])) {
        $nonafftrans_name = htmlspecialchars($stats["nonafftrans${i}_name"]);
        $nonafftrans_list = htmlspecialchars($stats["nonafftrans${i}_list"]);
        $nonafftrans_id   = $stats["nonafftrans${i}_id"] + 0;
        $nonafftrans      = $stats["nonafftrans$i"] + 0;

        $query = "";
        if ($nonafftrans_name != "") {
            // found nonafftrans
            if ($nonafftrans_id) {
                // update
                $query = "update character_powers set power_name='$nonafftrans_name', power_note='$nonafftrans_list', power_level='$nonafftrans' where id = $nonafftrans_id;";
            }
            else {
                // insert
                $query = "insert into character_powers (power_type, power_name, power_note, power_level, character_id) values ('NonAffTrans', '$nonafftrans_name', '$nonafftrans_list', $nonafftrans, $character_id);";
            }
        }
        else {
            // check to see if we delete
            if ($nonafftrans_id) {
                // delete
                $query = "delete from character_powers where id = $nonafftrans_id;";
            }
        }

        if ($query != "") {
            //echo $query."<br>";
            $result = mysql_query($query) or die(mysql_error());
        }

        $i++;
    }
}

function saveAffContXP($stats, $character_id)
{
    //echo "Saving affconts<br>";
    $i = 0;
    while (isset($stats["affcont$i"])) {
        $affcont_name = htmlspecialchars($stats["affcont${i}_list"]);
        $affcont_list = htmlspecialchars($stats["affcont${i}_name"]);
        $affcont_id   = $stats["affcont${i}_id"] + 0;
        $affcont      = $stats["affcont$i"] + 0;

        $query = "";
        if ($affcont_name != "") {
            // found affcont
            if ($affcont_id) {
                // update
                $query = "update character_powers set power_name='$affcont_name', power_note='$affcont_list', power_level='$affcont' where id = $affcont_id;";
            }
            else {
                // insert
                $query = "insert into character_powers (power_type, power_name, power_note, power_level, character_id) values ('AffContract', '$affcont_name', '$affcont_list', $affcont, $character_id);";
            }
        }
        else {
            // check to see if we delete
            if ($affcont_id) {
                // delete
                $query = "delete from character_powers where id = $affcont_id;";
            }
        }

        if ($query != "") {
            //echo $query."<br>";
            $result = mysql_query($query) or die(mysql_error());
        }

        $i++;
    }
}

function saveNonAffContXP($stats, $character_id)
{
    //echo "Saving nonaffconts<br>";
    $i = 0;
    while (isset($stats["nonaffcont$i"])) {
        $nonaffcont_name = htmlspecialchars($stats["nonaffcont${i}_list"]);
        $nonaffcont_list = htmlspecialchars($stats["nonaffcont${i}_name"]);
        $nonaffcont_id   = $stats["nonaffcont${i}_id"] + 0;
        $nonaffcont      = $stats["nonaffcont$i"] + 0;

        $query = "";
        if ($nonaffcont_name != "") {
            // found nonaffcont
            if ($nonaffcont_id) {
                // update
                $query = "update character_powers set power_name='$nonaffcont_name', power_note='$nonaffcont_list', power_level='$nonaffcont' where id = $nonaffcont_id;";
            }
            else {
                // insert
                $query = "insert into character_powers (power_type, power_name, power_note, power_level, character_id) values ('NonAffContract', '$nonaffcont_name', '$nonaffcont_list', $nonaffcont, $character_id);";
            }
        }
        else {
            // check to see if we delete
            if ($nonaffcont_id) {
                // delete
                $query = "delete from character_powers where id = $nonaffcont_id;";
            }
        }

        if ($query != "") {
            //echo $query."<br>";
            $result = mysql_query($query) or die(mysql_error());
        }

        $i++;
    }
}

function saveGobContXP($stats, $character_id)
{
    //echo "Saving gobconts<br>";
    $i = 0;
    while (isset($stats["gobcont$i"])) {
        $gobcont_name = htmlspecialchars($stats["gobcont${i}_name"]);
        $gobcont_id   = $stats["gobcont${i}_id"] + 0;
        $gobcont      = $stats["gobcont$i"] + 0;

        $query = "";
        if ($gobcont_name != "") {
            // found gobcont
            if ($gobcont_id) {
                // update
                $query = "update character_powers set power_name='$gobcont_name', power_level='$gobcont' where id = $gobcont_id;";
            }
            else {
                // insert
                $query = "insert into character_powers (power_type, power_name, power_note, power_level, character_id) values ('GoblinContract', '$gobcont_name', '', $gobcont, $character_id);";
            }
        }
        else {
            // check to see if we delete
            if ($gobcont_id) {
                // delete
                $query = "delete from character_powers where id = $gobcont_id;";
            }
        }

        if ($query != "") {
            //echo $query."<br>";
            $result = mysql_query($query) or die(mysql_error());
        }

        $i++;
    }
}

function saveEndowmentXP($stats, $character_id)
{
    $i = 0;
    while (isset($stats["endowment$i"])) {
        $endowment_name = htmlspecialchars($stats["endowment${i}_name"]);
        $endowment_note = htmlspecialchars($stats["endowment${i}_note"]);
        $endowment_id   = $stats["endowment${i}_id"] + 0;
        $endowment      = $stats["endowment$i"] + 0;

        $query = "";
        if ($endowment_name != "") {
            // found endowment
            if ($endowment_id) {
                // update
                $query = "update character_powers set power_name='$endowment_name', power_note='$endowment_note', power_level='$endowment' where id = $endowment_id;";
            }
            else {
                // insert
                $query = "insert into character_powers (power_type, power_name, power_note, power_level, character_id) values ('Endowment', '$endowment_name', '$endowment_note', $endowment, $character_id);";
            }
        }
        else {
            // check to see if we delete
            if ($endowment_id) {
                // delete
                $query = "delete from character_powers where id = $endowment_id;";
            }
        }

        if ($query != "") {
            //echo $query."<br>";
            $result = mysql_query($query) or die(mysql_error());
        }

        $i++;
    }
}

function saveTacticXP($stats, $character_id)
{
    //echo "Saving tactics<br>";
    $i = 0;
    while (isset($stats["tactic${i}_cost"])) {
        $tactic_name = htmlspecialchars($stats["tactic${i}_name"]);
        $tactic_cost = htmlspecialchars($stats["tactic${i}_cost"]);
        $tactic_id   = $stats["tactic${i}_id"] + 0;

        $query = "";
        if ($tactic_name != "") {
            // found tactic
            if ($tactic_id) {
                // update
                $query = "update character_powers set power_name='$tactic_name', power_level='$tactic_cost' where id = $tactic_id;";
            }
            else {
                // insert
                $query = "insert into character_powers (power_type, power_name, power_note, power_level, character_id) values ('Tactic', '$tactic_name', '', $tactic_cost, $character_id);";
            }
        }
        else {
            // check to see if we delete
            if ($tactic_id) {
                // delete
                $query = "delete from character_powers where id = $tactic_id;";
            }
        }

        if ($query != "") {
            //echo $query."<br>";
            $result = mysql_query($query) or die(mysql_error());
        }

        $i++;
    }
}

function saveKeyXP($stats, $character_id)
{
    $i = 0;
    while (isset($stats["key${i}_name"])) {
        $name = htmlspecialchars($stats["key${i}_name"]);
        $id   = $stats["key${i}_id"] + 0;

        $query = "";
        if ($name != "") {
            // found tactic
            if ($id) {
                // update
                $query = "update character_powers set power_name='$name' where id = $id;";
            }
            else {
                // insert
                $query = "insert into character_powers (power_type, power_name, power_note, power_level, character_id) values ('Key', '$name', '', 0, $character_id);";
            }
        }
        else {
            // check to see if we delete
            if ($id) {
                // delete
                $query = "delete from character_powers where id = $id;";
            }
        }

        if ($query != "") {
            //echo $query."<br>";
            $result = mysql_query($query) or die(mysql_error());
        }

        $i++;
    }
}

function saveMomentoXP($stats, $character_id)
{
    $i = 0;
    while (isset($stats["momento${i}_name"])) {
        $name = htmlspecialchars($stats["momento${i}_name"]);
        $id   = $stats["momento${i}_id"] + 0;

        $query = "";
        if ($name != "") {
            // found tactic
            if ($id) {
                // update
                $query = "update character_powers set power_name='$name' where id = $id;";
            }
            else {
                // insert
                $query = "insert into character_powers (power_type, power_name, power_note, power_level, character_id) values ('Momento', '$name', '', 0, $character_id);";
            }
        }
        else {
            // check to see if we delete
            if ($id) {
                // delete
                $query = "delete from character_powers where id = $id;";
            }
        }

        if ($query != "") {
            //echo $query."<br>";
            $result = mysql_query($query) or die(mysql_error());
        }

        $i++;
    }
}

function saveCeremoniesXP($stats, $character_id)
{
    $i = 0;
    while (isset($stats["momento${i}_name"])) {
        $name  = htmlspecialchars($stats["ceremony${i}_name"]);
        $level = $stats["ceremony${i}"] + 0;
        $id    = $stats["ceremony${i}_id"] + 0;

        $query = "";
        if ($name != "") {
            // found tactic
            if ($id) {
                // update
                $query = "update character_powers set power_name='$name', power_level = $level where id = $id;";
            }
            else {
                // insert
                $query = "insert into character_powers (power_type, power_name, power_note, power_level, character_id) values ('Ceremonies', '$name', '', $level, $character_id);";
            }
        }
        else {
            // check to see if we delete
            if ($id) {
                // delete
                $query = "delete from character_powers where id = $id;";
            }
        }

        if ($query != "") {
            //echo $query."<br>";
            $result = mysql_query($query) or die(mysql_error());
        }

        $i++;
    }
}

function saveManifestationXP($stats, $character_id)
{
    $i = 0;
    while (isset($stats["manifestation${i}_name"])) {
        $name  = htmlspecialchars($stats["manifestation${i}_name"]);
        $level = $stats["manifestation${i}"] + 0;
        $id    = $stats["manifestation${i}_id"] + 0;

        $query = "";
        if ($name != "") {
            // found tactic
            if ($id) {
                // update
                $query = "update character_powers set power_name='$name', power_level = $level where id = $id;";
            }
            else {
                // insert
                $query = "insert into character_powers (power_type, power_name, power_note, power_level, character_id) values ('Manifestation', '$name', '', $level, $character_id);";
            }
        }
        else {
            // check to see if we delete
            if ($id) {
                // delete
                $query = "delete from character_powers where id = $id;";
            }
        }

        if ($query != "") {
            //echo $query."<br>";
            $result = mysql_query($query) or die(mysql_error());
        }

        $i++;
    }
}

function saveNuminaXP($stats, $character_id)
{
    $i = 0;
    while (isset($stats["numina${i}_name"])) {
        $name = htmlspecialchars($stats["numina${i}_name"]);
        $id   = $stats["numina${i}_id"] + 0;

        $query = "";
        if ($name != "") {
            // found tactic
            if ($id) {
                // update
                $query = "update character_powers set power_name='$name' where id = $id;";
            }
            else {
                // insert
                $query = "insert into character_powers (power_type, power_name, power_note, power_level, character_id) values ('Numina', '$name', '', 0, $character_id);";
            }
        }
        else {
            // check to see if we delete
            if ($id) {
                // delete
                $query = "delete from character_powers where id = $id;";
            }
        }

        if ($query != "") {
            //echo $query."<br>";
            $result = mysql_query($query) or die(mysql_error());
        }

        $i++;
    }
}

function saveSiddhiXP($stats, $character_id)
{
    $i = 0;
    while (isset($stats["siddhi${i}_name"])) {
        $name  = htmlspecialchars($stats["siddhi${i}_name"]);
        $level = $stats["siddhi${i}"] + 0;
        $id    = $stats["siddhi${i}_id"] + 0;

        $query = "";
        if ($name != "") {
            // found tactic
            if ($id) {
                // update
                $query = "update character_powers set power_name='$name', power_level = $level where id = $id;";
            }
            else {
                // insert
                $query = "insert into character_powers (power_type, power_name, power_note, power_level, character_id) values ('Siddhi', '$name', '', $level, $character_id);";
            }
        }
        else {
            // check to see if we delete
            if ($id) {
                // delete
                $query = "delete from character_powers where id = $id;";
            }
        }

        if ($query != "") {
            //echo $query."<br>";
            $result = mysql_query($query) or die(mysql_error());
        }

        $i++;
    }
}