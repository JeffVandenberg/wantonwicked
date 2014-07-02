<?php
/**
 * Created by JetBrains PhpStorm.
 * User: JeffVandenberg
 * Date: 8/29/13
 * Time: 11:08 PM
 * To change this template use File | Settings | File Templates.
 */

namespace classes\character\helper;


use classes\character\data\Character;
use classes\character\data\ElementType;
use classes\character\repository\CharacterRepository;
use classes\core\helpers\FormHelper;
use classes\core\repository\Database;
use classes\core\repository\RepositoryManager;
use classes\log\CharacterLog;
use classes\log\data\ActionType;

class CharacterSheetHelper
{
    private $CharacterRepository;

    function __construct() {
        $this->CharacterRepository = new CharacterRepository();
    }

    public static function MakeHeaderView(Character $character)
    {
        $tableClass   = self::GetTableClassForCharacterType($character->CharacterType);
        $showSheetYes = FormHelper::Radio('show_sheet', 'Y', $character->ShowSheet == 'Y');
        $showSheetNo  = FormHelper::Radio('show_sheet', 'N', $character->ShowSheet == 'N');
        $hideIconYes  = FormHelper::Radio('hide_icon', 'Y', $character->HideIcon === 'Y');
        $hideIconNo   = FormHelper::Radio('hide_icon', 'N', $character->HideIcon === 'N');
        $viewPassword = FormHelper::Text('view_password', $character->ViewPassword,
                                         array('size' => 20, 'maxlength' => 30));

        ob_start();
        ?>
        <table class="character-sheet <?php echo $tableClass; ?>">
            <tr>
                <th colspan="3" style="text-align: center;">
                    Sheet Sharing
                </th>
            </tr>
            <tr style="vertical-align: top;">
                <td>
                    Show sheet to Others:
                    <label>Yes: <?php echo $showSheetYes; ?></label>
                    <label>No: <?php echo $showSheetNo; ?></label>
                </td>
                <td>
                    <label>Password to View:
                        <?php echo $viewPassword; ?></label>
                </td>
                <td>
                    Use General Icon:
                    <label>Yes: <?php echo $hideIconYes; ?></label>
                    <label>No: <?php echo $hideIconNo; ?></label>
                </td>
            </tr>
        </table>
        <?php
        return ob_get_clean();
    }

    private static function GetTableClassForCharacterType($characterType)
    {
        $tableClass = "mortal_normal_text";

        switch ($characterType) {
            case 'Mortal':
                $tableClass = "mortal_normal_text";
                break;

            case 'Psychic':
                $tableClass = "mortal_normal_text";
                break;

            case 'Thaumaturge':
                $tableClass = "mortal_normal_text";
                break;

            case 'Vampire':
                $tableClass = "vampire_normal_text";
                break;

            case 'Werewolf':
                $tableClass = "werewolf_normal_text";
                break;

            case 'Mage':
                $tableClass = "mage_normal_text";
                break;

            case 'Ghoul':
                $tableClass = "ghoul_normal_text";
                break;

            case 'Promethean':
                $tableClass = "promethean_normal_text";
                break;

            case 'Changeling':
                $tableClass = "changeling_normal_text";
                break;

            case 'Hunter':
                $tableClass = "mortal_normal_text";
                break;

            case 'Geist':
                $tableClass = "mortal_normal_text";
                break;

            case 'Purified':
                $tableClass = "mortal_normal_text";
                break;

            case 'Possessed':
                $tableClass = "vampire_normal_text";
                break;

            default:
                break;
        }

        return $tableClass;
    }

