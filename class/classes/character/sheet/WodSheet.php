<?php
/**
 * Created by PhpStorm.
 * User: jvandenberg
 * Date: 9/6/2015
 * Time: 7:29 PM
 */

namespace classes\character\sheet;


use classes\character\data\Character;
use classes\character\data\CharacterPower;
use classes\character\repository\CharacterPowerRepository;
use classes\character\repository\CharacterRepository;
use classes\core\helpers\Configuration;
use classes\core\helpers\FormHelper;
use classes\core\repository\Database;
use classes\core\repository\RepositoryManager;
use classes\utility\ArrayTools;

class WodSheet
{
    const NAMELEVEL = 1;
    const NOTELEVEL = 2;
    const NOTENAME = 3;
    const NAMENOTE = 4;

    const ATTRIBUTE = 1;
    const SKILL = 2;
    const MERIT = 3;
    const SUPERNATURAL = 4;
    const POWERTRAIT = 5;
    const MORALITY = 6;

    public $viewOptions = array(
        'edit_show_sheet' => false,
        'edit_name' => false,
        'edit_vitals' => false,
        'edit_is_dead' => false,
        'edit_concept' => false,
        'edit_description' => false,
        'edit_equipment' => false,
        'edit_public_effects' => false,
        'edit_group' => false,
        'edit_is_npc' => false,
        'edit_attributes' => false,
        'edit_skills' => false,
        'edit_perm_traits' => false,
        'edit_temp_traits' => false,
        'edit_powers' => false,
        'edit_history' => false,
        'edit_goals' => false,
        'edit_experience' => false,
        'show_st_notes' => false,
        'calculate_derived' => false,
        'xp_create_mode' => false,
        'user_type' => 'player'
    );

    public $stats;
    public $table_class;
    public $max_dots;

    public function buildSheet($character_type = 'Mortal', $stats = array(), $options = array())
    {
        if (!is_array($stats)) {
            $stats = array();
        }
        $this->viewOptions = array_merge($this->viewOptions, $options);
        $this->viewOptions['allow_edits'] = $this->checkEditMode();
        $this->stats = $stats;
        $this->stats['character_type'] = $character_type;
        $this->max_dots = 7;

        // initialize sheet values
        $attribute_xp = 135;
        $skill_xp = 105;
        $merit_xp = 32;
        $general_xp = Configuration::read('GENERAL_XP');
        $supernatural_xp = 0;
        $number_of_specialties = 3;

        $character_types = array("Mortal", "Vampire", "Ghoul", "Werewolf", "Wolfblooded", "Mage", "Sleepwalker",
            "Changeling", "Geist", "Changing Breed", 'Psychic', 'Thaumaturge', 'Promethean', 'Hunter', 'Purified',
            'Possessed');
        sort($character_types);

        $skill_list_proper = array("Academics", "Animal Ken", "Athletics", "Brawl", "Computer", "Crafts", "Drive", "Empathy", "Expression", "Firearms", "Intimidation", "Investigation", "Larceny", "Medicine", "Occult", "Persuasion", "Politics", "Science", "Socialize", "Stealth", "Streetwise", "Subterfuge", "Survival", "Weaponry");
        $skill_list_proper_mage = array("Academics", "Animal Ken", "Athletics", "Brawl", "Computer", "Crafts", "Drive", "Empathy", "Expression", "Firearms", "Intimidation", "Investigation", "Larceny", "Medicine", "Occult", "Persuasion", "Politics", "Science", "Socialize", "Stealth", "Streetwise", "Subterfuge", "Survival", "Weaponry", "Rote Specialty");


        $input_class = "normal_input";

        $experience_help = "";
        if ($this->viewOptions['xp_create_mode']) {
            $experience_help = <<<EOQ
<span id="xp_sheet_xp_help_button" class="xp_sheet_help" onMouseOver="showHelp('xp_sheet_xp_help_box', event);") onMouseOut="hideHelp('xp_sheet_xp_help_box');">What's This?</span>
<div id="xp_sheet_xp_help_box" class="xp_sheet_help_box">
  These fields report how much XP you have left to spend on each part of the sheet.
  The general XP field is used when you run out of XP in one of the other fields, say from buying extra attributes or skills.
</div>
EOQ;
        }

        $characterId = 0;
        $character_name = "";
        $show_sheet = "N";
        $view_password = "";
        $hide_icon = "N";

        $city = "";
        $virtue = "";
        $vice = "";
        $splat1 = "";
        $splat1_groups = "";
        $splat2 = "";
        $splat2_groups = "";
        $subsplat = "";
        $icon = 'city.png';
        $sex = "";
        $age = "18+";
        $apparent_age = "18+";
        $is_npc = "N";
        $status = "";

        $concept = "";
        $description = "";
        $equipment_public = "";
        $equipment_hidden = "";
        $public_effects = "";
        $friends = ""; // pack/coterie/whatever
        $helper = ""; // Totem/Familiar/whatever
        $safe_place = "";
        $misc_powers = "";

        $power_trait = 1;
        $willpower_perm = 0;
        $willpower_temp = 0;
        $morality = 7;
        $power_points = 10;
        $maxPowerPoints = 20;
        $average_power_points = 0;
        $power_points_modifier = 0;
        $health = 0;
        $size = 5;
        $defense = 0;
        $initiative_mod = 0;
        $speed = 0;
        $armor = "0/0";
        $wounds_bashing = 0;
        $wounds_lethal = 0;
        $wounds_aggravated = 0;

        $history = "";
        $notes = "";
        $goals = "";

        $current_experience = 0;
        $total_experience = 0;
        $bonus_received = 0;
        $first_login = "";
        $last_login = "";
        $last_st_updated = "";
        $when_last_st_updated = "";
        $gm_notes = "";
        $sheet_updates = "";
        $is_sanctioned = "";
        $asst_sanctioned = "";
        $view_status = "";
        $bonus_attribute = "";

        $attributes = $this->initializeAttributes();
        $skills = $this->initializeSkills();

        // mods for ghouls
        if ($character_type == "Ghoul") {
            $morality = 6;
            $power_points = $this->getPowerByName($attributes, "Stamina")->getPowerLevel();
        }

        $history_edit = 'readonly';
        $powers_edit = 'readonly';
        $goals_edit = 'readonly';
        $notes_edit = 'readonly';

        // test if stats were passed
        if (count($stats) > 0) {
            // set sheet values based on passed stats
            $characterId = $stats['id'];
            $character_name = $stats['character_name'];
            $show_sheet = $stats['show_sheet'];
            $view_password = $stats['view_password'];
            $hide_icon = $stats['hide_icon'];

            $city = $stats['city'];
            $virtue = $stats['virtue'];
            $vice = $stats['vice'];
            $splat1 = $stats['splat1'];
            $splat2 = $stats['splat2'];
            $subsplat = $stats['subsplat'];
            $icon = $stats['icon'];
            $sex = $stats['sex'];
            $age = $stats['age'];
            $apparent_age = $stats['apparent_age'];
            $is_npc = $stats['is_npc'];
            $status = $stats['status'];

            $concept = $stats['concept'];
            $description = $stats['description'];
            $equipment_public = $stats['equipment_public'];
            $equipment_hidden = $stats['equipment_hidden'];
            $public_effects = $stats['public_effects'];
            $friends = $stats['friends']; // pack/coterie/whatever
            $helper = $stats['helper']; // Totem/Familiar/whatever/Regnent
            $safe_place = $stats['safe_place'];
            $misc_powers = $stats['misc_powers'];

            $power_trait = $stats['power_stat'];
            $willpower_perm = $stats['willpower_perm'];
            $willpower_temp = $stats['willpower_temp'];
            $morality = $stats['morality'];
            $power_points = $stats['power_points'];
            $average_power_points = $stats['average_power_points'];
            $power_points_modifier = $stats['power_points_modifier'];
            $health = $stats['health'];
            $size = $stats['size'];
            $defense = $stats['defense'];
            $initiative_mod = $stats['initiative_mod'];
            $speed = $stats['speed'];
            $armor = $stats['armor'];
            $wounds_bashing = $stats['wounds_bashing'];
            $wounds_lethal = $stats['wounds_lethal'];
            $wounds_aggravated = $stats['wounds_agg'];

            $history = $stats['history'];
            $notes = $stats['character_notes'];
            $goals = $stats['goals'];

            $current_experience = $stats['current_experience'];
            $total_experience = $stats['total_experience'];
            $bonus_received = $stats['bonus_received'];
            $last_st_updated = $stats['updated_by_username'];
            $when_last_st_updated = $stats['updated_on'];
            $gm_notes = $stats['gm_notes'];
            $sheet_updates = $stats['sheet_update'];
            $is_sanctioned = $stats['is_sanctioned'];
            $asst_sanctioned = $stats['asst_sanctioned'];
            $bonus_attribute = $stats['bonus_attribute'];

            if ($characterId) {
                $attributes = $this->getPowers($stats['id'], 'Attribute', self::NAMELEVEL, 0);
                $skills = $this->getPowers($stats['id'], 'Skill', self::NAMELEVEL, 0);
                if (!$this->viewOptions['xp_create_mode'] && ($bonus_attribute != '')) {
                    foreach ($attributes as $attribute) {
                        if ($attribute->getPowerName() == $bonus_attribute) {
                            $attribute->setPowerLevel($attribute->getPowerLevel() + 1);
                        }
                    }
                }
            }
        }

