<?php
/**
 * Created by PhpStorm.
 * User: jvandenberg
 * Date: 9/6/2015
 * Time: 7:29 PM
 */

namespace classes\character\sheet;


use classes\character\repository\CharacterPowerRepository;
use classes\core\helpers\Configuration;
use classes\core\helpers\FormHelper;
use classes\core\repository\Database;
use classes\core\repository\RepositoryManager;
use classes\utility\ArrayTools;
use Power;

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
            "Changeling", "Geist", "Changing Breed", 'Psychic', 'Thaumaturge', 'Promethean', 'Hunter');
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

        $location = "";
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
        $url = "";
        $equipment_public = "";
        $equipment_hidden = "";
        $public_effects = "";
        $friends = ""; // pack/coterie/whatever
        $helper = ""; // Totem/Familiar/whatever
        $safe_place = "";
        $exit_line = "";
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

        $history_edit = "readonly";
        $goals_edit = "readonly";
        $notes_edit = "readonly";

        // test if stats were passed
        if (count($stats) > 0) {
            // set sheet values based on passed stats
            $characterId = $stats['id'];
            $character_name = $stats['character_name'];
            $show_sheet = $stats['show_sheet'];
            $view_password = $stats['view_password'];
            $hide_icon = $stats['hide_icon'];

            $location = $stats['city'];
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
            case 'Wolfblood':
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
                $character_type,
                array(
                    'onChange' => '"changeSheet(window.document.character_sheet.character_type.value)";',
                    'id' => 'character_type'
                )
            );

            // location
            $locations = array("Savannah", "San Diego", "The City", "Side Game");
            $location = FormHelper::Select(
                ArrayTools::array_valuekeys($locations),
                'location',
                $location
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
                    $splat1Options['onChange'] = '"updateBonusAttribute();"';
                }
                if ($character_type == 'Mage') {
                    $splat1Options['onChange'] = '"displayBonusDot();"';
                    $splat2Options['onChange'] = '"updateXP(' . self::MERIT . ');" ';
                }
                if ($character_type == 'Werewolf') {
                    $splat1Options['onChange'] = '"displayFreeWerewolfRenown();updateXP(' . self::SUPERNATURAL . ');" ';
                    $splat2Options['onChange'] = '"displayFreeWerewolfRenown();updateXP(' . self::SUPERNATURAL . ');" ';
                }
                if ($character_type == "Thaumaturge") {
                    $splat1Options['onChange'] = '"addThaumaturgeDefiningMerit();updateXP(' . self::MERIT . ');" ';
                }
            }

            if(is_array($splat1_groups)) {
                $splat1 = FormHelper::Select(
                    ArrayTools::array_valuekeys($splat1_groups),
                    'splat1',
                    $splat1,
                    $splat1Options
                );
            }
            if(is_array($splat2_groups)) {
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
            $status = buildSelect($status, $statuses, $statuses, "status");
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
                    $icon_query = "SELECT * FROM icons WHERE Admin_Viewable='Y' ORDER BY Icon_Name;";
                    break;
                case 'st':
                    $icon_query = "SELECT * FROM icons WHERE GM_Viewable='Y' ORDER BY Icon_Name;";
                    break;
                default:
                    $icon_query = "SELECT * FROM icons WHERE Player_Viewable='Y' ORDER BY Icon_Name;";
                    break;

            }

            $icons = array();
            foreach (Database::GetInstance()->Query($icon_query)->All() as $icon_detail) {
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

                        <label for="misc<?php echo $i; ?>"></label>
                        <input type="text" name="misc<?php echo $i; ?>" id="misc<?php echo $i; ?>" size="3"
                               maxlength="2"
                               value="<?php echo $power->getPowerLevel(); ?>"/>
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
                    echo MakeBaseStatDots($attributes, 'Intelligence', 'attribute', $attributeIndex++,
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
                    echo MakeBaseStatDots($attributes, 'Strength', 'attribute', $attributeIndex++,
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
                    echo MakeBaseStatDots($attributes, 'Presence', 'attribute', $attributeIndex++,
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
                    echo MakeBaseStatDots($attributes, 'Wits', 'attribute', $attributeIndex++, self::ATTRIBUTE,
                        $character_type, $this->viewOptions['edit_attributes'], $this->viewOptions['calculate_derived'],
                        $this->viewOptions['xp_create_mode'], $this->max_dots);
                    ?>
                </td>
                <td>
                    <b>Dexterity</b>
                </td>
                <td>
                    <?php
                    echo MakeBaseStatDots($attributes, 'Dexterity', 'attribute', $attributeIndex++,
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
                    echo MakeBaseStatDots($attributes, 'Manipulation', 'attribute', $attributeIndex++,
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
                    echo MakeBaseStatDots($attributes, 'Resolve', 'attribute', $attributeIndex++,
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
                    echo MakeBaseStatDots($attributes, 'Stamina', 'attribute', $attributeIndex++,
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
                    echo MakeBaseStatDots($attributes, 'Composure', 'attribute', $attributeIndex,
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
        $specialty_js = '';
        if ($this->viewOptions['xp_create_mode']) {
            $specialty_js = ' onChange="updateXP(' . self::SKILL . ')" ';
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
                            <?php echo buildSelect($power->getPowerNote(), $skill_list_proper, $skill_list_proper,
                                "skill_spec${i}_selected", "class=\"$input_class\" $specialty_js"); ?>
                        <?php else: ?>
                            <?php echo $power->getPowerNote(); ?>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($this->viewOptions['edit_skills']): ?>
                            <label for="skill_spec<?php echo $i; ?>"><input type="text"
                                                                            name="skill_spec<?php echo $i; ?>"
                                                                            id="skill_spec<?php echo $i; ?>" <?php echo $specialty_js; ?>
                                                                            value="<?php echo $power->getPowerName(); ?>"/>
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
                        echo MakeBaseStatDots($skills, 'Academics', 'skill', $skillIndex++, self::SKILL,
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
                        echo MakeBaseStatDots($skills, 'Athletics', 'skill', $skillIndex++, self::SKILL,
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
                        echo MakeBaseStatDots($skills, 'Animal Ken', 'skill', $skillIndex++, self::SKILL,
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
                        echo MakeBaseStatDots($skills, 'Computer', 'skill', $skillIndex++, self::SKILL, $character_type,
                            $this->viewOptions['edit_skills'], $this->viewOptions['calculate_derived'],
                            $this->viewOptions['xp_create_mode'], $this->max_dots);
                        ?>
                    </td>
                    <td>
                        Brawl
                    </td>
                    <td>
                        <?php
                        echo MakeBaseStatDots($skills, 'Brawl', 'skill', $skillIndex++, self::SKILL, $character_type,
                            $this->viewOptions['edit_skills'], $this->viewOptions['calculate_derived'],
                            $this->viewOptions['xp_create_mode'], $this->max_dots);
                        ?>
                    </td>
                    <td>
                        Empathy
                    </td>
                    <td>
                        <?php
                        echo MakeBaseStatDots($skills, 'Empathy', 'skill', $skillIndex++, self::SKILL, $character_type,
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
                        echo MakeBaseStatDots($skills, 'Crafts', 'skill', $skillIndex++, self::SKILL, $character_type,
                            $this->viewOptions['edit_skills'], $this->viewOptions['calculate_derived'],
                            $this->viewOptions['xp_create_mode'], $this->max_dots);
                        ?>
                    </td>
                    <td>
                        Drive
                    </td>
                    <td>
                        <?php
                        echo MakeBaseStatDots($skills, 'Drive', 'skill', $skillIndex++, self::SKILL, $character_type,
                            $this->viewOptions['edit_skills'], $this->viewOptions['calculate_derived'],
                            $this->viewOptions['xp_create_mode'], $this->max_dots);
                        ?>
                    </td>
                    <td>
                        Expression
                    </td>
                    <td>
                        <?php
                        echo MakeBaseStatDots($skills, 'Expression', 'skill', $skillIndex++, self::SKILL,
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
                        echo MakeBaseStatDots($skills, 'Investigation', 'skill', $skillIndex++, self::SKILL,
                            $character_type, $this->viewOptions['edit_skills'], $this->viewOptions['calculate_derived'],
                            $this->viewOptions['xp_create_mode'], $this->max_dots);
                        ?>
                    </td>
                    <td>
                        Firearms
                    </td>
                    <td>
                        <?php
                        echo MakeBaseStatDots($skills, 'Firearms', 'skill', $skillIndex++, self::SKILL, $character_type,
                            $this->viewOptions['edit_skills'], $this->viewOptions['calculate_derived'],
                            $this->viewOptions['xp_create_mode'], $this->max_dots);
                        ?>
                    </td>
                    <td>
                        Intimidation
                    </td>
                    <td>
                        <?php
                        echo MakeBaseStatDots($skills, 'Intimidation', 'skill', $skillIndex++, self::SKILL,
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
                        echo MakeBaseStatDots($skills, 'Medicine', 'skill', $skillIndex++, self::SKILL, $character_type,
                            $this->viewOptions['edit_skills'], $this->viewOptions['calculate_derived'],
                            $this->viewOptions['xp_create_mode'], $this->max_dots);
                        ?>
                    </td>
                    <td>
                        Larceny
                    </td>
                    <td>
                        <?php
                        echo MakeBaseStatDots($skills, 'Larceny', 'skill', $skillIndex++, self::SKILL, $character_type,
                            $this->viewOptions['edit_skills'], $this->viewOptions['calculate_derived'],
                            $this->viewOptions['xp_create_mode'], $this->max_dots);
                        ?>
                    </td>
                    <td>
                        Persuasion
                    </td>
                    <td>
                        <?php
                        echo MakeBaseStatDots($skills, 'Persuasion', 'skill', $skillIndex++, self::SKILL,
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
                        echo MakeBaseStatDots($skills, 'Occult', 'skill', $skillIndex++, self::SKILL, $character_type,
                            $this->viewOptions['edit_skills'], $this->viewOptions['calculate_derived'],
                            $this->viewOptions['xp_create_mode'], $this->max_dots);
                        ?>
                    </td>
                    <td>
                        Stealth
                    </td>
                    <td>
                        <?php
                        echo MakeBaseStatDots($skills, 'Stealth', 'skill', $skillIndex++, self::SKILL, $character_type,
                            $this->viewOptions['edit_skills'], $this->viewOptions['calculate_derived'],
                            $this->viewOptions['xp_create_mode'], $this->max_dots);
                        ?>
                    </td>
                    <td>
                        Socialize
                    </td>
                    <td>
                        <?php
                        echo MakeBaseStatDots($skills, 'Socialize', 'skill', $skillIndex++, self::SKILL,
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
                        echo MakeBaseStatDots($skills, 'Politics', 'skill', $skillIndex++, self::SKILL, $character_type,
                            $this->viewOptions['edit_skills'], $this->viewOptions['calculate_derived'],
                            $this->viewOptions['xp_create_mode'], $this->max_dots);
                        ?>
                    </td>
                    <td>
                        Survival
                    </td>
                    <td>
                        <?php
                        echo MakeBaseStatDots($skills, 'Survival', 'skill', $skillIndex++, self::SKILL, $character_type,
                            $this->viewOptions['edit_skills'], $this->viewOptions['calculate_derived'],
                            $this->viewOptions['xp_create_mode'], $this->max_dots);
                        ?>
                    </td>
                    <td>
                        Streetwise
                    </td>
                    <td>
                        <?php
                        echo MakeBaseStatDots($skills, 'Streetwise', 'skill', $skillIndex++, self::SKILL,
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
                        echo MakeBaseStatDots($skills, 'Science', 'skill', $skillIndex++, self::SKILL, $character_type,
                            $this->viewOptions['edit_skills'], $this->viewOptions['calculate_derived'],
                            $this->viewOptions['xp_create_mode'], $this->max_dots);
                        ?>
                    </td>
                    <td>
                        Weaponry
                    </td>
                    <td>
                        <?php
                        echo MakeBaseStatDots($skills, 'Weaponry', 'skill', $skillIndex++, self::SKILL, $character_type,
                            $this->viewOptions['edit_skills'], $this->viewOptions['calculate_derived'],
                            $this->viewOptions['xp_create_mode'], $this->max_dots);
                        ?>
                    </td>
                    <td>
                        Subterfuge
                    </td>
                    <td>
                        <?php
                        echo MakeBaseStatDots($skills, 'Subterfuge', 'skill', $skillIndex, self::SKILL, $character_type,
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
                          style="width:100%" <?php echo $this->viewOptions['edit_powers']; ?>><?php echo $misc_powers; ?></textarea>
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
                return $mortal->render($this, $character_name, $character_type_select, $location, $sex, $virtue, $vice,
                    $icon, $age, $is_npc, $status, $concept, $description, $equipment_public, $equipment_hidden,
                    $public_effects, $safe_place, $character_merit_list, $character_flaw_list, $characterMiscList,
                    $health_dots, $size, $wounds_bashing, $wounds_lethal, $wounds_aggravated, $defense, $morality_dots,
                    $initiative_mod, $willpower_perm_dots, $speed, $willpower_temp_dots, $armor, $st_notes_table,
                    $history_table, $skill_table, $attribute_table, $show_sheet_table);
                break;

            case 'Psychic':
                $psychic = new Psychic();
                return $psychic->render($this, $character_name, $character_type_select, $location, $sex, $virtue,
                    $vice, $icon, $age, $is_npc, $status, $concept, $description, $equipment_public, $equipment_hidden,
                    $public_effects, $safe_place, $character_merit_list, $character_flaw_list, $characterMiscList,
                    $health_dots, $size, $wounds_bashing, $wounds_lethal, $wounds_aggravated, $defense, $morality_dots,
                    $initiative_mod, $willpower_perm_dots, $speed, $willpower_temp_dots, $armor, $st_notes_table,
                    $history_table, $skill_table, $attribute_table, $show_sheet_table);
                break;

            case 'Thaumaturge':
                $thaumaturge = new Thaumaturge();
                return $thaumaturge->render($this, $character_name, $character_type_select, $location, $sex, $virtue,
                    $vice, $icon, $age, $is_npc, $status, $concept, $description, $equipment_public, $equipment_hidden,
                    $public_effects, $safe_place, $character_merit_list, $character_flaw_list, $characterMiscList,
                    $health_dots, $size, $wounds_bashing, $wounds_lethal, $wounds_aggravated, $defense, $morality_dots,
                    $initiative_mod, $willpower_perm_dots, $speed, $willpower_temp_dots, $armor, $st_notes_table,
                    $history_table, $skill_table, $attribute_table, $show_sheet_table, $splat1, $friends);
                break;

            case 'Werewolf':
                $renderer = new Werewolf();
                return $renderer->render($this, $character_name, $character_type_select, $location, $sex, $virtue,
                    $vice, $icon, $age, $is_npc, $status, $concept, $description, $equipment_public, $equipment_hidden,
                    $public_effects, $safe_place, $character_merit_list, $character_flaw_list, $characterMiscList,
                    $health_dots, $size, $wounds_bashing, $wounds_lethal, $wounds_aggravated, $defense, $morality_dots,
                    $initiative_mod, $willpower_perm_dots, $speed, $willpower_temp_dots, $armor, $st_notes_table,
                    $history_table, $skill_table, $attribute_table, $show_sheet_table, $splat1, $subsplat, $splat2,
                    $friends, $helper, $power_points_dots, $power_trait_dots);
                break;

            case 'Vampire':
                $renderer = new Vampire();
                return $renderer->render($this, $character_name, $character_type_select, $location, $sex, $virtue,
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
                return $renderer->render($this, $character_name, $character_type_select, $location, $sex, $virtue,
                    $vice, $icon, $age, $is_npc, $status, $concept, $description, $equipment_public, $equipment_hidden,
                    $public_effects, $safe_place, $character_merit_list, $character_flaw_list, $characterMiscList,
                    $health_dots, $size, $wounds_bashing, $wounds_lethal, $wounds_aggravated, $defense, $morality_dots,
                    $initiative_mod, $willpower_perm_dots, $speed, $willpower_temp_dots, $armor, $st_notes_table,
                    $history_table, $skill_table, $attribute_table, $show_sheet_table, $splat1, $subsplat, $splat2,
                    $friends, $helper, $power_points_dots, $power_trait_dots);
                break;

            case 'Ghoul':
                $ghoul = new Ghoul();
                return $ghoul->render($this, $character_name, $character_type_select, $location, $sex, $virtue, $vice,
                    $icon, $age, $is_npc, $status, $concept, $description, $equipment_public, $equipment_hidden,
                    $public_effects, $safe_place, $character_merit_list, $character_flaw_list, $characterMiscList,
                    $health_dots, $size, $wounds_bashing, $wounds_lethal, $wounds_aggravated, $defense, $morality_dots,
                    $initiative_mod, $willpower_perm_dots, $speed, $willpower_temp_dots, $armor, $st_notes_table,
                    $history_table, $skill_table, $attribute_table, $show_sheet_table, $friends, $apparent_age,
                    $power_points_dots);
                break;

            case 'Promethean':
                $promethean = new Promethean();
                return $promethean->render($this, $character_name, $character_type_select, $location, $sex, $virtue,
                    $vice, $icon, $age, $is_npc, $status, $concept, $description, $equipment_public, $equipment_hidden,
                    $public_effects, $safe_place, $character_merit_list, $character_flaw_list, $characterMiscList,
                    $health_dots, $size, $wounds_bashing, $wounds_lethal, $wounds_aggravated, $defense, $morality_dots,
                    $initiative_mod, $willpower_perm_dots, $speed, $willpower_temp_dots, $armor, $st_notes_table,
                    $history_table, $skill_table, $attribute_table, $show_sheet_table, $splat1, $subsplat, $splat2,
                    $friends, $power_points_dots, $power_trait_dots);
                break;

            case 'Changeling':
                $changeling = new Changeling();
                return $changeling->render($this, $character_name, $character_type_select, $location, $sex, $virtue, $vice,
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
                return $hunter->render($this, $character_name, $character_type_select, $location, $sex, $virtue, $vice,
                    $icon, $age, $is_npc, $status, $concept, $description, $equipment_public, $equipment_hidden,
                    $public_effects, $safe_place, $character_merit_list, $character_flaw_list, $characterMiscList,
                    $health_dots, $size, $wounds_bashing, $wounds_lethal, $wounds_aggravated, $defense, $morality_dots,
                    $initiative_mod, $willpower_perm_dots, $speed, $willpower_temp_dots, $armor, $st_notes_table,
                    $history_table, $skill_table, $attribute_table, $show_sheet_table, $splat1, $subsplat, $friends);
                break;

            case 'Geist':
                return $this->buildSheetGeist($character_name, $character_type_select, $location, $sex, $virtue, $vice,
                    $icon, $age, $is_npc, $status, $concept, $description, $equipment_public, $equipment_hidden,
                    $public_effects, $safe_place, $character_merit_list, $character_flaw_list, $characterMiscList,
                    $health_dots, $size, $wounds_bashing, $wounds_lethal, $wounds_aggravated, $defense, $morality_dots,
                    $initiative_mod, $willpower_perm_dots, $speed, $willpower_temp_dots, $armor, $st_notes_table,
                    $history_table, $skill_table, $attribute_table, $show_sheet_table);
                break;

            case 'Purified':
                return $this->buildSheetPurified($character_name, $character_type_select, $location, $sex, $virtue,
                    $vice,
                    $icon, $age, $is_npc, $status, $concept, $description, $equipment_public, $equipment_hidden,
                    $public_effects, $safe_place, $character_merit_list, $character_flaw_list, $characterMiscList,
                    $health_dots, $size, $wounds_bashing, $wounds_lethal, $wounds_aggravated, $defense, $morality_dots,
                    $initiative_mod, $willpower_perm_dots, $speed, $willpower_temp_dots, $armor, $st_notes_table,
                    $history_table, $skill_table, $attribute_table, $show_sheet_table);
                break;

            case 'Possessed':
                return $this->buildSheetPossessed($character_name, $character_type_select, $location, $sex, $virtue,
                    $vice,
                    $icon, $age, $is_npc, $status, $concept, $description, $equipment_public, $equipment_hidden,
                    $public_effects, $safe_place, $character_merit_list, $character_flaw_list, $characterMiscList,
                    $health_dots, $size, $wounds_bashing, $wounds_lethal, $wounds_aggravated, $defense, $morality_dots,
                    $initiative_mod, $willpower_perm_dots, $speed, $willpower_temp_dots, $armor, $st_notes_table,
                    $history_table, $skill_table, $attribute_table, $show_sheet_table);
                break;

            case 'Changing Breed':
                return $this->buildSheetChangingBreed($character_name, $character_type_select, $location, $sex, $virtue,
                    $vice,
                    $icon, $age, $is_npc, $status, $concept, $description, $equipment_public, $equipment_hidden,
                    $public_effects, $safe_place, $character_merit_list, $character_flaw_list, $characterMiscList,
                    $health_dots, $size, $wounds_bashing, $wounds_lethal, $wounds_aggravated, $defense, $morality_dots,
                    $initiative_mod, $willpower_perm_dots, $speed, $willpower_temp_dots, $armor, $st_notes_table,
                    $history_table, $skill_table, $attribute_table, $show_sheet_table);
                break;

            default:
                return "Not implemented yet.  $character_type_select<br>";
                break;
        }
        return '';
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
        if ($characterId != null) {
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

    private function checkEditMode()
    {
        $mayEdit = false;
        foreach ($this->viewOptions as $value) {
            $mayEdit |= $value;
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

}