    public static function MakeAttributesView(Character $character)
    {
        $intelligence = $character->getAttribute('Intelligence')->PowerLevel;
        $wits         = $character->getAttribute('Wits')->PowerLevel;
        $resolve      = $character->getAttribute('Resolve')->PowerLevel;
        $strength     = $character->getAttribute('Strength')->PowerLevel;
        $dexterity    = $character->getAttribute('Dexterity')->PowerLevel;
        $stamina      = $character->getAttribute('Stamina')->PowerLevel;
        $presence     = $character->getAttribute('Presence')->PowerLevel;
        $manipulation = $character->getAttribute('Manipulation')->PowerLevel;
        $composure    = $character->getAttribute('Composure')->PowerLevel;

        $intelligence = FormHelper::Dots('intelligence', $intelligence, ElementType::Attribute,
                                         $character->CharacterType, 7, false, false, false);
        $wits         = FormHelper::Dots('manipulation', $wits, ElementType::Attribute, $character->CharacterType, 7,
                                         false, false, false);
        $resolve      = FormHelper::Dots('resolve', $resolve, ElementType::Attribute, $character->CharacterType, 7,
                                         false, false, false);
        $strength     = FormHelper::Dots('strength', $strength, ElementType::Attribute, $character->CharacterType, 7,
                                         false, false, false);
        $dexterity    = FormHelper::Dots('dexterity', $dexterity, ElementType::Attribute, $character->CharacterType, 7,
                                         false, false, false);
        $stamina      = FormHelper::Dots('stamina', $stamina, ElementType::Attribute, $character->CharacterType, 7,
                                         false, false, false);
        $presence     = FormHelper::Dots('presence', $presence, ElementType::Attribute, $character->CharacterType, 7,
                                         false, false, false);
        $manipulation = FormHelper::Dots('manipulation', $manipulation, ElementType::Attribute,
                                         $character->CharacterType, 7, false, false, false);
        $composure    = FormHelper::Dots('composure', $composure, ElementType::Attribute, $character->CharacterType, 7,
                                         false, false, false);

        ob_start();
        ?>
        <table class="character-sheet <?php echo self::GetTableClassForCharacterType($character->CharacterType); ?>">
            <tr>
                <th colspan="6" style="text-align: center;">
                    Attributes
                    <span id="attribute_div"></span>
                </th>
            </tr>
            <tr>
                <td>
                    <b>
                        Intelligence
                    </b>
                </td>
                <td>
                    <?php echo $intelligence; ?>
                </td>
                <td>
                    <b>
                        Strength
                    </b>
                </td>
                <td>
                    <?php echo $strength; ?>
                </td>
                <td>
                    <b>
                        Presence
                    </b>
                </td>
                <td>
                    <?php echo $presence; ?>
                </td>
            </tr>
            <tr>
                <td>
                    <b>
                        Wits
                    </b>
                </td>
                <td>
                    <?php echo $wits; ?>
                </td>
                <td>
                    <b>
                        Dexterity
                    </b>
                </td>
                <td>
                    <?php echo $dexterity; ?>
                </td>
                <td>
                    <b>
                        Manipulation
                    </b>
                </td>
                <td>
                    <?php echo $manipulation; ?>
                </td>
            </tr>
            <tr>
                <td>
                    <b>
                        Resolve
                    </b>
                </td>
                <td>
                    <?php echo $resolve; ?>
                </td>
                <td>
                    <b>
                        Stamina
                    </b>
                </td>
                <td>
                    <?php echo $stamina; ?>
                </td>
                <td>
                    <b>
                        Composure
                    </b>
                </td>
                <td>
                    <?php echo $composure; ?>
                </td>
            </tr>
        </table>
        <?php
        return ob_get_clean();
    }

    public static function MakeSkillsView(Character $character)
    {
        $skills = array("Academics", "Animal_Ken", "Athletics", "Brawl", "Computer", "Crafts", "Drive", "Empathy", "Expression", "Firearms", "Intimidation", "Investigation", "Larceny", "Medicine", "Occult", "Persuasion", "Politics", "Science", "Socialize", "Stealth", "Streetwise", "Subterfuge", "Survival", "Weaponry");

        foreach ($skills as $skill) {
            $skillLower  = strtolower($skill);
            $$skillLower = FormHelper::Dots(
                                     $skillLower,
                                     $character->getSkill($skill)->PowerLevel,
                                     ElementType::Skill,
                                     $character->CharacterType
            );
        }

        ob_start();
        ?>
        <table class="character-sheet <?php echo self::GetTableClassForCharacterType($character->CharacterType); ?>">
        <tr>
            <th colspan="7" style="text-align: center;">
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
        <tr style="vertical-align: top;">
            <td>
                Academics
            </td>
            <td>
                <?php echo $academics; ?>
            </td>
            <td>
                Athletics
            </td>
            <td>
                <?php echo $athletics; ?>
            </td>
            <td>
                Animal Ken
            </td>
            <td>
                <?php echo $animal_ken; ?>
            </td>
            <td rowspan="11" style="vertical-align: top;">
                <table>
                    <tr>
                        <th>
                            Skill
                        </th>
                        <th>
                            Specialty
                        </th>
                    </tr>
                    <?php foreach ($character->Specialties as $specialty): ?>
                        <tr>
                            <td>
                                <?php echo $specialty->PowerNote; ?>
                            </td>
                            <td>
                                <?php echo $specialty->PowerName; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </td>
        </tr>
        <tr>
            <td>
                Computer
            </td>
            <td>
                <?php echo $computer; ?>
            </td>
            <td>
                Brawl
            </td>
            <td>
                <?php echo $brawl; ?>
            </td>
            <td>
                Empathy
            </td>
            <td>
                <?php echo $empathy; ?>
            </td>
        </tr>
        <tr>
            <td>
                Crafts
            </td>
            <td>
                <?php echo $crafts; ?>
            </td>
            <td>
                Drive
            </td>
            <td>
                <?php echo $drive; ?>
            </td>
            <td>
                Expression
            </td>
            <td>
                <?php echo $expression; ?>
            </td>
        </tr>
        <tr>
            <td>
                Investigation
            </td>
            <td>
                <?php echo $investigation; ?>
            </td>
            <td>
                Firearms
            </td>
            <td>
                <?php echo $firearms; ?>
            </td>
            <td>
                Intimidation
            </td>
            <td>
                <?php echo $intimidation; ?>
            </td>
        </tr>
        <tr>
            <td>
                Medicine
            </td>
            <td>
                <?php echo $medicine; ?>
            </td>
            <td>
                Larceny
            </td>
            <td>
                <?php echo $larceny; ?>
            </td>
            <td>
                Persuasion
            </td>
            <td>
                <?php echo $persuasion; ?>
            </td>
        </tr>
        <tr>
            <td>
                Occult
            </td>
            <td>
                <?php echo $occult; ?>
            </td>
            <td>
                Stealth
            </td>
            <td>
                <?php echo $stealth; ?>
            </td>
            <td>
                Socialize
            </td>
            <td>
                <?php echo $socialize; ?>
            </td>
        </tr>
        <tr>
            <td>
                Politics
            </td>
            <td>
                <?php echo $politics; ?>
            </td>
            <td>
                Survival
            </td>
            <td>
                <?php echo $survival; ?>
            </td>
            <td>
                Streetwise
            </td>
            <td>
                <?php echo $streetwise; ?>
            </td>
        </tr>
        <tr>
            <td>
                Science
            </td>
            <td>
                <?php echo $science; ?>
            </td>
            <td>
                Weaponry
            </td>
            <td>
                <?php echo $weaponry; ?>
            </td>
            <td>
                Subterfuge
            </td>
            <td>
                <?php echo $subterfuge; ?>
            </td>
        </tr>
        </table>

        <?php
        return ob_get_clean();
    }