        // set page colors based on type of character and supernatural XP
        switch ($character_type) {
            case 'Mortal':
                $this->table_class = "mortal_normal_text";
                break;
            case 'Sleepwalker':
                $this->table_class = "mage_normal_text";
                break;
            case 'Wolfblooded':
                $this->table_class = "werewolf_normal_text";
                break;

            case 'Psychic':
                $this->table_class = "mortal_normal_text";
                break;

            case 'Thaumaturge':
                $this->table_class = "mortal_normal_text";
                $splat1_groups = array("Ceremonial Magician", "Hedge Witch", "Shaman", "Taoist Alchemist", "Vodoun", "Apostle");
                $supernatural_xp = 0;
                break;

            case 'Vampire':
                $this->table_class = "vampire_normal_text";
                $splat1_groups = array("Daeva", "Gangrel", "Mekhet", "Nosferatu", "Ventrue");
                $splat2_groups = array("Carthian", "Circle of the Crone", "Invictus", "Lancea Sanctum", "Ordo Dracul", "Unaligned");
                $supernatural_xp = 20;
                break;

            case 'Werewolf':
                $this->table_class = "werewolf_normal_text";
                $splat1_groups = array("Rahu", "Cahalith", "Elodoth", "Ithaeur", "Irraka", "None");
                $splat2_groups = array("Blood Talons", "Bone Shadows", "Hunters in Darkness", "Iron Masters", "Storm Lords", "Ghost Wolves", "Fire-Touched", "Ivory Claws", "Predator Kings");
                $supernatural_xp = 16;
                $number_of_specialties = 4;
                break;

            case 'Mage':
                $this->table_class = "mage_normal_text";
                $splat1_groups = array("Acanthus", "Mastigos", "Moros", "Obrimos", "Thyrsus");
                $splat2_groups = array("The Adamantine Arrows", "Free Council", "Guardians of the Veil", "The Mysterium", "The Silver Ladder", "Apostate", "Seer of the Throne", "Banisher");
                $supernatural_xp = 75;
                break;

            case 'Ghoul':
                $this->table_class = "ghoul_normal_text";
                $supernatural_xp = 10;
                break;

            case 'Promethean':
                $this->table_class = "promethean_normal_text";
                $splat1_groups = array("Frankenstein", "Galatea", "Osiris", "Tammuz", "Uglan", "Pandoran", "Unfleshed", "Zeka");
                $splat2_groups = array("Aurum", "Cuprum", "Ferrum", "Mercurius", "Stannum", "Centimani", "Aes", "Argentum", "Cobalus", "Plumbum");
                $supernatural_xp = 30;
                break;

            case 'Changeling':
                $this->table_class = "changeling_normal_text";
                $splat1_groups = array("Beast", "Darkling", "Elemental", "Fairest", "Ogre", "Wizened");
                $splat2_groups = array("Spring", "Summer", "Autumn", "Winter", "Courtless");
                $supernatural_xp = 40;
                $number_of_specialties = 4;
                break;

            case 'Hunter':
                $this->table_class = "mortal_normal_text";
                $splat1_groups = array("Academic", "Artist", "Athlete", "Cop", "Criminal", "Detective", "Doctor", "Engineer", "Hacker", "Hit man", "Journalist", "Laborer", "Occultist", "Outdoorsman", "Professional", "Religious Leader", "Scientist", "Socialite", "Soldier", "Technician", "Vagrant");
                $splat2_groups = array("");
                break;

            case 'Geist':
                $this->table_class = "geist_normal_text";
                $splat1_groups = array("Advocate", "Bonepicker", "Celebrant", "Gatekeeper", "Mourner", "Necromancer", "Pilgrim", "Reaper");
                $splat2_groups = array("Forgotten", "Prey", "Silent", "Stricken", "Torn");
                $supernatural_xp = 44;
                $maxPowerPoints = 30;
                break;

            case 'Purified':
                $this->table_class = "mortal_normal_text";
                $supernatural_xp = 52;
                $merit_xp = 38;
                $skill_xp = 141;
                break;

            case 'Possessed':
                $this->table_class = "vampire_normal_text";
                $supernatural_xp = 30;
                break;

            case 'Changing Breed':
                $this->table_class = "mortal_normal_text";
                $splat1_groups = array('Bastet', 'Land Titans', 'Laughing Strangers', 'The Pack', 'Royal Apes', 'Shadow-Beast', 'Spinner-Kin', 'Ursara', 'Wind-Runners', 'Wing-Folk');
                $splat2_groups = array('Den-Warder', 'Heart-Ripper', 'Root-Weaver', 'Sun-Chaser', 'Wind-Dancer');
                $supernatural_xp = 100;
                $number_of_specialties = 4;
                break;

            default:
                $this->table_class = "mortal_normal_text";
                break;
        }

