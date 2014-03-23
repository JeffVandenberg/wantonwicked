<?php
/* @var array $element_type */
/* @var int $max_dots */

use classes\core\helpers\FormHelper;

ob_start();
?>
    <table class="character-sheet <?php echo $table_class; ?>">
        <tr>
            <th colspan="4">
                Vitals
            </th>
        </tr>
        <tr>
            <td width="15%">
                <b>Name</b>
            </td>
            <td width="35%">
                <?php echo $character_name; ?>
            </td>
            <td width="15%">
                <b>Character Type</b>
            </td>
            <td width="35%">
                <?php echo $character_type_select; ?>
            </td>
        </tr>
        <tr>
            <td>
                <b>Location</b>
            </td>
            <td>
                <?php echo $location; ?>
            </td>
            <td>
                <b>Sex:</b>
            </td>
            <td>
                <?php echo $sex; ?>
            </td>
        </tr>
        <tr>
            <td>
                <b>Virtue</b>
            </td>
            <td>
                <?php echo $virtue; ?>
            </td>
            <td>
                <b>Vice</b>
            </td>
            <td>
                <?php echo $vice; ?>
            </td>
        </tr>
        <tr>
            <td>
                <b>Clan</b>
            </td>
            <td>
                <?php echo $splat1; ?>
            </td>
            <td>
                <b>Bloodline</b>
            </td>
            <td>
                <?php echo $subsplat; ?>
            </td>
        </tr>
        <tr>
            <td>
                <b>Covenant</b>
            </td>
            <td>
                <?php echo $splat2; ?>
            </td>
            <td>
                <b>Icon</b>
            </td>
            <td>
                <?php echo $icon; ?>
            </td>
        </tr>
        <tr>
            <td>
                <b>Age</b>
            </td>
            <td>
                <?php echo $age; ?>
            </td>
            <td>
                <b>Apparent Age</b>
            </td>
            <td>
                <?php echo $apparent_age; ?>
            </td>
        </tr>
        <tr>
            <td>
                <b>Is NPC</b>
            </td>
            <td>
                <?php echo $is_npc; ?>
            </td>
            <td>
                <b>Status</b>
            </td>
            <td>
                <?php echo $status; ?>
            </td>
        </tr>
    </table>
<?php
$vitals_table = ob_get_clean();

ob_start();
?>
    <table class="character-sheet <?php echo $table_class; ?>">
        <tr>
            <th colspan="2">
                Information
            </th>
        </tr>
        <tr>
            <td style="width:25%;">
                <b>Concept</b>
            </td>
            <td style="width:75%;">
                <?php echo $concept; ?>
            </td>
        </tr>
        <tr>
            <td>
                <b>Description</b>
            </td>
            <td>
                <?php echo $description; ?>
            </td>
        </tr>
        <tr>
            <td>
                <b>Daily Equipment</b>
            </td>
            <td>
                <?php echo $equipment_public; ?>
            </td>
        </tr>
        <tr>
            <td>
                <b>Other Equipment</b>
            </td>
            <td>
                <?php echo $equipment_hidden; ?>
            </td>
        </tr>
        <tr>
            <td>
                <b>Public Effects</b>
            </td>
            <td>
                <?php echo $public_effects; ?>
            </td>
        </tr>
        <tr>
            <td>
                <b>Sire</b>
            </td>
            <td>
                <?php echo $friends; ?>
            </td>
        </tr>
        <tr>
            <td>
                <b>Haven</b>
            </td>
            <td>
                <?php echo $safe_place; ?>
            </td>
        </tr>
    </table>
<?php
$information_table = ob_get_clean();


// in clan
$disciplines_list = "";

$powers = getPowers($characterId, 'ICDisc', NAMENOTE, 3);