    public static function MakeVitalsViewOwn(Character $character)
    {
        switch ($character->CharacterType) {
            case 'Vampire':
                return self::MakeVitalsViewOwnVampire($character);
                break;
            default:
                return "Unknown Character Type: " . $character->CharacterType;
        }
    }

    private static function MakeVitalsViewOwnVampire(Character $character)
    {
        ob_start();
        ?>
        <table class="character-sheet <?php echo self::GetTableClassForCharacterType($character->CharacterType); ?>">
            <tr>
                <th colspan="4">
                    Vitals
                </th>
            </tr>
            <tr>
                <td style="width:15%;">
                    <b>Name</b>
                </td>
                <td style="width:35%;">
                    <?php echo $character->CharacterName; ?>
                </td>
                <td style="width:15%;">
                    <b>Character Type</b>
                </td>
                <td style="width:35%;">
                    <?php echo $character->CharacterType; ?>
                </td>
            </tr>
            <tr>
                <td>
                    <b>Location</b>
                </td>
                <td>
                    <?php echo $character->City; ?>
                </td>
                <td>
                    <b>Sex:</b>
                </td>
                <td>
                    <?php echo $character->Sex; ?>
                </td>
            </tr>
            <tr>
                <td>
                    <b>Virtue</b>
                </td>
                <td>
                    <?php echo $character->Virtue; ?>
                </td>
                <td>
                    <b>Vice</b>
                </td>
                <td>
                    <?php echo $character->Vice; ?>
                </td>
            </tr>
            <tr>
                <td>
                    <b>Clan</b>
                </td>
                <td>
                    <?php echo $character->Splat1; ?>
                </td>
                <td>
                    <b>Bloodline</b>
                </td>
                <td>
                    <?php echo $character->SubSplat; ?>
                </td>
            </tr>
            <tr>
                <td>
                    <b>Covenant</b>
                </td>
                <td>
                    <?php echo $character->Splat2; ?>
                </td>
                <td>
                    <b>Icon</b>
                </td>
                <td>
                    <?php echo self::GetIconSelect($character); ?>
                </td>
            </tr>
            <tr>
                <td>
                    <b>Age</b>
                </td>
                <td>
                    <?php echo $character->Age; ?>
                </td>
                <td>
                    <b>Apparent Age</b>
                </td>
                <td>
                    <?php echo $character->ApparentAge; ?>
                </td>
            </tr>
            <tr>
                <td>
                    <b>Is NPC</b>
                </td>
                <td>
                    <?php echo $character->IsNpc; ?>
                </td>
                <td>
                    <b>Status</b>
                </td>
                <td>
                    <?php echo $character->Status; ?>
                </td>
            </tr>
        </table>
        <?php
        return ob_get_clean();
    }

    private static function GetIconSelect(Character $character, $type = 'player')
    {
        $icon_query = "select * from icons where Player_Viewable='Y' order by Icon_Name;";
        if ($type == 'admin') {
            $icon_query = "select * from icons where Admin_Viewable='Y' order by Icon_Name;";
        }
        else {
            if ($type == 'st') {
                $icon_query = "select * from icons where GM_Viewable='Y' order by Icon_Name;";
            }
        }

        $icons  = Database::GetInstance()->Query($icon_query)->All();
        $ids    = array();
        $values = array();
        foreach ($icons as $icon) {
            $ids[]    = $icon['Icon_ID'];
            $values[] = $icon['Icon_Name'];
        }

        return buildSelect($character->Icon, $ids, $values, "icon");
    }

