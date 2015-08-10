<?php
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
                <b>Accord</b>
            </td>
            <td>
                <?php echo $splat1; ?>
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
                <b>Breed</b>
            </td>
            <td>
                <?php echo $splat2; ?>
            </td>
            <td>
                <b>Species</b>
            </td>
            <td>
                <?php echo $subsplat; ?>
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
                <b>Friends</b>
            </td>
            <td>
                <?php echo $friends; ?>
            </td>
        </tr>
        <tr>
            <td>
                <b>Safe Place</b>
            </td>
            <td>
                <?php echo $safe_place; ?>
            </td>
        </tr>
    </table>
<?php
$information_table = ob_get_clean();


$powers = getPowers($characterId, 'Aspect', NAMENOTE, 3);

ob_start();
?>
    <table class="character-sheet <?php echo $table_class; ?>" id="aspect_list">
        <tr>
            <th colspan="2">
                Aspects
                <?php if ($edit_powers): ?>
                    <a href="#" onClick="addAspect();return false;">
                        <img src="/img/plus.png" title="Add Aspect"/>
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
            <?php $dots = FormHelper::Dots("aspect${i}", $power->getPowerLevel(),
                $element_type['supernatural'], $character_type, $max_dots,
                $edit_powers, false, $edit_xp); ?>
            <tr>
                <td>
                    <?php if ($edit_powers): ?>
                        <label for="aspect<?php echo $i; ?>_name"></label><input type="text"
                                                                                 name="aspect<?php echo $i; ?>_name"
                                                                                 id="aspect<?php echo $i; ?>_name"
                                                                                 size="15"
                                                                                 value="<?php echo $power->getPowerName(); ?>">
                    <?php else: ?>
                        <?php echo $power->getPowerName(); ?>
                    <?php endif; ?>
                </td>
                <td>
                    <?php echo $dots; ?>
                    <input type="hidden" name="aspect<?php echo $i; ?>_id" id="aspect<?php echo $i; ?>_id"
                           value="<?php echo $power->getPowerID(); ?>">
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php
$aspects = ob_get_clean();

$updateJs = "";
if ($edit_xp) {
    $updateJs = " onChange=\"updateXP($element_type[supernatural])\" ";
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

$renowns = getRenownsRituals($characterId);

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

ob_start();
?>
    <div style="width:50%;float:left;">
        <?php echo $character_merit_list; ?>
        <?php echo $character_flaw_list; ?>
        <?php echo $characterMiscList; ?>
    </div>
    <div style="width:50%;float:left;">
        <?php echo $aspects; ?>
        <?php echo $renownList; ?>
        <?php echo $affinityGiftList; ?>
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
                Wounds
            </td>
            <td colspan="2" style="width:20%;white-space: nowrap;">
                Bashing: <?php echo $wounds_bashing; ?>
                Lethal: <?php echo $wounds_lethal; ?>
                Agg: <?php echo $wounds_aggravated; ?>
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
                Power Points
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