ob_start();
?>
    <table class="character-sheet <?php echo $table_class; ?>" id="icdisc_list">
        <tr>
            <th colspan="2">
                In-Clan Disciplines
                <?php if ($edit_powers): ?>
                    <a href="#" onClick="addDisc('icdisc');return false;">
                        <img src="/img/plus.png" title="Add In-Clan Discipline"/>
                    </a>
                <?php endif; ?>
            </th>
        </tr>
        <tr>
            <td style="width:50%;" class="header-row">
                Name
            </td>
            <td style="width:50%;" class="header-row">
                Level
            </td>
        </tr>
        <?php foreach ($powers as $i => $power): ?>
            <?php $discipline_dots = FormHelper::Dots("icdisc${i}", $power->getPowerLevel(),
                                                      $element_type['supernatural'], $character_type, $max_dots,
                                                      $edit_powers, false, $edit_xp); ?>
            <tr>
                <td>
                    <?php if ($edit_powers): ?>
                        <label for="icdisc<?php echo $i; ?>_name"></label><input type="text"
                                                                                 name="icdisc<?php echo $i; ?>_name"
                                                                                 id="icdisc<?php echo $i; ?>_name"
                                                                                 size="15"
                                                                                 value="<?php echo $power->getPowerName(); ?>">
                    <?php else: ?>
                        <?php echo $power->getPowerName(); ?>
                    <?php endif; ?>
                </td>
                <td>
                    <?php echo $discipline_dots; ?>
                    <input type="hidden" name="icdisc<?php echo $i; ?>_id" id="icdisc<?php echo $i; ?>_id"
                           value="<?php echo $power->getPowerID(); ?>">
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php
$inclanDisciplines = ob_get_clean();


$powers = getPowers($characterId, 'OOCDisc', NAMENOTE, 2);
ob_start();
?>
    <table class="character-sheet <?php echo $table_class; ?>" id="oocdisc_list">
        <tr>
            <th colspan="2">
                Out-of-Clan Disciplines
                <?php if ($edit_powers): ?>
                    <a href="#" onClick="addDisc('oocdisc');return false;">
                        <img src="/img/plus.png" title="Add Out-of-Clan Discipline"/>
                    </a>
                <?php endif; ?>
            </th>
        </tr>
        <tr>
            <td style="width:50%;" class="header-row">
                Name
            </td>
            <td style="width:50%;" class="header-row">
                Level
            </td>
        </tr>
        <?php foreach ($powers as $i => $power): ?>
            <?php $discipline_dots = FormHelper::Dots("oocdisc${i}", $powers[$i]->getPowerLevel(),
                                                      $element_type['supernatural'], $character_type, $max_dots,
                                                      $edit_powers, false, $edit_xp); ?>
            <tr>
                <td>
                    <?php if ($edit_powers): ?>
                        <label for="oocdisc<?php echo $i; ?>_name"></label><input type="text"
                                                                                  name="oocdisc<?php echo $i; ?>_name"
                                                                                  id="oocdisc<?php echo $i; ?>_name"
                                                                                  size="15"
                                                                                  value="<?php echo $power->getPowerName(); ?>">
                    <?php else: ?>
                        <?php echo $power->getPowerName(); ?>
                    <?php endif; ?>
                </td>
                <td>
                    <?php echo $discipline_dots; ?>
                    <input type="hidden" name="oocdisc<?php echo $i; ?>_id" id="oocdisc<?php echo $i; ?>_id"
                           value="<?php echo $power->getPowerID(); ?>">
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php
$oocDisciplines = ob_get_clean();