    public function MakeStView($stats, $userdata, $characterType)
    {
        $viewed_sheet        = false;
        $edit_show_sheet     = false;
        $edit_name           = false;
        $edit_vitals         = false;
        $edit_is_dead        = false;
        $edit_location       = false;
        $edit_concept        = false;
        $edit_description    = false;
        $edit_url            = false;
        $edit_equipment      = false;
        $edit_public_effects = false;
        $edit_group          = false;
        $edit_exit_line      = false;
        $edit_is_npc         = false;
        $edit_attributes     = false;
        $edit_skills         = false;
        $edit_perm_traits    = false;
        $edit_temp_traits    = false;
        $edit_powers         = false;
        $edit_history        = false;
        $edit_goals          = false;
        $edit_login_note     = false;
        $edit_experience     = false;
        $show_st_notes       = false;
        $view_is_asst        = false;
        $view_is_st          = false;
        $view_is_head        = false;
        $view_is_admin       = false;
        $may_edit            = false;
        $edit_cell           = false;
        $calculate_derived   = false;
        $edit_xp             = false;

        if ($userdata['is_admin']) {
            $viewed_sheet        = true;
            $edit_show_sheet     = true;
            $edit_name           = true;
            $edit_vitals         = true;
            $edit_is_dead        = true;
            $edit_location       = true;
            $edit_concept        = true;
            $edit_description    = true;
            $edit_url            = true;
            $edit_equipment      = true;
            $edit_public_effects = true;
            $edit_group          = true;
            $edit_exit_line      = true;
            $edit_is_npc         = true;
            $edit_attributes     = true;
            $edit_skills         = true;
            $edit_perm_traits    = true;
            $edit_temp_traits    = true;
            $edit_powers         = true;
            $edit_history        = true;
            $edit_goals          = true;
            $edit_login_note     = true;
            $edit_experience     = true;
            $show_st_notes       = true;
            $view_is_asst        = true;
            $view_is_st          = true;
            $view_is_head        = true;
            $view_is_admin       = true;
            $may_edit            = true;
            $edit_cell           = true;
        }

        if (!$viewed_sheet && $userdata['is_head']) {
            $viewed_sheet        = true;
            $edit_name           = true;
            $edit_vitals         = true;
            $edit_is_dead        = true;
            $edit_location       = true;
            $edit_concept        = true;
            $edit_description    = true;
            $edit_url            = true;
            $edit_equipment      = true;
            $edit_public_effects = true;
            $edit_group          = true;
            $edit_exit_line      = true;
            $edit_is_npc         = true;
            $edit_attributes     = true;
            $edit_skills         = true;
            $edit_perm_traits    = true;
            $edit_temp_traits    = true;
            $edit_powers         = true;
            $edit_history        = true;
            $edit_goals          = true;
            $edit_login_note     = true;
            $edit_experience     = true;
            $show_st_notes       = true;
            $view_is_asst        = true;
            $view_is_st          = true;
            $view_is_head        = true;
            $may_edit            = true;
            $edit_cell           = true;
        }

        if (!$viewed_sheet && $userdata['is_gm']) {
            $viewed_sheet = true;
            // open update
            $edit_name           = true;
            $edit_vitals         = true;
            $edit_is_dead        = true;
            $edit_location       = true;
            $edit_concept        = true;
            $edit_description    = true;
            $edit_url            = true;
            $edit_equipment      = true;
            $edit_public_effects = true;
            $edit_group          = true;
            $edit_exit_line      = true;
            $edit_is_npc         = true;
            $edit_attributes     = true;
            $edit_skills         = true;
            $edit_perm_traits    = true;
            $edit_temp_traits    = true;
            $edit_powers         = true;
            $edit_history        = true;
            $edit_goals          = true;
            $edit_login_note     = true;
            $edit_experience     = true;
            $show_st_notes       = true;
            $view_is_st          = true;
            $may_edit            = true;
            $edit_cell           = true;
        }

        if (!$viewed_sheet && $userdata['is_asst']) {
            $edit_name           = true;
            $edit_vitals         = true;
            $edit_is_dead        = true;
            $edit_location       = true;
            $edit_concept        = true;
            $edit_description    = true;
            $edit_url            = true;
            $edit_equipment      = true;
            $edit_public_effects = true;
            $edit_group          = true;
            $edit_exit_line      = true;
            $edit_is_npc         = true;
            $edit_attributes     = true;
            $edit_skills         = true;
            $edit_perm_traits    = true;
            $edit_temp_traits    = true;
            $edit_powers         = true;
            $edit_history        = true;
            $edit_goals          = true;
            $edit_login_note     = true;
            $edit_experience     = true;
            $show_st_notes       = true;
            $view_is_asst        = true;
            $may_edit            = true;
            $edit_cell           = true;
        }
        if ($stats['is_sanctioned'] == '') {
            $edit_xp = true;
        }

        return buildWoDSheetXP($stats, $characterType, $edit_show_sheet, $edit_name, $edit_vitals, $edit_is_npc,
                               $edit_is_dead, $edit_location, $edit_concept, $edit_description, $edit_url,
                               $edit_equipment, $edit_public_effects, $edit_group, $edit_exit_line, $edit_is_npc,
                               $edit_attributes, $edit_skills, $edit_perm_traits, $edit_temp_traits, $edit_powers,
                               $edit_history, $edit_goals, $edit_login_note, $edit_experience, $show_st_notes,
                               $view_is_asst, $view_is_st, $view_is_head, $view_is_admin, $may_edit, $edit_cell,
                               $calculate_derived, $edit_xp);

    }

