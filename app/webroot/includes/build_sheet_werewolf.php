<?php
/** @var $table_class string */
use classes\core\helpers\FormHelper;

/** @var $characterId int */
/** @var $max_dots int */
/** @var $input_class string */

$vitals_table = <<<EOQ
<table class="character-sheet $table_class">
    <tr>
        <th colspan="4" align="center">
            Vitals
        </th>
    </tr>
    <tr>
        <td width="15%">
            <b>Name</b>
        </td>
        <td width="35%">
            $character_name
        </td>
        <td width="15%">
            <b>Character Type</b>
        </td>
        <td width="35%">
            $character_type_select
        </td>
    </tr>
    <tr>
        <td>
            <b>Location</b>
        </td>
        <td>
            $location
        </td>
        <td>
            <b>Sex:</b>
        </td>
        <td>
            $sex
        </td>
    </tr>
    <tr>
        <td>
            <b>Virtue</b>
        </td>
        <td>
            $virtue
        </td>
        <td>
            <b>Vice</b>
        </td>
        <td>
            $vice
        </td>
    </tr>
    <tr>
        <td>
            <b>Auspice</b>
        </td>
        <td>
            $splat1
        </td>
        <td>
            <b>Lodge</b>
        </td>
        <td>
            $subsplat
        </td>
    </tr>
    <tr>
        <td>
            <b>Tribe</b>
        </td>
        <td>
            $splat2
        </td>
        <td>
            <b>Icon</b>
        </td>
        <td>
            $icon
        </td>
    </tr>
    <tr>
        <td>
            <b>Age</b>
        </td>
        <td>
            $age
        </td>
        <td>
            <b>Pack</b>
        </td>
        <td>
            $friends
        </td>
    </tr>
    <tr>
        <td>
            <b>Is NPC</b>
        </td>
        <td>
            $is_npc
        </td>
        <td>
            <b>Status</b>
        </td>
        <td>
            $status
        </td>
    </tr>
</table>
EOQ;

$information_table = <<<EOQ
<table class="character-sheet $table_class">
    <tr>
        <th colspan="2" align="center">
            Information
        </th>
    </tr>
    <tr>
        <td width="25%">
            <b>Concept</b>
        </td>
        <td width="75%">
            $concept
        </td>
    </tr>
    <tr>
        <td>
            <b>Description</b>
        </td>
        <td>
            $description
        </td>
    </tr>
    <tr>
        <td>
            <b>Daily Equipment</b>
        </td>
        <td>
            $equipment_public
        </td>
    </tr>
    <tr>
        <td>
            <b>Other Equipment</b>
        </td>
        <td>
            $equipment_hidden
        </td>
    </tr>
    <tr>
        <td>
            <b>Public Effects</b>
        </td>
        <td>
            $public_effects
        </td>
    </tr>
    <tr>
        <td>
            <b>Totem</b>
        </td>
        <td>
            $helper
        </td>
    </tr>
    <tr>
        <td>
            <b>Territory/Loci</b>
        </td>
        <td>
            $safe_place
        </td>
    </tr>
</table>
EOQ;

$werewolfupdate_js = "";
if ($edit_xp) {
    $werewolfupdate_js = " onChange=\"updateXP($element_type[supernatural])\" ";
}

$powers = getPowers($characterId, 'AffGift', NOTELEVEL, 5);
ob_start();
?>
    <table class="character-sheet <?php echo $table_class; ?>" id="affgift_list">
        <tr>
            <th colspan="3">
                Affinity Gifts
                <?php if ($edit_powers): ?>
                    <a href="#" onClick="addGift('affgift');return false;">
                        <img src="/img/plus.png" title="Add Affinity Gift"/>
                    </a>
                <?php endif; ?>
            </th>
        </tr>
        <tr>
            <td class="header-row">
                Gift List
            </td>
            <td class="header-row">
                Gift Name
            </td>
            <td class="header-row">
                Rank
            </td>
        </tr>
        <?php foreach ($powers as $i => $power): ?>
            <?php $dots = FormHelper::Dots("affgift${i}", $power->getPowerLevel(),
                                           $element_type['supernatural'], $character_type, $max_dots,
                                           $edit_powers, false, $edit_xp); ?>
            <tr>
                <td>
                    <?php if ($edit_powers): ?>
                        <label for="affgift<?php echo $i; ?>_note"></label><input type="text"
                                                                                  name="affgift<?php echo $i; ?>_note"
                                                                                  id="affgift<?php echo $i; ?>_note"
                                                                                  size="20"
                                                                                  value="<?php echo $power->getPowerNote(); ?>">
                    <?php else: ?>
                        <?php echo $power->getPowerNote(); ?>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if ($edit_powers): ?>
                        <label for="affgift<?php echo $i; ?>_name"></label><input type="text"
                                                                                  name="affgift<?php echo $i; ?>_name"
                                                                                  id="affgift<?php echo $i; ?>_name"
                                                                                  size="15"
                                                                                  value="<?php echo $power->getPowerName(); ?>">
                    <?php else: ?>
                        <?php echo $power->getPowerName(); ?>
                    <?php endif; ?>
                </td>
                <td>
                    <?php echo $dots; ?>
                    <input type="hidden" name="affgift<?php echo $i; ?>_id" id="affgift<?php echo $i; ?>_id"
                           value="<?php echo $power->getPowerID(); ?>">
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php
$affinityGiftList = ob_get_clean();