$powers = getPowers($characterId, 'Devotion', NAMENOTE, 2);
if ($edit_xp) {
    $supernatural_xp_js = " onChange=\"updateXP($element_type[supernatural])\" ";
}
ob_start();
?>
    <table class="character-sheet <?php echo $table_class; ?>" id="devotion_list">
        <tr>
            <th colspan="2">
                Devotions/Rituals/Other
                <?php if ($edit_powers): ?>
                    <a href="#" onClick="addDevotion();return false;">
                        <img src="/img/plus.png" title="Add Devotion/Ritual/Other"/>
                    </a>
                <?php endif; ?>
            </th>
        </tr>
        <tr>
            <td style="width:50%;" class="header-row">
                Name
            </td>
            <td style="width:50%;" class="header-row">
                Cost
            </td>
        </tr>
        <?php foreach ($powers as $i => $power): ?>
            <?php $level = $power->getPowerLevel(); ?>
            <?php $discipline_id = $power->getPowerID(); ?>
            <tr>
                <td>
                    <?php if ($edit_powers): ?>
                        <label for="devotion<?php echo $i; ?>_name"></label><input type="text"
                                                                                   name="devotion<?php echo $i; ?>_name"
                                                                                   id="devotion<?php echo $i; ?>_name"
                                                                                   size="15"
                                                                                   value="<?php echo $power->getPowerName(); ?>">
                    <?php else: ?>
                        <?php echo $power->getPowerName(); ?>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if ($edit_powers): ?>
                        <label for="devotion<?php echo $i; ?>"></label><input type="text"
                                                                              name="devotion<?php echo $i; ?>"
                                                                              id="devotion<?php echo $i; ?>" size="3"
                                                                              maxlength="2"
                                                                              value="<?php echo $level; ?>" <?php echo $supernatural_xp_js; ?>>
                    <?php else: ?>
                        <?php echo $level; ?>
                    <?php endif; ?>
                    <input type="hidden" name="oocdisc<?php echo $i; ?>_id" id="oocdisc<?php echo $i; ?>_id"
                           value="<?php echo $discipline_id; ?>">
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php
$devotions = ob_get_clean();

ob_start();
?>
    <div style="width:50%;float:left;">
        <?php echo $character_merit_list; ?>
        <?php echo $character_flaw_list; ?>
        <?php echo $characterMiscList; ?>
    </div>
    <div style="width:50%;float:left;">
        <?php echo $inclanDisciplines; ?>
        <?php echo $oocDisciplines; ?>
        <?php echo $devotions; ?>
    </div>
    <table class="character-sheet <?php echo $table_class; ?>">
        <tr>
            <th colspan="6">
                Traits
            </th>
        </tr>
        <tr>
            <td style="width:15%">
                Health
            </td>
            <td colspan="2" style="width:50%">
                <?php echo $health_dots; ?>
            </td>
            <td colspan="1" style="width:15%">
                Size
            </td>
            <td colspan="2" style="width:20%">
                <?php echo $size; ?>
            </td>
        </tr>
        <tr>
            <td colspan="1">
                Blood Potency
            </td>
            <td colspan="2">
                <?php echo $power_trait_dots; ?>
            </td>
            <td colspan="1">
                Size
            </td>
            <td colspan="2">
                <?php echo $size; ?>
            </td>
        </tr>
        <tr>
            <td colspan="1">
                Humanity
            </td>
            <td colspan="2">
                <?php echo $morality_dots; ?>
            </td>
            <td colspan="1">
                Defense
            </td>
            <td colspan="2">
                <?php echo $defense; ?>
            </td>
        </tr>
        <tr>
            <td>
                Willpower Perm
            </td>
            <td colspan="2">
                <?php echo $willpower_perm_dots; ?>
            </td>
            <td colspan="1">
                Initiative Mod
            </td>
            <td colspan="2">
                <?php echo $initiative_mod; ?>
            </td>
        </tr>
        <tr>
            <td>
                Willpower Temp
            </td>
            <td colspan="2">
                <?php echo $willpower_temp_dots; ?>
            </td>
            <td colspan="1">
                Speed
            </td>
            <td colspan="2">
                <?php echo $speed; ?>
            </td>
        </tr>
        <tr>
            <td>
                Blood
            </td>
            <td colspan="2">
                <?php echo $power_points_dots; ?>
            </td>
            <td colspan="1">
                Armor
            </td>
            <td colspan="2">
                <?php echo $armor; ?>
            </td>
        </tr>
        <tr>
            <td>
                ABP
            </td>
            <td colspan="2">
                <?php echo $average_power_points; ?>
                <a href="abp.php?action=show_modifiers&character_id=<?php echo $characterId; ?>" target="_blank">Explanation</a>
            </td>
            <td>
                ABP Modifier
            </td>
            <td colspan="2">
                <?php echo $power_points_modifier; ?>
            </td>
        </tr>
    </table>
<?php
$traits_table = ob_get_clean();