    public function MakeViewOwn($stats, $characterType)
    {
        $edit_name           = false;
        $edit_vitals         = false;
        $edit_is_dead        = false;
        $edit_location       = false;
        $edit_concept        = false;
        $edit_equipment      = false;
        $edit_public_effects = false;
        $edit_group          = false;
        $edit_is_npc         = false;
        $edit_attributes     = false;
        $edit_skills         = false;
        $edit_perm_traits    = false;
        $edit_powers         = false;
        $edit_history        = false;
        $edit_login_note     = false;
        $edit_experience     = false;
        $show_st_notes       = false;
        $view_is_asst        = false;
        $view_is_st          = false;
        $view_is_head        = false;
        $view_is_admin       = false;
        $edit_cell           = false;
        $calculate_derived   = false;
        $edit_xp             = false;

        if (($stats['asst_sanctioned'] == 'Y') || ($stats['is_sanctioned'] == 'Y')) {
            $edit_show_sheet  = true;
            $edit_description = true;
            $edit_url         = true;
            $edit_exit_line   = true;
            $edit_temp_traits = true;
            $edit_goals       = true;
            $may_edit         = true;
        }
        else {
            $edit_show_sheet     = true;
            $edit_name           = true;
            $edit_vitals         = true;
            $edit_is_dead        = true;
            $edit_location       = true;
            $edit_concept        = true;
            $edit_description    = true;
            $edit_url            = true;
            $edit_equipment      = true;
            $edit_public_effects = true;
            $edit_group          = true;
            $edit_exit_line      = true;
            $edit_is_npc         = true;
            $edit_attributes     = true;
            $edit_skills         = true;
            $edit_perm_traits    = true;
            $edit_temp_traits    = true;
            $edit_powers         = true;
            $edit_history        = true;
            $edit_goals          = true;
            $edit_experience     = true;
            $may_edit            = true;
            $edit_cell           = true;
            $calculate_derived   = true;
            $edit_xp             = true;
        }

        return buildWoDSheetXP($stats, $characterType, $edit_show_sheet, $edit_name, $edit_vitals, $edit_is_npc,
                               $edit_is_dead, $edit_location, $edit_concept, $edit_description, $edit_url,
                               $edit_equipment, $edit_public_effects, $edit_group, $edit_exit_line, $edit_is_npc,
                               $edit_attributes, $edit_skills, $edit_perm_traits, $edit_temp_traits, $edit_powers,
                               $edit_history, $edit_goals, $edit_login_note, $edit_experience, $show_st_notes,
                               $view_is_asst, $view_is_st, $view_is_head, $view_is_admin, $may_edit, $edit_cell,
                               $calculate_derived, $edit_xp);

    }

    public function MakeNewView($stats, $characterType)
    {
        $edit_show_sheet     = true;
        $edit_name           = true;
        $edit_vitals         = true;
        $edit_is_dead        = true;
        $edit_location       = true;
        $edit_concept        = true;
        $edit_description    = true;
        $edit_url            = true;
        $edit_equipment      = true;
        $edit_public_effects = true;
        $edit_group          = true;
        $edit_exit_line      = true;
        $edit_is_npc         = true;
        $edit_attributes     = true;
        $edit_skills         = true;
        $edit_perm_traits    = true;
        $edit_temp_traits    = true;
        $edit_powers         = true;
        $edit_history        = true;
        $edit_goals          = true;
        $edit_login_note     = false;
        $edit_experience     = true;
        $show_st_notes       = false;
        $view_is_asst        = false;
        $view_is_st          = false;
        $view_is_head        = false;
        $view_is_admin       = false;
        $may_edit            = true;
        $edit_cell           = true;
        $calculate_derived   = true;
        $edit_xp             = true;

        return buildWoDSheetXP($stats, $characterType, $edit_show_sheet, $edit_name, $edit_vitals, $edit_is_npc,
                               $edit_is_dead, $edit_location, $edit_concept, $edit_description, $edit_url,
                               $edit_equipment, $edit_public_effects, $edit_group, $edit_exit_line, $edit_is_npc,
                               $edit_attributes, $edit_skills, $edit_perm_traits, $edit_temp_traits, $edit_powers,
                               $edit_history, $edit_goals, $edit_login_note, $edit_experience, $show_st_notes,
                               $view_is_asst, $view_is_st, $view_is_head, $view_is_admin, $may_edit, $edit_cell,
                               $calculate_derived, $edit_xp);
    }

    public function MakeLockedView($stats, $characterType)
    {
        $edit_show_sheet     = false;
        $edit_name           = false;
        $edit_vitals         = false;
        $edit_is_dead        = false;
        $edit_location       = false;
        $edit_concept        = false;
        $edit_description    = false;
        $edit_url            = false;
        $edit_equipment      = false;
        $edit_public_effects = false;
        $edit_group          = false;
        $edit_exit_line      = false;
        $edit_is_npc         = false;
        $edit_attributes     = false;
        $edit_skills         = false;
        $edit_perm_traits    = false;
        $edit_temp_traits    = false;
        $edit_powers         = false;
        $edit_history        = false;
        $edit_goals          = false;
        $edit_login_note     = false;
        $edit_experience     = false;
        $show_st_notes       = false;
        $view_is_asst        = false;
        $view_is_st          = false;
        $view_is_head        = false;
        $view_is_admin       = false;
        $may_edit            = false;
        $edit_cell           = false;
        $calculate_derived   = false;
        $edit_xp             = false;

        return buildWoDSheetXP($stats, $characterType, $edit_show_sheet, $edit_name, $edit_vitals, $edit_is_npc,
                               $edit_is_dead, $edit_location, $edit_concept, $edit_description, $edit_url,
                               $edit_equipment, $edit_public_effects, $edit_group, $edit_exit_line, $edit_is_npc,
                               $edit_attributes, $edit_skills, $edit_perm_traits, $edit_temp_traits, $edit_powers,
                               $edit_history, $edit_goals, $edit_login_note, $edit_experience, $show_st_notes,
                               $view_is_asst, $view_is_st, $view_is_head, $view_is_admin, $may_edit, $edit_cell,
                               $calculate_derived, $edit_xp);
    }