$powers = getPowers($characterId, 'NonAffGift', NOTELEVEL, 3);
ob_start();
?>
    <table class="character-sheet <?php echo $table_class; ?>" id="nonaffgift_list">
        <tr>
            <th colspan="3">
                Non-Affinity Gifts
                <?php if ($edit_powers): ?>
                    <a href="#" onClick="addGift('nonaffgift');return false;">
                        <img src="/img/plus.png" title="Add Non-Affinity Gift"/>
                    </a>
                <?php endif; ?>
            </th>
        </tr>
        <tr>
            <td class="header-row">
                Gift List
            </td>
            <td class="header-row">
                Gift Name
            </td>
            <td class="header-row">
                Rank
            </td>
        </tr>
        <?php foreach ($powers as $i => $power): ?>
            <?php $dots = FormHelper::Dots("nonaffgift${i}", $power->getPowerLevel(),
                                           $element_type['supernatural'], $character_type, $max_dots,
                                           $edit_powers, false, $edit_xp); ?>
            <tr>
                <td>
                    <?php if ($edit_powers): ?>
                        <label for="nonaffgift<?php echo $i; ?>_note"></label><input type="text"
                                                                                     name="nonaffgift<?php echo $i; ?>_note"
                                                                                     id="nonaffgift<?php echo $i; ?>_note"
                                                                                     size="20"
                                                                                     value="<?php echo $power->getPowerNote(); ?>">
                    <?php else: ?>
                        <?php echo $power->getPowerNote(); ?>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if ($edit_powers): ?>
                        <label for="nonaffgift<?php echo $i; ?>_name"></label><input type="text"
                                                                                     name="nonaffgift<?php echo $i; ?>_name"
                                                                                     id="nonaffgift<?php echo $i; ?>_name"
                                                                                     size="15"
                                                                                     value="<?php echo $power->getPowerName(); ?>">
                    <?php else: ?>
                        <?php echo $power->getPowerName(); ?>
                    <?php endif; ?>
                </td>
                <td>
                    <?php echo $dots; ?>
                    <input type="hidden" name="nonaffgift<?php echo $i; ?>_id" id="nonaffgift<?php echo $i; ?>_id"
                           value="<?php echo $power->getPowerID(); ?>">
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php
$nonaffinityGiftList = ob_get_clean();

$renowns = getRenownsRituals($characterId);
$powers = getPowers($characterId, 'Ritual', NAMENOTE, 3);
ob_start();
?>
    <table class="character-sheet <?php echo $table_class; ?>" id="ritual_list">
        <tr>
            <th colspan="2">
                Rituals
                <?php if ($edit_powers): ?>
                    <a href="#" onClick="addRitual();return false;">
                        <img src="/img/plus.png" title="Add Ritual"/>
                    </a>
                <?php endif; ?>
            </th>
        </tr>
        <tr>
            <td>
                Rituals Level
            </td>
            <td>
                <?php echo FormHelper::Dots("rituals", $renowns["rituals"]->getPowerLevel(),
                                            $element_type['supernatural'], $character_type, $max_dots, $edit_powers,
                                            false, $edit_xp); ?>

            </td>
        </tr>
        <tr>
            <td class="header-row">
                Name
            </td>
            <td class="header-row">
                Rank
            </td>
        </tr>
        <?php foreach ($powers as $i => $power): ?>
            <?php $dots = FormHelper::Dots("ritual${i}", $power->getPowerLevel(),
                                           $element_type['merit'], $character_type, $max_dots,
                                           $edit_powers, false, $edit_xp); ?>
            <tr>
                <td>
                    <?php if ($edit_powers): ?>
                        <label for="ritual<?php echo $i; ?>_name"></label>
                        <input type="text"
                               name="ritual<?php echo $i; ?>_name"
                               id="ritual<?php echo $i; ?>_name"
                               size="20"
                               value="<?php echo $power->getPowerName(); ?>">
                    <?php else: ?>
                        <?php echo $power->getPowerName(); ?>
                    <?php endif; ?>
                </td>
                <td>
                    <?php echo $dots; ?>
                    <input type="hidden" name="ritual<?php echo $i; ?>_id" id="ritual<?php echo $i; ?>_id"
                           value="<?php echo $power->getPowerID(); ?>">
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php
$rituals = ob_get_clean();


