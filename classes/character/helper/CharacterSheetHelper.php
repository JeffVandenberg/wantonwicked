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
use classes\core\helpers\FormHelper;

class CharacterSheetHelper
{

    public static function MakeHeaderView(Character $character)
    {
        $tableClass = self::GetTableClassForCharacterType($character->CharacterType);
        $showSheetYes = FormHelper::Radio('show_sheet', 'Y', $character->ShowSheet == 'Y');
        $showSheetNo = FormHelper::Radio('show_sheet', 'N', $character->ShowSheet == 'N');
        $hideIconYes = FormHelper::Radio('hide_icon', 'Y', $character->HideIcon === 'Y');
        $hideIconNo = FormHelper::Radio('hide_icon', 'N', $character->HideIcon === 'N');
        $viewPassword = FormHelper::Text('view_password', $character->ViewPassword, array('size' => 20, 'maxlength' => 30));

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
        $wits = $character->getAttribute('Wits')->PowerLevel;
        $resolve = $character->getAttribute('Resolve')->PowerLevel;
        $strength = $character->getAttribute('Strength')->PowerLevel;
        $dexterity = $character->getAttribute('Dexterity')->PowerLevel;
        $stamina = $character->getAttribute('Stamina')->PowerLevel;
        $presence = $character->getAttribute('Presence')->PowerLevel;
        $manipulation = $character->getAttribute('Manipulation')->PowerLevel;
        $composure = $character->getAttribute('Composure')->PowerLevel;

        $intelligence = FormHelper::Dots('intelligence', $intelligence, ElementType::Attribute, $character->CharacterType, 7, false, false, false);
        $wits = FormHelper::Dots('manipulation', $wits, ElementType::Attribute, $character->CharacterType, 7, false, false, false);
        $resolve = FormHelper::Dots('resolve', $resolve, ElementType::Attribute, $character->CharacterType, 7, false, false, false);
        $strength = FormHelper::Dots('strength', $strength, ElementType::Attribute, $character->CharacterType, 7, false, false, false);
        $dexterity = FormHelper::Dots('dexterity', $dexterity, ElementType::Attribute, $character->CharacterType, 7, false, false, false);
        $stamina = FormHelper::Dots('stamina', $stamina, ElementType::Attribute, $character->CharacterType, 7, false, false, false);
        $presence = FormHelper::Dots('presence', $presence, ElementType::Attribute, $character->CharacterType, 7, false, false, false);
        $manipulation = FormHelper::Dots('manipulation', $manipulation, ElementType::Attribute, $character->CharacterType, 7, false, false, false);
        $composure = FormHelper::Dots('composure', $composure, ElementType::Attribute, $character->CharacterType, 7, false, false, false);

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
            $skillLower = strtolower($skill);
            $$skillLower = FormHelper::Dots($skillLower, $character->getSkill($skill)->PowerLevel, ElementType::Skill, $character->CharacterType);
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
                    Specialties here!
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
}