    public function UpdateOwnLimited(Character $oldCharacter, $newStats)
    {
        $edit_show_sheet     = true;
        $edit_name           = false;
        $edit_vitals         = false;
        $edit_is_npc         = false;
        $edit_is_dead        = false;
        $edit_location       = false;
        $edit_concept        = false;
        $edit_description    = true;
        $edit_url            = true;
        $edit_equipment      = false;
        $edit_public_effects = false;
        $edit_group          = false;
        $edit_exit_line      = true;
        $edit_attributes     = false;
        $edit_skills         = false;
        $edit_perm_traits    = false;
        $edit_temp_traits    = true;
        $edit_powers         = false;
        $edit_history        = false;
        $edit_goals          = true;
        $edit_login_note     = false;
        $edit_experience     = false;
        $show_st_notes       = false;
        $view_is_asst        = false;
        $view_is_st          = false;
        $view_is_head        = false;
        $view_is_admin       = false;
        $may_edit            = true;
        $edit_cell           = false;

        $error = updateWoDSheetXP($newStats, $edit_show_sheet, $edit_name, $edit_vitals, $edit_is_npc, $edit_is_dead,
                                  $edit_location, $edit_concept, $edit_description, $edit_url, $edit_equipment,
                                  $edit_public_effects, $edit_group, $edit_exit_line, $edit_attributes, $edit_skills,
                                  $edit_perm_traits, $edit_temp_traits, $edit_powers, $edit_history, $edit_goals,
                                  $edit_login_note, $edit_experience, $show_st_notes, $view_is_asst, $view_is_st,
                                  $view_is_head, $view_is_admin, $may_edit, $edit_cell);
        if ($error == '') {
            RepositoryManager::ClearCache();
            $newCharacter = $this->CharacterRepository->GetById($newStats['character_id']);
            /* @var Character $newCharacter */
            $this->LogChanges($newCharacter, $oldCharacter);
        }
    }

    public function UpdateOwnFull(Character $oldCharacter, $newStats)
    {
        $edit_show_sheet     = true;
        $edit_name           = true;
        $edit_vitals         = true;
        $edit_is_npc         = true;
        $edit_is_dead        = true;
        $edit_location       = true;
        $edit_concept        = true;
        $edit_description    = true;
        $edit_url            = true;
        $edit_equipment      = true;
        $edit_public_effects = true;
        $edit_group          = true;
        $edit_exit_line      = true;
        $edit_attributes     = true;
        $edit_skills         = true;
        $edit_perm_traits    = true;
        $edit_temp_traits    = true;
        $edit_powers         = true;
        $edit_history        = true;
        $edit_goals          = true;
        $edit_login_note     = false;
        $edit_experience     = false;
        $show_st_notes       = false;
        $view_is_asst        = false;
        $view_is_st          = false;
        $view_is_head        = false;
        $view_is_admin       = false;
        $may_edit            = true;
        $edit_cell           = true;

        $error = updateWoDSheetXP($newStats, $edit_show_sheet, $edit_name, $edit_vitals, $edit_is_npc, $edit_is_dead,
                                  $edit_location, $edit_concept, $edit_description, $edit_url, $edit_equipment,
                                  $edit_public_effects, $edit_group, $edit_exit_line, $edit_attributes, $edit_skills,
                                  $edit_perm_traits, $edit_temp_traits, $edit_powers, $edit_history, $edit_goals,
                                  $edit_login_note, $edit_experience, $show_st_notes, $view_is_asst, $view_is_st,
                                  $view_is_head, $view_is_admin, $may_edit, $edit_cell);
        if ($error == '') {
            RepositoryManager::ClearCache();
            $newCharacter = $this->CharacterRepository->GetById($newStats['character_id']);
            /* @var Character $newCharacter */
            $this->LogChanges($newCharacter, $oldCharacter);
        }
    }