$purity_dots = FormHelper::Dots("purity", $renowns["purity"]->getPowerLevel(), $element_type['supernatural'],
                                $character_type, $max_dots, $edit_powers, false, $edit_xp);
$purity_id = $renowns["purity"]->getPowerID();

$glory_dots = FormHelper::Dots("glory", $renowns["glory"]->getPowerLevel(), $element_type['supernatural'],
                               $character_type, $max_dots, $edit_powers, false, $edit_xp);
$glory_id = $renowns["glory"]->getPowerID();

$honor_dots = FormHelper::Dots("honor", $renowns["honor"]->getPowerLevel(), $element_type['supernatural'],
                               $character_type, $max_dots, $edit_powers, false, $edit_xp);
$honor_id = $renowns["honor"]->getPowerID();

$wisdom_dots = FormHelper::Dots("wisdom", $renowns["wisdom"]->getPowerLevel(), $element_type['supernatural'],
                                $character_type, $max_dots, $edit_powers, false, $edit_xp);
$wisdom_id = $renowns["wisdom"]->getPowerID();

$cunning_dots = FormHelper::Dots("cunning", $renowns["cunning"]->getPowerLevel(), $element_type['supernatural'],
                                 $character_type, $max_dots, $edit_powers, false, $edit_xp);
$cunning_id = $renowns["cunning"]->getPowerID();

ob_start();
?>
    <table class="character-sheet <?php echo $table_class; ?>">
        <tr>
            <th colspan="2">
                Renown
            </th>
        </tr>
        <tr>
            <td>
                Purity
            </td>
            <td>
                <?php echo $purity_dots; ?>
                <input type="hidden" name="purity_id" id="purity_id" value="<?php echo $purity_id; ?>">
            </td>
        </tr>
        <tr>
            <td>
                Glory
            </td>
            <td>
                <?php echo $glory_dots; ?>
                <input type="hidden" name="glory_id" id="glory_id" value="<?php echo $glory_id; ?>">
            </td>
        </tr>
        <tr>
            <td>
                Honor
            </td>
            <td>
                <?php echo $honor_dots; ?>
                <input type="hidden" name="honor_id" id="honor_id" value="<?php echo $honor_id; ?>">
            </td>
        </tr>
        <tr>
            <td>
                Wisdom
            </td>
            <td>
                <?php echo $wisdom_dots; ?>
                <input type="hidden" name="wisdom_id" id="wisdom_id" value="<?php echo $wisdom_id; ?>">
            </td>
        </tr>
        <tr>
            <td>
                Cunning
            </td>
            <td>
                <?php echo $cunning_dots; ?>
                <input type="hidden" name="cunning_id" id="cunning_id" value="<?php echo $cunning_id; ?>">
            </td>
        </tr>
    </table>
<?php
$renownList = ob_get_clean();

ob_start()
?>
    <div style="width:50%;float:left;">
        <?php echo $character_merit_list; ?>
        <?php echo $character_flaw_list; ?>
        <?php echo $characterMiscList; ?>
        <?php echo $rituals; ?>
    </div>
    <div style="width:50%;float:left;">
        <?php echo $renownList; ?>
        <?php echo $affinityGiftList; ?>
        <?php echo $nonaffinityGiftList; ?>
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
                Primal Urge
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
                Harmony
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
                Essence
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
    </table>
<?php
$traits_table = ob_get_clean();