        $show_sheet_table = "";
        if ($this->viewOptions['edit_show_sheet']) {
            $show_sheet_yes_check = ($show_sheet == 'Y') ? "checked" : "";
            $show_sheet_no_check = ($show_sheet == 'N') ? "checked" : "";

            $hide_icon_yes_check = ($hide_icon == 'Y') ? "checked" : "";
            $hide_icon_no_check = ($hide_icon == 'N') ? "checked" : "";

            $show_sheet_table = <<<EOQ
<table class="character-sheet $this->table_class">
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

        // create sheet values
        if ($this->viewOptions['edit_name']) {
            $character_name = <<<EOQ
<input type="text" name="character_name" id="character_name" value="$character_name" size="30" maxlength="30">
EOQ;
        }

        if ($this->viewOptions['edit_vitals']) {
            // edit character type

            $character_type_select = FormHelper::Select(
                ArrayTools::array_valuekeys($character_types),
                'character_type',
                $character_type
            );

            // location
            $locations = array("Savannah", "San Diego", "The City", "Side Game");
            $city = FormHelper::Select(
                ArrayTools::array_valuekeys($locations),
                'city',
                $city
            );

            // sex
            $sexes = array("Male", "Female");
            $sex = FormHelper::Select(
                ArrayTools::array_valuekeys($sexes),
                'sex',
                $sex
            );

            // virtue & vice
            $virtues = array("Charity", "Faith", "Fortitude", "Hope", "Justice", "Prudence", "Temperance");
            $virtue = FormHelper::Select(
                ArrayTools::array_valuekeys($virtues),
                'virtue',
                $virtue
            );
            $vices = array("Envy", "Gluttony", "Greed", "Lust", "Pride", "Sloth", "Wrath");
            $vice = FormHelper::Select(
                ArrayTools::array_valuekeys($vices),
                'vice',
                $vice
            );

            $splat1Options = array();
            $splat2Options = array();
            if ($this->viewOptions['xp_create_mode']) {
                if ($character_type == 'Vampire') {
                    $splat1Options['onChange'] = 'updateBonusAttribute();';
                }
                if ($character_type == 'Mage') {
                    $splat1Options['onChange'] = 'displayBonusDot();';
                    $splat2Options['onChange'] = 'updateXP(' . self::MERIT . ');';
                }
                if ($character_type == 'Werewolf') {
                    $splat1Options['onChange'] = 'displayFreeWerewolfRenown();updateXP(' . self::SUPERNATURAL . ');';
                    $splat2Options['onChange'] = 'displayFreeWerewolfRenown();updateXP(' . self::SUPERNATURAL . ');';
                }
                if ($character_type == "Thaumaturge") {
                    $splat1Options['onChange'] = 'addThaumaturgeDefiningMerit();updateXP(' . self::MERIT . ');';
                }
            }

            if (is_array($splat1_groups)) {
                $splat1 = FormHelper::Select(
                    ArrayTools::array_valuekeys($splat1_groups),
                    'splat1',
                    $splat1,
                    $splat1Options
                );
            }
            if (is_array($splat2_groups)) {
                $splat2 = FormHelper::Select(
                    ArrayTools::array_valuekeys($splat2_groups),
                    'splat2',
                    $splat2,
                    $splat2Options
                );
            }

            $subsplat = <<<EOQ
<input type="text" name="subsplat" id="subsplat" value="$subsplat" size="20" maxlength="30">
EOQ;

            $age = <<<EOQ
<input type="text" name="age" id="age" value="$age" size="3" maxlength="4">
EOQ;

            $apparent_age = <<<EOQ
<input type="text" name="apparent_age" id="apparent_age" value="$apparent_age" size="3" maxlength="4">
EOQ;
        } else {
            // have a hidden form field for character dots
            $character_type_select = <<<EOQ
$character_type
<input type="hidden" name="character_type" id="character_type" value="$character_type">
EOQ;
        }

        if ($this->viewOptions['edit_is_npc']) {
            $is_npc_check = "";
            if ($is_npc == 'Y') {
                $is_npc_check = "checked";
            }

            $is_npc = <<<EOQ
<input type="checkbox" name="is_npc" id="is_npc" value="Y" $is_npc_check>
EOQ;
        }

        if ($this->viewOptions['edit_is_dead']) {
            $statuses = array("Ok", "Imprisoned", "Hospitalized", "Torpored", "Dead");
            $status = FormHelper::Select(
                ArrayTools::array_valuekeys($statuses),
                'status',
                $status
            );
        }

        // concept
        if ($this->viewOptions['edit_concept']) {
            $concept = <<<EOQ
<input type="text" name="concept" id="concept" value="$concept" size="50" maxlength="255">
EOQ;
        }

        // description
        if ($this->viewOptions['edit_description']) {
            // icon
            switch ($this->viewOptions['user_type']) {
                case 'admin':
                case 'head':
                    $icon_query = "SELECT * FROM icons WHERE Admin_Viewable='Y' ORDER BY Icon_Name;";
                    break;
                case 'st':
                case 'asst':
                    $icon_query = "SELECT * FROM icons WHERE GM_Viewable='Y' ORDER BY Icon_Name;";
                    break;
                default:
                    $icon_query = "SELECT * FROM icons WHERE Player_Viewable='Y' ORDER BY Icon_Name;";
                    break;

            }

            $icons = array();
            foreach (Database::getInstance()->query($icon_query)->all() as $icon_detail) {
                $icons[$icon_detail['Icon_ID']] = $icon_detail['Icon_Name'];
            }
            $icon = FormHelper::Select($icons, 'icon', $icon);

            $description = <<<EOQ
<input type="text" name="description" id="description" value="$description" size="50" maxlength="400">
EOQ;
        }

        // equipment
        if ($this->viewOptions['edit_equipment']) {
            $equipment_public = <<<EOQ
<input type="text" name="equipment_public" id="equipment_public" value="$equipment_public" size="50" maxlength="255">
EOQ;

            $equipment_hidden = <<<EOQ
<input type="text" name="equipment_hidden" id="equipment_hidden" value="$equipment_hidden" size="50" maxlength="255">
EOQ;
        }

        if ($this->viewOptions['edit_public_effects']) {
            $public_effects = <<<EOQ
<input type="text" name="public_effects" id="public_effects" value="$public_effects" size="50" maxlength="255">
EOQ;
        }

        if ($this->viewOptions['edit_group']) {
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

        $power_trait_dots = FormHelper::Dots("power_trait", $power_trait, self::POWERTRAIT, $character_type,
            10, $this->viewOptions['edit_perm_traits'], false, $this->viewOptions['xp_create_mode']);
        $willpower_perm_dots = FormHelper::Dots("willpower_perm", $willpower_perm, 0, $character_type, 10,
            $this->viewOptions['edit_perm_traits'], false);
        $willpower_temp_dots = FormHelper::Dots("willpower_temp", $willpower_temp, 0, $character_type, 10,
            $this->viewOptions['edit_temp_traits'], false);
        $morality_dots = FormHelper::Dots("morality", $morality, self::MORALITY, $character_type, 10,
            $this->viewOptions['edit_perm_traits'], false, $this->viewOptions['xp_create_mode']);
        $power_points_dots = FormHelper::Dots("power_points", $power_points, 0, $character_type, $maxPowerPoints,
            $this->viewOptions['edit_temp_traits'], false);
        $health_dots = FormHelper::Dots("health", $health, 0, $character_type, 15,
            $this->viewOptions['edit_perm_traits'], false);

        if ($this->viewOptions['edit_perm_traits']) {
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

        if ($this->viewOptions['edit_temp_traits']) {
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

        $powers = $this->getPowers($characterId, 'Merit', self::NAMENOTE, 5);
        ob_start();
        ?>
        <table class="character-sheet <?php echo $this->table_class; ?>" id="merit_list">
            <tr>
                <th colspan="3">
                    Merits
                    <?php if ($this->viewOptions['edit_powers']): ?>
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
                    self::MERIT, $character_type, $this->max_dots,
                    $this->viewOptions['edit_powers'], false, $this->viewOptions['xp_create_mode']); ?>
                <tr>
                    <td>
                        <?php if ($this->viewOptions['edit_powers']): ?>
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
                        <?php if ($this->viewOptions['edit_powers']): ?>
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

        $powers = $this->getPowers($characterId, 'Flaw', self::NAMENOTE, 1);
        ob_start();
        ?>
        <table class="character-sheet <?php echo $this->table_class; ?>" id="flaw_list">
            <tr>
                <th colspan="1">
                    Flaws
                    <?php if ($this->viewOptions['edit_powers']): ?>
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
                        <?php if ($this->viewOptions['edit_powers']): ?>
                            <label for="flaw<?php echo $i; ?>_name"></label>
                            <input type="text"
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

        $powers = $this->getPowers($characterId, 'Misc', self::NAMENOTE, 1);
        ob_start();
        ?>
        <table class="character-sheet <?php echo $this->table_class; ?>" id="misc_list">
            <tr>
                <th colspan="3">
                    Misc Traits
                    <?php if ($this->viewOptions['edit_powers']): ?>
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
                        <?php if ($this->viewOptions['edit_powers']): ?>
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
                        <?php if ($this->viewOptions['edit_powers']): ?>
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
                        <?php if ($this->viewOptions['edit_powers']): ?>
                            <label for="misc<?php echo $i; ?>"></label>
                            <input type="text" name="misc<?php echo $i; ?>" id="misc<?php echo $i; ?>" size="3"
                                   maxlength="2"
                                   value="<?php echo $power->getPowerLevel(); ?>"/>
                        <?php else: ?>
                            <?php echo $power->getPowerLevel(); ?>
                        <?php endif; ?>
                        <input type="hidden" name="misc<?php echo $i; ?>_id" id="misc<?php echo $i; ?>_id"
                               value="<?php echo $power->getPowerID(); ?>">
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
        <?php
        $characterMiscList = ob_get_clean();

        if ($this->viewOptions['edit_history']) {
            $history_edit = "";
        }

        if ($this->viewOptions['edit_powers']) {
            $powers_edit = '';
        }

        if ($this->viewOptions['edit_goals']) {
            $goals_edit = "";
            $notes_edit = "";
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

        if ($this->viewOptions['show_st_notes']) {
            if (in_array($this->viewOptions['user_type'], array('st', 'head', 'admin'))) {
                $sanc_yes_check = ($is_sanctioned == 'Y') ? "checked" : "";
                $sanc_no_check = ($is_sanctioned == 'N') ? "checked" : "";
                $is_sanctioned = <<<EOQ
Yes: <input type="radio" name="is_sanctioned" value="Y" $sanc_yes_check>
No: <input type="radio" name="is_sanctioned" value="N" $sanc_no_check>
EOQ;
            }

            if ($this->viewOptions['user_type'] == 'asst') {
                $asst_sanc_yes_check = ($asst_sanctioned == 'Y') ? "checked" : "";
                $asst_sanc_no_check = ($asst_sanctioned == 'N') ? "checked" : "";
                $asst_sanctioned = <<<EOQ
Yes: <input type="radio" name="asst_sanctioned" value="Y" $asst_sanc_yes_check>
No: <input type="radio" name="asst_sanctioned" value="N" $asst_sanc_no_check>
EOQ;
            }

            $monthlyBonusXPCap = 5;
            if (in_array($this->viewOptions['user_type'], array('head', 'admin'))) {
                $monthlyBonusXPCap = '99999';
            }


            ob_start();
            ?>
            <table class="character-sheet <?php echo $this->table_class; ?>">
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
                        <label for="xp-spent">XP Spent</label>
                    </td>
                    <td>
                        <input type="text" name="xp_spent" id="xp-spent" value="0"/>
                    </td>
                    <td>
                        <label for="xp-gained">XP Gained</label>
                    </td>
                    <td>
                        <input type="text" name="xp_gained" id="xp-gained" value="0"/>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="xp-note">XP Adjustment Explanation</label>
                    </td>
                    <td colspan="3">
                        <input type="text" name="xp_note" id="xp-note" style="width: 98%;" value=""/>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="vertical-align: top;">

                        <label>
                            Past ST Updates (archival)<br/>
                            <textarea name="sheet_updates" rows="6" cols="40"
                                      readonly><?php echo $sheet_updates; ?></textarea>
                        </label>
                        <br>
                    </td>
                    <td colspan="2">
                        <label>
                            ST Notes: Personal notes and comments about the character. Not a mandatory field.<br>
                            <textarea name="gm_notes" rows="6" cols="40" readonly><?php echo $gm_notes; ?></textarea>
                        </label>
                        <br>

                        <label>
                            Your Notes to add:<br>
                            <textarea name="new_gm_notes" rows="6" cols="40"></textarea>
                        </label>
                    </td>
                </tr>
            </table>
            <?php
            $st_notes_table = ob_get_clean();

        } else {
            ob_start();
            ?>
            <table class="character-sheet <?php echo $this->table_class; ?>">
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

        // put together general pieces
        $xp_row = "";
        if ($this->viewOptions['xp_create_mode']) {
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
                    <input type="text" name="attribute_xp" id="attribute_xp" size="3"
                           value="<?php echo $attribute_xp; ?>"
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
        <table class="character-sheet <?php echo $this->table_class; ?>">
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
                    echo $this->makeBaseStatDots($attributes, 'Intelligence', 'attribute', $attributeIndex++,
                        self::ATTRIBUTE, $character_type, $this->viewOptions['edit_attributes'],
                        $this->viewOptions['calculate_derived'],
                        $this->viewOptions['xp_create_mode'], $this->max_dots);
                    ?>
                </td>
                <td>
                    <b>Strength</b>
                </td>
                <td>
                    <?php
                    echo $this->makeBaseStatDots($attributes, 'Strength', 'attribute', $attributeIndex++,
                        self::ATTRIBUTE, $character_type, $this->viewOptions['edit_attributes'],
                        $this->viewOptions['calculate_derived'],
                        $this->viewOptions['xp_create_mode'], $this->max_dots);
                    ?>
                </td>
                <td>
                    <b>Presence</b>
                </td>
                <td>
                    <?php
                    echo $this->makeBaseStatDots($attributes, 'Presence', 'attribute', $attributeIndex++,
                        self::ATTRIBUTE, $character_type, $this->viewOptions['edit_attributes'],
                        $this->viewOptions['calculate_derived'],
                        $this->viewOptions['xp_create_mode'], $this->max_dots);
                    ?>
                </td>
            </tr>
            <tr>
                <td>
                    <b>Wits</b>
                </td>
                <td>
                    <?php
                    echo $this->makeBaseStatDots($attributes, 'Wits', 'attribute', $attributeIndex++, self::ATTRIBUTE,
                        $character_type, $this->viewOptions['edit_attributes'], $this->viewOptions['calculate_derived'],
                        $this->viewOptions['xp_create_mode'], $this->max_dots);
                    ?>
                </td>
                <td>
                    <b>Dexterity</b>
                </td>
                <td>
                    <?php
                    echo $this->makeBaseStatDots($attributes, 'Dexterity', 'attribute', $attributeIndex++,
                        self::ATTRIBUTE, $character_type, $this->viewOptions['edit_attributes'],
                        $this->viewOptions['calculate_derived'],
                        $this->viewOptions['xp_create_mode'], $this->max_dots);
                    ?>
                </td>
                <td>
                    <b>Manipulation</b>
                </td>
                <td>
                    <?php
                    echo $this->makeBaseStatDots($attributes, 'Manipulation', 'attribute', $attributeIndex++,
                        self::ATTRIBUTE, $character_type, $this->viewOptions['edit_attributes'],
                        $this->viewOptions['calculate_derived'],
                        $this->viewOptions['xp_create_mode'], $this->max_dots);
                    ?>
                </td>
            </tr>
            <tr>
                <td>
                    <b>Resolve</b>
                </td>
                <td>
                    <?php
                    echo $this->makeBaseStatDots($attributes, 'Resolve', 'attribute', $attributeIndex++,
                        self::ATTRIBUTE, $character_type, $this->viewOptions['edit_attributes'],
                        $this->viewOptions['calculate_derived'],
                        $this->viewOptions['xp_create_mode'], $this->max_dots);
                    ?>
                </td>
                <td>
                    <b>Stamina</b>
                </td>
                <td>
                    <?php
                    echo $this->makeBaseStatDots($attributes, 'Stamina', 'attribute', $attributeIndex++,
                        self::ATTRIBUTE, $character_type, $this->viewOptions['edit_attributes'],
                        $this->viewOptions['calculate_derived'],
                        $this->viewOptions['xp_create_mode'], $this->max_dots);
                    ?>
                </td>
                <td>
                    <b>Composure</b>
                </td>
                <td>
                    <?php
                    echo $this->makeBaseStatDots($attributes, 'Composure', 'attribute', $attributeIndex,
                        self::ATTRIBUTE, $character_type, $this->viewOptions['edit_attributes'],
                        $this->viewOptions['calculate_derived'],
                        $this->viewOptions['xp_create_mode'], $this->max_dots);
                    ?>
                </td>
            </tr>
        </table>
        <?php
        $attribute_table = ob_get_clean();

        $powers = $this->getPowers($characterId, 'Specialty', self::NOTENAME, $number_of_specialties);
        $specialtyXpCreateModeClass = '';
        if ($this->viewOptions['xp_create_mode']) {
            $specialtyXpCreateModeClass = ' specialty-skill-update-create';
        }
        if ($character_type == 'Mage') {
            $skill_list_proper = $skill_list_proper_mage;
        }
        ob_start();
        ?>
        <table class="character-sheet <?php echo $this->table_class; ?>" id="specialties_list">
            <tr>
                <th colspan="2">
                    Specialties
                    <?php if ($this->viewOptions['edit_powers']): ?>
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
                        <?php if ($this->viewOptions['edit_skills']): ?>
                            <?php echo FormHelper::Select(
                                ArrayTools::array_valuekeys($skill_list_proper),
                                "skill_spec${i}_note",
                                $power->getPowerNote(),
                                array(
                                    'class' => $input_class . $specialtyXpCreateModeClass,

                                )
                            ); ?>
                        <?php else: ?>
                            <?php echo $power->getPowerNote(); ?>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($this->viewOptions['edit_skills']): ?>
                            <label for="skill_spec<?php echo $i; ?>">
                                <input type="text"
                                       name="skill_spec<?php echo $i; ?>_name"
                                       id="skill-spec<?php echo $i; ?>-name"
                                       value="<?php echo $power->getPowerName(); ?>"
                                       class="<?php echo $specialtyXpCreateModeClass; ?>"
                                    />
                            </label>
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
            <table class="character-sheet <?php echo $this->table_class; ?>">
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
                        echo $this->makeBaseStatDots($skills, 'Academics', 'skill', $skillIndex++, self::SKILL,
                            $character_type,
                            $this->viewOptions['edit_skills'], $this->viewOptions['calculate_derived'],
                            $this->viewOptions['xp_create_mode'], $this->max_dots);
                        ?>
                    </td>
                    <td>
                        Athletics
                    </td>
                    <td>
                        <?php
                        echo $this->makeBaseStatDots($skills, 'Athletics', 'skill', $skillIndex++, self::SKILL,
                            $character_type,
                            $this->viewOptions['edit_skills'], $this->viewOptions['calculate_derived'],
                            $this->viewOptions['xp_create_mode'], $this->max_dots);
                        ?>
                    </td>
                    <td>
                        Animal Ken
                    </td>
                    <td>
                        <?php
                        echo $this->makeBaseStatDots($skills, 'Animal Ken', 'skill', $skillIndex++, self::SKILL,
                            $character_type, $this->viewOptions['edit_skills'], $this->viewOptions['calculate_derived'],
                            $this->viewOptions['xp_create_mode'], $this->max_dots);
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        Computer
                    </td>
                    <td>
                        <?php
                        echo $this->makeBaseStatDots($skills, 'Computer', 'skill', $skillIndex++, self::SKILL,
                            $character_type,
                            $this->viewOptions['edit_skills'], $this->viewOptions['calculate_derived'],
                            $this->viewOptions['xp_create_mode'], $this->max_dots);
                        ?>
                    </td>
                    <td>
                        Brawl
                    </td>
                    <td>
                        <?php
                        echo $this->makeBaseStatDots($skills, 'Brawl', 'skill', $skillIndex++, self::SKILL,
                            $character_type,
                            $this->viewOptions['edit_skills'], $this->viewOptions['calculate_derived'],
                            $this->viewOptions['xp_create_mode'], $this->max_dots);
                        ?>
                    </td>
                    <td>
                        Empathy
                    </td>
                    <td>
                        <?php
                        echo $this->makeBaseStatDots($skills, 'Empathy', 'skill', $skillIndex++, self::SKILL,
                            $character_type,
                            $this->viewOptions['edit_skills'], $this->viewOptions['calculate_derived'],
                            $this->viewOptions['xp_create_mode'], $this->max_dots);
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        Crafts
                    </td>
                    <td>
                        <?php
                        echo $this->makeBaseStatDots($skills, 'Crafts', 'skill', $skillIndex++, self::SKILL,
                            $character_type,
                            $this->viewOptions['edit_skills'], $this->viewOptions['calculate_derived'],
                            $this->viewOptions['xp_create_mode'], $this->max_dots);
                        ?>
                    </td>
                    <td>
                        Drive
                    </td>
                    <td>
                        <?php
                        echo $this->makeBaseStatDots($skills, 'Drive', 'skill', $skillIndex++, self::SKILL,
                            $character_type,
                            $this->viewOptions['edit_skills'], $this->viewOptions['calculate_derived'],
                            $this->viewOptions['xp_create_mode'], $this->max_dots);
                        ?>
                    </td>
                    <td>
                        Expression
                    </td>
                    <td>
                        <?php
                        echo $this->makeBaseStatDots($skills, 'Expression', 'skill', $skillIndex++, self::SKILL,
                            $character_type, $this->viewOptions['edit_skills'], $this->viewOptions['calculate_derived'],
                            $this->viewOptions['xp_create_mode'], $this->max_dots);
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        Investigation
                    </td>
                    <td>
                        <?php
                        echo $this->makeBaseStatDots($skills, 'Investigation', 'skill', $skillIndex++, self::SKILL,
                            $character_type, $this->viewOptions['edit_skills'], $this->viewOptions['calculate_derived'],
                            $this->viewOptions['xp_create_mode'], $this->max_dots);
                        ?>
                    </td>
                    <td>
                        Firearms
                    </td>
                    <td>
                        <?php
                        echo $this->makeBaseStatDots($skills, 'Firearms', 'skill', $skillIndex++, self::SKILL,
                            $character_type,
                            $this->viewOptions['edit_skills'], $this->viewOptions['calculate_derived'],
                            $this->viewOptions['xp_create_mode'], $this->max_dots);
                        ?>
                    </td>
                    <td>
                        Intimidation
                    </td>
                    <td>
                        <?php
                        echo $this->makeBaseStatDots($skills, 'Intimidation', 'skill', $skillIndex++, self::SKILL,
                            $character_type, $this->viewOptions['edit_skills'], $this->viewOptions['calculate_derived'],
                            $this->viewOptions['xp_create_mode'], $this->max_dots);
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        Medicine
                    </td>
                    <td>
                        <?php
                        echo $this->makeBaseStatDots($skills, 'Medicine', 'skill', $skillIndex++, self::SKILL,
                            $character_type,
                            $this->viewOptions['edit_skills'], $this->viewOptions['calculate_derived'],
                            $this->viewOptions['xp_create_mode'], $this->max_dots);
                        ?>
                    </td>
                    <td>
                        Larceny
                    </td>
                    <td>
                        <?php
                        echo $this->makeBaseStatDots($skills, 'Larceny', 'skill', $skillIndex++, self::SKILL,
                            $character_type,
                            $this->viewOptions['edit_skills'], $this->viewOptions['calculate_derived'],
                            $this->viewOptions['xp_create_mode'], $this->max_dots);
                        ?>
                    </td>
                    <td>
                        Persuasion
                    </td>
                    <td>
                        <?php
                        echo $this->makeBaseStatDots($skills, 'Persuasion', 'skill', $skillIndex++, self::SKILL,
                            $character_type, $this->viewOptions['edit_skills'], $this->viewOptions['calculate_derived'],
                            $this->viewOptions['xp_create_mode'], $this->max_dots);
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        Occult
                    </td>
                    <td>
                        <?php
                        echo $this->makeBaseStatDots($skills, 'Occult', 'skill', $skillIndex++, self::SKILL,
                            $character_type,
                            $this->viewOptions['edit_skills'], $this->viewOptions['calculate_derived'],
                            $this->viewOptions['xp_create_mode'], $this->max_dots);
                        ?>
                    </td>
                    <td>
                        Stealth
                    </td>
                    <td>
                        <?php
                        echo $this->makeBaseStatDots($skills, 'Stealth', 'skill', $skillIndex++, self::SKILL,
                            $character_type,
                            $this->viewOptions['edit_skills'], $this->viewOptions['calculate_derived'],
                            $this->viewOptions['xp_create_mode'], $this->max_dots);
                        ?>
                    </td>
                    <td>
                        Socialize
                    </td>
                    <td>
                        <?php
                        echo $this->makeBaseStatDots($skills, 'Socialize', 'skill', $skillIndex++, self::SKILL,
                            $character_type,
                            $this->viewOptions['edit_skills'], $this->viewOptions['calculate_derived'],
                            $this->viewOptions['xp_create_mode'], $this->max_dots);
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        Politics
                    </td>
                    <td>
                        <?php
                        echo $this->makeBaseStatDots($skills, 'Politics', 'skill', $skillIndex++, self::SKILL,
                            $character_type,
                            $this->viewOptions['edit_skills'], $this->viewOptions['calculate_derived'],
                            $this->viewOptions['xp_create_mode'], $this->max_dots);
                        ?>
                    </td>
                    <td>
                        Survival
                    </td>
                    <td>
                        <?php
                        echo $this->makeBaseStatDots($skills, 'Survival', 'skill', $skillIndex++, self::SKILL,
                            $character_type,
                            $this->viewOptions['edit_skills'], $this->viewOptions['calculate_derived'],
                            $this->viewOptions['xp_create_mode'], $this->max_dots);
                        ?>
                    </td>
                    <td>
                        Streetwise
                    </td>
                    <td>
                        <?php
                        echo $this->makeBaseStatDots($skills, 'Streetwise', 'skill', $skillIndex++, self::SKILL,
                            $character_type, $this->viewOptions['edit_skills'], $this->viewOptions['calculate_derived'],
                            $this->viewOptions['xp_create_mode'], $this->max_dots);
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        Science
                    </td>
                    <td>
                        <?php
                        echo $this->makeBaseStatDots($skills, 'Science', 'skill', $skillIndex++, self::SKILL,
                            $character_type,
                            $this->viewOptions['edit_skills'], $this->viewOptions['calculate_derived'],
                            $this->viewOptions['xp_create_mode'], $this->max_dots);
                        ?>
                    </td>
                    <td>
                        Weaponry
                    </td>
                    <td>
                        <?php
                        echo $this->makeBaseStatDots($skills, 'Weaponry', 'skill', $skillIndex++, self::SKILL,
                            $character_type,
                            $this->viewOptions['edit_skills'], $this->viewOptions['calculate_derived'],
                            $this->viewOptions['xp_create_mode'], $this->max_dots);
                        ?>
                    </td>
                    <td>
                        Subterfuge
                    </td>
                    <td>
                        <?php
                        echo $this->makeBaseStatDots($skills, 'Subterfuge', 'skill', $skillIndex, self::SKILL,
                            $character_type,
                            $this->viewOptions['edit_skills'], $this->viewOptions['calculate_derived'],
                            $this->viewOptions['xp_create_mode'], $this->max_dots);
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
        <table class="character-sheet <?php echo $this->table_class; ?>">
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
                          style="width:100%" <?php echo $powers_edit; ?>><?php echo $misc_powers; ?></textarea>
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

        switch ($character_type) {
            case 'Mortal':
            case 'Wolfblooded':
            case 'Sleepwalker':
                $mortal = new Mortal();
                return $mortal->render($this, $character_name, $character_type_select, $city, $sex, $virtue, $vice,
                    $icon, $age, $is_npc, $status, $concept, $description, $equipment_public, $equipment_hidden,
                    $public_effects, $safe_place, $character_merit_list, $character_flaw_list, $characterMiscList,
                    $health_dots, $size, $wounds_bashing, $wounds_lethal, $wounds_aggravated, $defense, $morality_dots,
                    $initiative_mod, $willpower_perm_dots, $speed, $willpower_temp_dots, $armor, $st_notes_table,
                    $history_table, $skill_table, $attribute_table, $show_sheet_table);
                break;

            case 'Psychic':
                $psychic = new Psychic();
                return $psychic->render($this, $character_name, $character_type_select, $city, $sex, $virtue,
                    $vice, $icon, $age, $is_npc, $status, $concept, $description, $equipment_public, $equipment_hidden,
                    $public_effects, $safe_place, $character_merit_list, $character_flaw_list, $characterMiscList,
                    $health_dots, $size, $wounds_bashing, $wounds_lethal, $wounds_aggravated, $defense, $morality_dots,
                    $initiative_mod, $willpower_perm_dots, $speed, $willpower_temp_dots, $armor, $st_notes_table,
                    $history_table, $skill_table, $attribute_table, $show_sheet_table);
                break;

            case 'Thaumaturge':
                $thaumaturge = new Thaumaturge();
                return $thaumaturge->render($this, $character_name, $character_type_select, $city, $sex, $virtue,
                    $vice, $icon, $age, $is_npc, $status, $concept, $description, $equipment_public, $equipment_hidden,
                    $public_effects, $safe_place, $character_merit_list, $character_flaw_list, $characterMiscList,
                    $health_dots, $size, $wounds_bashing, $wounds_lethal, $wounds_aggravated, $defense, $morality_dots,
                    $initiative_mod, $willpower_perm_dots, $speed, $willpower_temp_dots, $armor, $st_notes_table,
                    $history_table, $skill_table, $attribute_table, $show_sheet_table, $splat1, $friends);
                break;

            case 'Werewolf':
                $renderer = new Werewolf();
                return $renderer->render($this, $character_name, $character_type_select, $city, $sex, $virtue,
                    $vice, $icon, $age, $is_npc, $status, $concept, $description, $equipment_public, $equipment_hidden,
                    $public_effects, $safe_place, $character_merit_list, $character_flaw_list, $characterMiscList,
                    $health_dots, $size, $wounds_bashing, $wounds_lethal, $wounds_aggravated, $defense, $morality_dots,
                    $initiative_mod, $willpower_perm_dots, $speed, $willpower_temp_dots, $armor, $st_notes_table,
                    $history_table, $skill_table, $attribute_table, $show_sheet_table, $splat1, $subsplat, $splat2,
                    $friends, $helper, $power_points_dots, $power_trait_dots);
                break;

            case 'Vampire':
                $renderer = new Vampire();
                return $renderer->render($this, $character_name, $character_type_select, $city, $sex, $virtue,
                    $vice, $icon, $age, $is_npc, $status, $concept, $description, $equipment_public, $equipment_hidden,
                    $public_effects, $safe_place, $character_merit_list, $character_flaw_list, $characterMiscList,
                    $health_dots, $size, $wounds_bashing, $wounds_lethal, $wounds_aggravated, $defense, $morality_dots,
                    $initiative_mod, $willpower_perm_dots, $speed, $willpower_temp_dots, $armor, $st_notes_table,
                    $history_table, $skill_table, $attribute_table, $show_sheet_table, $splat1, $subsplat, $splat2,
                    $friends, $power_points_dots, $power_trait_dots, $apparent_age, $average_power_points,
                    $power_points_modifier);
                break;

            case 'Mage':
                $renderer = new Mage();
                return $renderer->render($this, $character_name, $character_type_select, $city, $sex, $virtue,
                    $vice, $icon, $age, $is_npc, $status, $concept, $description, $equipment_public, $equipment_hidden,
                    $public_effects, $safe_place, $character_merit_list, $character_flaw_list, $characterMiscList,
                    $health_dots, $size, $wounds_bashing, $wounds_lethal, $wounds_aggravated, $defense, $morality_dots,
                    $initiative_mod, $willpower_perm_dots, $speed, $willpower_temp_dots, $armor, $st_notes_table,
                    $history_table, $skill_table, $attribute_table, $show_sheet_table, $splat1, $subsplat, $splat2,
                    $friends, $helper, $power_points_dots, $power_trait_dots);
                break;

            case 'Ghoul':
                $ghoul = new Ghoul();
                return $ghoul->render($this, $character_name, $character_type_select, $city, $sex, $virtue, $vice,
                    $icon, $age, $is_npc, $status, $concept, $description, $equipment_public, $equipment_hidden,
                    $public_effects, $safe_place, $character_merit_list, $character_flaw_list, $characterMiscList,
                    $health_dots, $size, $wounds_bashing, $wounds_lethal, $wounds_aggravated, $defense, $morality_dots,
                    $initiative_mod, $willpower_perm_dots, $speed, $willpower_temp_dots, $armor, $st_notes_table,
                    $history_table, $skill_table, $attribute_table, $show_sheet_table, $friends, $apparent_age,
                    $power_points_dots);
                break;

            case 'Promethean':
                $promethean = new Promethean();
                return $promethean->render($this, $character_name, $character_type_select, $city, $sex, $virtue,
                    $vice, $icon, $age, $is_npc, $status, $concept, $description, $equipment_public, $equipment_hidden,
                    $public_effects, $safe_place, $character_merit_list, $character_flaw_list, $characterMiscList,
                    $health_dots, $size, $wounds_bashing, $wounds_lethal, $wounds_aggravated, $defense, $morality_dots,
                    $initiative_mod, $willpower_perm_dots, $speed, $willpower_temp_dots, $armor, $st_notes_table,
                    $history_table, $skill_table, $attribute_table, $show_sheet_table, $splat1, $subsplat, $splat2,
                    $friends, $power_points_dots, $power_trait_dots);
                break;

            case 'Changeling':
                $changeling = new Changeling();
                return $changeling->render($this, $character_name, $character_type_select, $city, $sex, $virtue,
                    $vice,
                    $icon, $age, $is_npc, $status, $concept, $description, $equipment_public, $equipment_hidden,
                    $public_effects, $safe_place, $character_merit_list, $character_flaw_list,
                    $characterMiscList, $health_dots, $size, $wounds_bashing, $wounds_lethal, $wounds_aggravated,
                    $defense, $morality_dots, $initiative_mod, $willpower_perm_dots, $speed,
                    $willpower_temp_dots, $armor, $st_notes_table, $history_table, $skill_table,
                    $attribute_table, $show_sheet_table, $splat1, $subsplat, $splat2, $friends,
                    $power_points_dots, $power_trait_dots, $apparent_age);
                break;

            case 'Hunter':
                $hunter = new Hunter();
                return $hunter->render($this, $character_name, $character_type_select, $city, $sex, $virtue, $vice,
                    $icon, $age, $is_npc, $status, $concept, $description, $equipment_public, $equipment_hidden,
                    $public_effects, $safe_place, $character_merit_list, $character_flaw_list, $characterMiscList,
                    $health_dots, $size, $wounds_bashing, $wounds_lethal, $wounds_aggravated, $defense, $morality_dots,
                    $initiative_mod, $willpower_perm_dots, $speed, $willpower_temp_dots, $armor, $st_notes_table,
                    $history_table, $skill_table, $attribute_table, $show_sheet_table, $splat1, $subsplat, $friends);
                break;

            case 'Geist':
                $geist = new Geist();
                return $geist->render($this, $character_name, $character_type_select, $city, $sex, $virtue, $vice,
                    $icon, $age, $is_npc, $status, $concept, $description, $equipment_public, $equipment_hidden,
                    $public_effects, $safe_place, $character_merit_list, $character_flaw_list, $characterMiscList,
                    $health_dots, $size, $wounds_bashing, $wounds_lethal, $wounds_aggravated, $defense, $morality_dots,
                    $initiative_mod, $willpower_perm_dots, $speed, $willpower_temp_dots, $armor, $st_notes_table,
                    $history_table, $skill_table, $attribute_table, $show_sheet_table, $splat1, $splat2, $friends,
                    $power_trait_dots, $power_points_dots);
                break;

            case 'Purified':
                $purified = new Purified();
                return $purified->render($this, $character_name, $character_type_select, $city, $sex, $virtue,
                    $vice, $icon, $age, $is_npc, $status, $concept, $description, $equipment_public, $equipment_hidden,
                    $public_effects, $safe_place, $character_merit_list, $character_flaw_list, $characterMiscList,
                    $health_dots, $size, $wounds_bashing, $wounds_lethal, $wounds_aggravated, $defense, $morality_dots,
                    $initiative_mod, $willpower_perm_dots, $speed, $willpower_temp_dots, $armor, $st_notes_table,
                    $history_table, $skill_table, $attribute_table, $show_sheet_table, $power_trait_dots,
                    $power_points_dots);
                break;

            case 'Possessed':
                $possessed = new Possessed();
                return $possessed->render($this, $character_name, $character_type_select, $city, $sex, $virtue,
                    $vice, $icon, $age, $is_npc, $status, $concept, $description, $equipment_public, $equipment_hidden,
                    $public_effects, $safe_place, $character_merit_list, $character_flaw_list, $characterMiscList,
                    $health_dots, $size, $wounds_bashing, $wounds_lethal, $wounds_aggravated, $defense, $morality_dots,
                    $initiative_mod, $willpower_perm_dots, $speed, $willpower_temp_dots, $armor, $st_notes_table,
                    $history_table, $skill_table, $attribute_table, $show_sheet_table, $apparent_age, $power_trait_dots,
                    $power_points_dots);
                break;

            case 'Changing Breed':
                $changingBreed = new ChangingBreed();
                return $changingBreed->render($this, $character_name, $character_type_select, $city, $sex, $virtue,
                    $vice, $icon, $age, $is_npc, $status, $concept, $description, $equipment_public, $equipment_hidden,
                    $public_effects, $safe_place, $character_merit_list, $character_flaw_list, $characterMiscList,
                    $health_dots, $size, $wounds_bashing, $wounds_lethal, $wounds_aggravated, $defense, $morality_dots,
                    $initiative_mod, $willpower_perm_dots, $speed, $willpower_temp_dots, $armor, $st_notes_table,
                    $history_table, $skill_table, $attribute_table, $show_sheet_table, $splat1, $splat2, $subsplat,
                    $apparent_age, $friends, $power_trait_dots, $power_points_dots);
                break;

            default:
                return "Not implemented yet.  $character_type_select<br>";
                break;
        }
    }

    private function checkEditMode()
    {
        $mayEdit = false;
        foreach ($this->viewOptions as $value) {
            if (is_bool($value)) {
                $mayEdit |= $value;
            }
        }
        return $mayEdit;
    }

    private function initializeAttributes()
    {
        $attribute_list = array("strength", "dexterity", "stamina", "presence", "manipulation", "composure", "intelligence", "wits", "resolve");
        $attributes = array();
        foreach ($attribute_list as $attribute) {
            $power = new Power();
            $power->setPowerLevel(1);
            $power->setPowerName(ucfirst($attribute));
            $attributes[] = $power;
        }

        return $attributes;
    }

    private function initializeSkills()
    {
        $skill_list = array("academics", "computer", "crafts", "investigation", "medicine", "occult", "politics", "science", "athletics", "brawl", "drive", "firearms", "larceny", "stealth", "survival", "weaponry", "animal ken", "empathy", "expression", "intimidation", "persuasion", "socialize", "streetwise", "subterfuge");
        $skills = array();
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
     * @return Power
     */
    private function getPowerByName($powers, $name)
    {
        foreach ($powers as $power) {
            if (strtolower($power->getPowerName()) == strtolower($name)) {
                return $power;
            }
        }

        return null;
    }

    /**
     * @param $characterId
     * @param $powerType
     * @param $sort
     * @param $minCount
     * @return Power[]
     */
    public function getPowers($characterId, $powerType, $sort, $minCount)
    {
        $power_list = array();
        if (($characterId !== null) && ($characterId !== 0)) {
            switch ($sort) {
                case self::NAMELEVEL:
                    $order_by = "power_name, power_level";
                    break;
                case self::NOTELEVEL:
                    $order_by = "power_note, power_level";
                    break;
                case self::NOTENAME:
                    $order_by = "power_note, power_name";
                    break;
                case self::NAMENOTE:
                    $order_by = "power_name, power_note";
                    break;
                default:
                    $order_by = "power_name, power_level";
                    break;
            }

            $repo = RepositoryManager::GetRepository('classes\character\data\CharacterPower');
            /* @var CharacterPowerRepository $repo */

            foreach ($repo->ListPowersForCharacter($characterId, $powerType, $order_by) as $detail) {
                $power = new Power();

                $power->setPowerName($detail['power_name']);
                $power->setPowerNote($detail['power_note']);
                $power->setPowerLevel($detail['power_level']);
                $power->setPowerID($detail['id']);

                $power_list[] = $power;
            }
        } else {
            for ($i = 0; $i < $minCount; $i++) {
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

    private function makeBaseStatDots($powers, $powerName, $powerType, $position, $element_type, $character_type, $edit,
                                      $calculate_derived, $edit_xp, $max_dots)
    {
        $power = $this->GetPowerByName($powers, $powerName);
        $output = FormHelper::Hidden($powerType . $position . '_id', $power->getPowerID());
        $output .= FormHelper::Hidden($powerType . $position . '_name', $power->getPowerName());
        $output .= FormHelper::Dots($powerType . $position, $power->getPowerLevel(), $element_type, $character_type,
            $max_dots, $edit, $calculate_derived, $edit_xp);

        return $output;
    }

    /**
     * @param $characterId
     * @return Power[]
     */
    public function getRenownsRituals($characterId)
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

        if ($characterId) {
            $repo = RepositoryManager::GetRepository('classes\Character\Data\CharacterPower');
            /* @var CharacterPowerRepository $repo */

            foreach ($repo->ListPowersForCharacter($characterId, 'Renown', 'power_name') as $detail) {
                $renown = new Power();
                $renown->setPowerName($detail["power_name"]);
                $renown->setPowerLevel($detail["power_level"]);
                $renown->setPowerID($detail["id"]);
                $renown_name = strtolower($detail["power_name"]);

                $renown_list[$renown_name] = $renown;
            }

            foreach ($repo->ListPowersForCharacter($characterId, 'Rituals', 'power_name') as $detail) {
                $renown = new Power();
                $renown->setPowerName($detail["power_name"]);
                $renown->setPowerLevel($detail["power_level"]);
                $renown->setPowerID($detail["id"]);

                $renown_list["rituals"] = $renown;
            }
        }

        return $renown_list;
    }

    public function updateSheet($stats, $options, $userdata)
    {
        if (!is_array($stats)) {
            $stats = array();
        }
        $this->viewOptions = array_merge($this->viewOptions, $options);
        $this->viewOptions['allow_edits'] = $this->checkEditMode();
        $this->stats = $stats;

        $characterRepository = new CharacterRepository();
        $characterRepository->startTransaction();
        if ($characterRepository->isNameInUse($stats['character_name'], $stats['character_id'])) {
            return 'That character name is already in use.';
        }

        // start to process
        // attempt to process character
        $character = $characterRepository->getById($stats['character_id']);
        /* @var Character $character */

        if ($this->viewOptions['edit_show_sheet']) {
            $character->ShowSheet = $stats['show_sheet'];
            $character->ViewPassword = $stats['view_password'];
            $character->HideIcon = $stats['hide_icon'];
        }
        if ($this->viewOptions['edit_name']) {
            $character->CharacterName = htmlspecialchars($stats['character_name']);
            if (!$character->CharacterName) {
                $character->CharacterName = 'Character ' . mt_rand(9999999, 100000000);
            }
        }
        if ($this->viewOptions['edit_vitals']) {
            $character->CharacterType = $stats['character_type'];
            $character->City = $stats['city'];
            $character->Age = $stats['age'];
            $character->ApparentAge = $stats['apparent_age'];
            $character->Sex = $stats['sex'];
            $character->Virtue = $stats['virtue'];
            $character->Vice = $stats['vice'];
            $character->Splat1 = $stats['splat1'];
            $character->Splat2 = $stats['splat2'];
            $character->Subsplat = htmlspecialchars($stats['subsplat']);
        }
        if ($this->viewOptions['edit_is_npc']) {
            $character->IsNpc = isset($stats['is_npc']) ? 'Y' : 'N';
        }
        if ($this->viewOptions['edit_is_dead']) {
            $character->Status = $stats['status'];
        }
        if ($this->viewOptions['edit_concept']) {
            $character->Concept = $stats['concept'];
        }
        if ($this->viewOptions['edit_description']) {
            $character->Description = $stats['description'];
            $character->Icon = $stats['icon'];
        }
        if ($this->viewOptions['edit_equipment']) {
            $character->EquipmentPublic = $stats['equipment_public'];
            $character->EquipmentHidden = $stats['equipment_hidden'];
        }
        if ($this->viewOptions['edit_public_effects']) {
            $character->PublicEffects = $stats['public_effects'];
        }
        if ($this->viewOptions['edit_group']) {
            $character->SafePlace = $stats['safe_place'];
            $character->Friends = $stats['friends'];
            $character->Helper = $stats['friends'];
        }
        if ($this->viewOptions['edit_perm_traits']) {
            $character->PowerStat = $stats['power_trait'];
            $character->WillpowerPerm = $stats['willpower_perm'];
            $character->Morality = $stats['morality'];
            $character->Size = $stats['size'];
            $character->Speed = $stats['speed'];
            $character->InitiativeMod = $stats['initiative_mod'];
            $character->Defense = $stats['defense'];
            $character->Armor = $stats['armor'];
            $character->Health = $stats['health'];
            $character->PowerPointsModifier = $stats['power_points_modifier'];
            $character->BonusAttribute = $stats['bonus_attribute'];
        }
        if ($this->viewOptions['edit_temp_traits']) {
            $character->PowerPoints = $stats['power_points'];
            $character->WoundsAgg = $stats['wounds_agg'];
            $character->WoundsLethal = $stats['wounds_lethal'];
            $character->WoundsBashing = $stats['wounds_bashing'];
            $character->WillpowerTemp = $stats['willpower_temp'];
        }
        if ($this->viewOptions['edit_history']) {
            $character->History = htmlspecialchars($stats['history']);
        }
        if ($this->viewOptions['edit_powers']) {
            $character->MiscPowers = $stats['misc_powers'];
        }
        if ($this->viewOptions['edit_goals']) {
            $character->CharacterNotes = $stats['notes'];
            $character->Goals = $stats['goals'];
        }

        if (!$character->Id) {
            $character->UserId = $userdata['user_id'];
            $character->UpdatedOn = date('Y-m-d H:i:s');
        }

        if ($this->viewOptions['show_st_notes']) {
            switch ($this->viewOptions['user_type']) {
                case 'player':
                    // something bad has happened here
                    break;
                case 'asst':
                    $character->AsstSanctioned = $stats['asst_sanctioned'];
                    break;
                case 'st':
                case 'head':
                case 'admin':
                    $character->IsSanctioned = $stats['is_sanctioned'];
                    break;
            }
            $character->UpdatedById = $userdata['user_id'];
            $character->UpdatedOn = date('Y-m-d H:i:s');

            if ($this->viewOptions['edit_experience']) {
                if ($stats['xp_spent'] > 0) {
                    $character->CurrentExperience -= $stats['xp_spent'];
                }
                if ($stats['xp_gained'] > 0) {
                    $character->CurrentExperience += $stats['xp_gained'];
                    $character->TotalExperience += $stats['xp_gained'];
                    $character->BonusReceived += $stats['xp_gained'];
                }
            }

            if ($stats['new_gm_notes']) {
                $short_now = date('Y-m-d');
                $newNote = <<<EOQ
\n$stats[gm_notes]
$stats[new_gm_notes]
$userdata[username] on $short_now\n
EOQ;
                $character->GmNotes .= htmlspecialchars($newNote);
            }
        }

        if (!$character->Description) {
            $character->Description = 'I need a description.';
        }

        // check for bonus dot from sanctioning
        if (($stats['is_sanctioned'] != '') && ($stats['bonus_attribute'] != '')) {
            $this->increaseBonusAttribute($stats, $character);
            $character->BonusAttribute = '';
        }

        $characterRepository->save($character);
        if ($this->viewOptions['edit_attributes']) {
            $this->saveCharacterPower('Attribute', 'attribute', $stats, $character->Id);
        }
        if ($this->viewOptions['edit_skills']) {
            $this->saveCharacterPower('Skill', 'skill', $stats, $character->Id);
            $this->saveCharacterPower('Specialty', 'skill_spec', $stats, $character->Id);
        }

        if ($this->viewOptions['edit_powers']) {
            $this->saveCharacterPower('Merit', 'merit', $stats, $character->Id);
            $this->saveCharacterPower('Flaw', 'flaw', $stats, $character->Id);
            $this->saveCharacterPower('Misc', 'misc', $stats, $character->Id);

            $powers = array();
            switch ($character->CharacterType) {
                case "Mortal":
                    break;
                case "Vampire":
                case "Ghoul":
                case "Possessed":
                    $powers = array(
                        'ICDisc' => 'icdisc',
                        'OOCDisc' => 'oocdisc',
                        'Devotion' => 'devotion'
                    );
                    break;
                case "Werewolf":
                    $powers = array(
                        'AffGift' => 'affgift',
                        'NonAffGift' => 'nonaffgift',
                        'Ritual' => 'ritual'
                    );

                    $this->saveRitualsRenown($stats, $character->Id);
                    break;
                case "Mage":
                    $powers = array(
                        'RulingArcana' => 'rulingarcana',
                        'CommonArcana' => 'commonarcana',
                        'InferiorArcana' => 'inferiorarcana',
                        'Rote' => 'rote'
                    );
                    break;
                case "Psychic":
                    $powers = array(
                        'PsychicMerit' => 'psychicmerit'
                    );
                    break;
                case "Thaumaturge":
                    $powers = array(
                        'ThaumaturgeMerit' => 'thaumaturgemerit'
                    );
                    break;
                case "Promethean":
                    $powers = array(
                        'Bestowment' => 'bestowment',
                        'AffTrans' => 'afftrans',
                        'NonAffTrans' => 'nonafftrans',
                    );
                    break;
                case "Changeling":
                    $powers = array(
                        'AffContract' => 'affcont',
                        'NonAffContract' => 'nonaffcont',
                        'GoblinContract' => 'gobcont'
                    );
                    break;
                case "Hunter":
                    $powers = array(
                        'Endowment' => 'endowment',
                        'Tactics' => 'tactic'
                    );
                    break;
                case "Geist":
                    $powers = array(
                        'Key' => 'key',
                        'Manifestation' => 'manifestation',
                        'Ceremonies' => 'ceremony'
                    );
                    break;
                case "Purified":
                    $powers = array(
                        'Numina' => 'numina',
                        'Siddhi' => 'siddhi',
                        'NonAffTrans' => 'nonafftrans',
                    );
                    break;
                case "Changing Breed":
                    $powers = array(
                        'AffGift' => 'affgift',
                        'Aspect' => 'aspect',
                    );
                    $this->saveRitualsRenown($stats, $character->Id);
                    break;
                default:
                    // do  nothing
            }

            foreach ($powers as $key => $value) {
                $this->saveCharacterPower($key, $value, $stats, $character->Id);
            }

        }

        $characterRepository->commitTransaction();
        return '';
    }

    private function increaseBonusAttribute(&$stats, Character $character)
    {
        for ($i = 0; $i < 9; $i++) {
            if (strtolower($stats['attribute' . $i . '_name']) == strtolower($character->BonusAttribute)) {
                $stats['attribute' . $i]++;
                break;
            }
        }
    }

    private function saveCharacterPower($powerType, $fieldName, $stats, $characterId)
    {
        $i = 0;
        while (isset($stats["${fieldName}${i}_name"])) {
            $name = htmlspecialchars($stats["${fieldName}${i}_name"]);
            $note = htmlspecialchars($stats["${fieldName}${i}_note"]);
            $id = (int)$stats["${fieldName}${i}_id"];
            $level = (int)$stats["${fieldName}$i"];

            $characterPowerRepository = RepositoryManager::GetRepository('classes\character\data\CharacterPower');
            /* @var CharacterPowerRepository $characterPowerRepository */

            if ($name != "") {
                if ($id) {
                    $characterPower = $characterPowerRepository->getById($id);
                } else {
                    $characterPower = new CharacterPower();
                }

                $characterPower->CharacterId = $characterId;
                $characterPower->PowerType = $powerType;
                $characterPower->PowerName = $name;
                $characterPower->PowerNote = $note;
                $characterPower->PowerLevel = $level;
                $characterPower->Id = $id;
                $characterPowerRepository->save($characterPower);
            } else {
                // check to see if we delete
                if ($id) {
                    $characterPowerRepository->delete($id);
                }
            }
            $i++;
        }
    }

    private function saveRitualsRenown($stats, $characterId)
    {
        $renowns = array("purity", "glory", "honor", "wisdom", "cunning");
        $characterPowerRepository = RepositoryManager::GetRepository('classes\character\data\CharacterPower');
        /* @var CharacterPowerRepository $characterPowerRepository */

        for ($i = 0; $i < 5; $i++) {
            $renownName = $renowns[$i];
            $renownLevel = $stats[$renowns[$i]] + 0;
            $renownId = $stats[$renowns[$i] . "_id"] + 0;

            if ($renownId) {
                // update
                $characterPower = $characterPowerRepository->getById($renownId);
                /* @var CharacterPower $characterPower */
                $characterPower->PowerLevel = $renownLevel;
            } else {
                $characterPower = new CharacterPower();
                $characterPower->PowerType = 'Renown';
                $characterPower->PowerName = $renownName;
                $characterPower->PowerLevel = $renownLevel;
                $characterPower->CharacterId = $characterId;
            }

            $characterPowerRepository->save($characterPower);
        }

        if (isset($stats['rituals'])) {
            $ritualsId = $stats["rituals_id"] + 0;
            $ritualLevel = $stats["rituals"] + 0;
            if ($ritualsId) {
                $characterPower = $characterPowerRepository->getById($ritualsId);
                /* @var CharacterPower $characterPower */
                $characterPower->PowerLevel = $ritualLevel;
            } else {
                $characterPower = new CharacterPower();
                $characterPower->PowerType = 'Rituals';
                $characterPower->PowerName = 'Rituals';
                $characterPower->PowerLevel = $ritualLevel;
                $characterPower->CharacterId = $characterId;
            }

            $characterPowerRepository->save($characterPower);
        }
    }
}