    private function LogChanges(Character $newCharacter, Character $oldCharacter)
    {
        global $userdata;

        if($newCharacter->IsSanctioned != $oldCharacter->IsSanctioned)
        {
            if($newCharacter->IsSanctioned == 'Y') {
                CharacterLog::LogAction($newCharacter['character_id'], ActionType::Sanctioned, 'ST Sanctioned Character', $userdata['user_id']);
            }
            if($newCharacter->IsSanctioned == 'N') {
                CharacterLog::LogAction($newCharacter['character_id'], ActionType::Desanctioned, 'ST Desanctioned Character', $userdata['user_id']);
            }
        }

        $excludedProperties = array(
            'IsSanctioned',
            'SheetUpdate',
        );

        $changedProperties = array();
        foreach($newCharacter as $property => $value) {
            if(!in_array($property, $excludedProperties)) {
                if($newCharacter->$property != $oldCharacter->$property) {
                    $changedProperties[] = $property;
                }
            }
        }

        $note = "";
        if(count($changedProperties) > 0) {
            foreach($changedProperties as $property) {
                $note .= $property . ' changed from ' . $oldCharacter->$property . ' to ' . $newCharacter->$property . "\n";
            }
        }

        $newPowerList = $newCharacter->CharacterPower;
        $oldPowerList = $oldCharacter->CharacterPower;

        $changedPowerList = array();

        foreach($newCharacter->CharacterPower as $i => $newPower) {
            foreach($oldCharacter->CharacterPower as $j => $oldPower) {
                // if they are the same
                if($newPower->Id == $oldPower->Id) {
                    if(($newPower->PowerName == $oldPower->PowerName)
                        && ($newPower->PowerNote == $oldPower->PowerNote)
                        && ($newPower->PowerLevel == $oldPower->PowerLevel)
                    ) {
                    }
                    else {
                        $changedPowerList[] = array(
                            'old' => $oldPower,
                            'new' => $newPower
                        );
                    }
                    unset($newPowerList[$i]);
                    unset($oldPowerList[$j]);
                }
            }
        }

        foreach($newPowerList as $newPower) {
            $note .= 'Added Power: ' . $newPower->PowerType .
                ' Name: ' . $newPower->PowerName .
                ' Note: ' . $newPower->PowerNote .
                ' Level: ' . $newPower->PowerLevel . " \n";
        }

        foreach($oldPowerList as $oldPower) {
            $note .= 'Removed Power: ' . $oldPower->PowerType .
                ' Name: ' . $oldPower->PowerName .
                ' Note: ' . $oldPower->PowerNote .
                ' Level: ' . $oldPower->PowerLevel . " \n";
        }

        foreach($changedPowerList as $power) {
            $note .= 'Changed Power: ' . $power['new']->PowerType .
                ' OLD: ' .
                ' Name: ' . $power['old']->PowerName .
                ' Note: ' . $power['old']->PowerNote .
                ' Level: ' . $power['old']->PowerLevel .
                ' NEW ' .
                ' Name: ' . $power['new']->PowerName .
                ' Note: ' . $power['new']->PowerNote .
                ' Level: ' . $power['new']->PowerLevel . " \n";
            ;
        }

        CharacterLog::LogAction($newCharacter->Id, ActionType::UpdateCharacter, str_replace("\n", "<br/>", $note),
                                $userdata['user_id']);
    }

    public function UpdateNew($newStats)
    {
        $edit_show_sheet     = true;
        $edit_name           = true;
        $edit_vitals         = true;
        $edit_is_npc         = true;
        $edit_is_dead        = true;
        $edit_location       = true;
        $edit_concept        = true;
        $edit_description    = true;
        $edit_url            = true;
        $edit_equipment      = true;
        $edit_public_effects = true;
        $edit_group          = true;
        $edit_exit_line      = true;
        $edit_attributes     = true;
        $edit_skills         = true;
        $edit_perm_traits    = true;
        $edit_temp_traits    = true;
        $edit_powers         = true;
        $edit_history        = true;
        $edit_goals          = true;
        $edit_login_note     = false;
        $edit_experience     = false;
        $show_st_notes       = false;
        $view_is_asst        = false;
        $view_is_st          = false;
        $view_is_head        = false;
        $view_is_admin       = false;
        $may_edit            = true;
        $edit_cell           = true;

        $error = updateWoDSheetXP($newStats, $edit_show_sheet, $edit_name, $edit_vitals, $edit_is_npc, $edit_is_dead,
                                  $edit_location, $edit_concept, $edit_description, $edit_url, $edit_equipment,
                                  $edit_public_effects, $edit_group, $edit_exit_line, $edit_attributes, $edit_skills,
                                  $edit_perm_traits, $edit_temp_traits, $edit_powers, $edit_history, $edit_goals,
                                  $edit_login_note, $edit_experience, $show_st_notes, $view_is_asst, $view_is_st,
                                  $view_is_head, $view_is_admin, $may_edit, $edit_cell);
        if ($error == '') {
            //$this->LogChanges($newStats, array());
        }
    }

    public function UpdateSt(array $newStats, Character $oldStats, array $userdata)
    {
        $viewed_sheet        = false;
        $edit_show_sheet     = false;
        $edit_name           = false;
        $edit_vitals         = false;
        $edit_is_npc         = false;
        $edit_is_dead        = false;
        $edit_location       = false;
        $edit_concept        = false;
        $edit_description    = false;
        $edit_url            = false;
        $edit_equipment      = false;
        $edit_public_effects = false;
        $edit_group          = false;
        $edit_exit_line      = false;
        $edit_attributes     = false;
        $edit_skills         = false;
        $edit_perm_traits    = false;
        $edit_temp_traits    = false;
        $edit_powers         = false;
        $edit_history        = false;
        $edit_goals          = false;
        $edit_login_note     = false;
        $edit_experience     = false;
        $show_st_notes       = false;
        $view_is_asst        = false;
        $view_is_st          = false;
        $view_is_head        = false;
        $view_is_admin       = false;
        $may_edit            = false;
        $edit_cell           = false;

        if ($userdata['is_admin']) {
            $viewed_sheet        = true;
            $edit_show_sheet     = true;
            $edit_name           = true;
            $edit_vitals         = true;
            $edit_is_npc         = true;
            $edit_is_dead        = true;
            $edit_location       = true;
            $edit_concept        = true;
            $edit_description    = true;
            $edit_url            = true;
            $edit_equipment      = true;
            $edit_public_effects = true;
            $edit_group          = true;
            $edit_exit_line      = true;
            $edit_attributes     = true;
            $edit_skills         = true;
            $edit_perm_traits    = true;
            $edit_temp_traits    = true;
            $edit_powers         = true;
            $edit_history        = true;
            $edit_goals          = true;
            $edit_login_note     = true;
            $edit_experience     = true;
            $show_st_notes       = true;
            $view_is_asst        = true;
            $view_is_st          = true;
            $view_is_head        = true;
            $view_is_admin       = true;
            $may_edit            = true;
            $edit_cell           = true;
        }

        if (!$viewed_sheet && $userdata['is_head']) {
            $viewed_sheet        = true;
            $edit_name           = true;
            $edit_vitals         = true;
            $edit_is_npc         = true;
            $edit_is_dead        = true;
            $edit_location       = true;
            $edit_concept        = true;
            $edit_description    = true;
            $edit_url            = true;
            $edit_equipment      = true;
            $edit_public_effects = true;
            $edit_group          = true;
            $edit_exit_line      = true;
            $edit_attributes     = true;
            $edit_skills         = true;
            $edit_perm_traits    = true;
            $edit_temp_traits    = true;
            $edit_powers         = true;
            $edit_history        = true;
            $edit_goals          = true;
            $edit_login_note     = true;
            $edit_experience     = true;
            $show_st_notes       = true;
            $view_is_asst        = true;
            $view_is_st          = true;
            $view_is_head        = true;
            $may_edit            = true;
            $edit_cell           = true;
        }

        if (!$viewed_sheet && $userdata['is_gm']) {
            $viewed_sheet = true;
            // open update
            $edit_show_sheet     = false;
            $edit_name           = true;
            $edit_vitals         = true;
            $edit_is_npc         = true;
            $edit_is_dead        = true;
            $edit_location       = true;
            $edit_concept        = true;
            $edit_description    = true;
            $edit_url            = true;
            $edit_equipment      = true;
            $edit_public_effects = true;
            $edit_group          = true;
            $edit_exit_line      = true;
            $edit_attributes     = true;
            $edit_skills         = true;
            $edit_perm_traits    = true;
            $edit_temp_traits    = true;
            $edit_powers         = true;
            $edit_history        = true;
            $edit_goals          = true;
            $edit_login_note     = true;
            $edit_experience     = true;
            $show_st_notes       = true;
            $view_is_st          = true;
            $may_edit            = true;
            $edit_cell           = true;
        }

        if (!$viewed_sheet && $userdata['is_asst']) {
            $viewed_sheet        = true;
            $edit_name           = true;
            $edit_vitals         = true;
            $edit_is_npc         = true;
            $edit_is_dead        = true;
            $edit_location       = true;
            $edit_concept        = true;
            $edit_description    = true;
            $edit_url            = true;
            $edit_equipment      = true;
            $edit_public_effects = true;
            $edit_group          = true;
            $edit_exit_line      = true;
            $edit_attributes     = true;
            $edit_skills         = true;
            $edit_perm_traits    = true;
            $edit_temp_traits    = true;
            $edit_powers         = true;
            $edit_history        = true;
            $edit_goals          = true;
            $edit_login_note     = true;
            $edit_experience     = true;
            $show_st_notes       = true;
            $view_is_asst        = true;
            $may_edit            = true;
            $edit_cell           = true;
        }
        if ($viewed_sheet) {
            if($newStats['xp_spent'] > 0) {
                CharacterLog::LogAction($newStats['character_id'], ActionType::XPModification, 'Removed ' . $newStats['xp_gained'] . 'XP: ' . $newStats['xp_note'], $userdata['user_id']);
            }
            if($newStats['xp_gained'] > 0) {
                CharacterLog::LogAction($newStats['character_id'], ActionType::XPModification, 'Added ' . $newStats['xp_gained'] . 'XP: ' . $newStats['xp_note'], $userdata['user_id']);
            }
            $error = updateWoDSheetXP($newStats, $edit_show_sheet, $edit_name, $edit_vitals, $edit_is_npc, $edit_is_dead,
                                      $edit_location, $edit_concept, $edit_description, $edit_url, $edit_equipment,
                                      $edit_public_effects, $edit_group, $edit_exit_line, $edit_attributes, $edit_skills,
                                      $edit_perm_traits, $edit_temp_traits, $edit_powers, $edit_history, $edit_goals,
                                      $edit_login_note, $edit_experience, $show_st_notes, $view_is_asst, $view_is_st,
                                      $view_is_head, $view_is_admin, $may_edit, $edit_cell);
            if ($error == '') {
                RepositoryManager::ClearCache();
                $newCharacter = $this->CharacterRepository->GetById($newStats['character_id']);
                /* @var Character $newCharacter */
                $this->LogChanges($newCharacter, $oldStats);
            }
        }
    }
}