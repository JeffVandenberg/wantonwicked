<?php
/* @var string $table_class */
use classes\core\helpers\FormHelper;

/* @var string $table_class */

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
            <b>Path</b>
        </td>
        <td>
            $splat1
        </td>
        <td>
            <b>Legacy</b>
        </td>
        <td>
            $subsplat
        </td>
    </tr>
    <tr>
        <td>
            <b>Order</b>
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
            <b>Cabal</b>
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
            <b>Familiar</b>
        </td>
        <td>
            $helper
        </td>
    </tr>
    <tr>
        <td>
            <b>Sanctum/Hallow</b>
        </td>
        <td>
            $safe_place
        </td>
    </tr>
</table>
EOQ;


$powers = getPowers($characterId, 'RulingArcana', NAMENOTE, 2);

ob_start();
?>
    <table class="character-sheet <?php echo $table_class; ?>" id="rulingarcana_list">
        <tr>
            <th colspan="2">
                Ruling Arcana
                <?php if ($edit_powers): ?>
                    <a href="#" onClick="addArcana('rulingarcana');return false;">
                        <img src="/img/plus.png" title="Add Ruling Arcana"/>
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
            <?php $dots = FormHelper::Dots("rulingarcana${i}", $power->getPowerLevel(), $element_type['supernatural'],
                                           $character_type, $max_dots, $edit_powers, false, $edit_xp); ?>
            <tr>
                <td>
                    <?php if ($edit_powers): ?>
                        <label for="rulingarcana<?php echo $i; ?>_name"></label><input type="text"
                                                                                       name="rulingarcana<?php echo $i; ?>_name"
                                                                                       id="rulingarcana<?php echo $i; ?>_name"
                                                                                       size="15"
                                                                                       value="<?php echo $power->getPowerName(); ?>">
                    <?php else: ?>
                        <?php echo $power->getPowerName(); ?>
                    <?php endif; ?>
                </td>
                <td>
                    <?php echo $dots; ?>
                    <input type="hidden" name="rulingarcana<?php echo $i; ?>_id" id="rulingarcana<?php echo $i; ?>_id"
                           value="<?php echo $power->getPowerID(); ?>">
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php
$rulingArcana = ob_get_clean();

$powers = getPowers($characterId, 'CommonArcana', NAMENOTE, 2);

ob_start();
?>
    <table class="character-sheet <?php echo $table_class; ?>" id="commonarcana_list">
        <tr>
            <th colspan="2">
                Common Arcana
                <?php if ($edit_powers): ?>
                    <a href="#" onClick="addArcana('commonarcana');return false;">
                        <img src="/img/plus.png" title="Add Common Arcana"/>
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
            <?php $dots = FormHelper::Dots("commonarcana${i}", $power->getPowerLevel(), $element_type['supernatural'],
                                           $character_type, $max_dots, $edit_powers, false, $edit_xp); ?>
            <tr>
                <td>
                    <?php if ($edit_powers): ?>
                        <label for="commonarcana<?php echo $i; ?>_name"></label><input type="text"
                                                                                       name="commonarcana<?php echo $i; ?>_name"
                                                                                       id="commonarcana<?php echo $i; ?>_name"
                                                                                       size="15"
                                                                                       value="<?php echo $power->getPowerName(); ?>">
                    <?php else: ?>
                        <?php echo $power->getPowerName(); ?>
                    <?php endif; ?>
                </td>
                <td>
                    <?php echo $dots; ?>
                    <input type="hidden" name="commonarcana<?php echo $i; ?>_id" id="commonarcana<?php echo $i; ?>_id"
                           value="<?php echo $power->getPowerID(); ?>">
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php
$commonArcana = ob_get_clean();

$powers = getPowers($characterId, 'InferiorArcana', NAMENOTE, 1);

ob_start();
?>
    <table class="character-sheet <?php echo $table_class; ?>" id="inferiorarcana_list">
        <tr>
            <th colspan="2">
                Inferior Arcana
                <?php if ($edit_powers): ?>
                    <a href="#" onClick="addArcana('inferiorarcana');return false;">
                        <img src="/img/plus.png" title="Add Inferior Arcana"/>
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
            <?php $dots = FormHelper::Dots("inferiorarcana${i}", $power->getPowerLevel(), $element_type['supernatural'],
                                           $character_type, $max_dots, $edit_powers, false, $edit_xp); ?>
            <tr>
                <td>
                    <?php if ($edit_powers): ?>
                        <label for="inferiorarcana<?php echo $i; ?>_name"></label><input type="text"
                                                                                         name="inferiorarcana<?php echo $i; ?>_name"
                                                                                         id="inferiorarcana<?php echo $i; ?>_name"
                                                                                         size="15"
                                                                                         value="<?php echo $power->getPowerName(); ?>">
                    <?php else: ?>
                        <?php echo $power->getPowerName(); ?>
                    <?php endif; ?>
                </td>
                <td>
                    <?php echo $dots; ?>
                    <input type="hidden" name="inferiorarcana<?php echo $i; ?>_id"
                           id="inferiorarcana<?php echo $i; ?>_id"
                           value="<?php echo $power->getPowerID(); ?>">
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php
$inferiorArcana = ob_get_clean();


$powers = getPowers($characterId, 'Rote', NAMENOTE, 5);
$supernatural_xp_js = "";
if ($edit_xp) {
    $supernatural_xp_js = " onChange=\"updateXP($element_type[supernatural])\" ";
}

ob_start();
?>
    <table class="character-sheet <?php echo $table_class; ?>" id="rote_list">
        <tr>
            <th colspan="3">
                Rotes
                <?php if ($edit_powers): ?>
                    <a href="#" onClick="addRote();return false;">
                        <img src="/img/plus.png" title="Add Rote"/>
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
            <?php $dots = FormHelper::Dots("inferiorarcana${i}", $power->getPowerLevel(), $element_type['supernatural'],
                                           $character_type, $max_dots, $edit_powers, false, $edit_xp); ?>
            <tr>
                <td>
                    <?php if ($edit_powers): ?>
                        <label for="rote<?php echo $i; ?>_name"></label><input type="text"
                                                                               name="rote<?php echo $i; ?>_name"
                                                                               id="rote<?php echo $i; ?>_name"
                                                                               size="15"
                                                                               value="<?php echo $power->getPowerName(); ?>">
                    <?php else: ?>
                        <?php echo $power->getPowerName(); ?>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if ($edit_powers): ?>
                        <label for="rote<?php echo $i; ?>_note"></label><input type="text"
                                                                               name="rote<?php echo $i; ?>_note"
                                                                               id="rote<?php echo $i; ?>_note"
                                                                               size="15"
                                                                               value="<?php echo $power->getPowerNote(); ?>">
                    <?php else: ?>
                        <?php echo $power->getPowerNote(); ?>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if ($edit_powers): ?>
                        <input type="text" name="rote<?php echo $i; ?>" id="rote<?php echo $i; ?>"
                               size="3" maxlength="2" <?php echo $supernatural_xp_js; ?>
                               value="<?php echo $power->getPowerLevel(); ?>">
                    <?php else: ?>
                        <?php echo $power->getPowerLevel(); ?>
                    <?php endif; ?>
                    <input type="hidden" name="rote<?php echo $i; ?>_id"
                           id="rote<?php echo $i; ?>_id"
                           value="<?php echo $power->getPowerID(); ?>">
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php
$rotes = ob_get_clean();

ob_start();
?>
    <div style="width:50%;float:left;">
        <?php echo $character_merit_list; ?>
    </div>
    <div style="width:50%;float:left;">
        <?php echo $character_flaw_list; ?>
        <?php echo $characterMiscList; ?>
    </div>
    <div style="width:50%;float:left;clear: both;">
        <?php echo $rulingArcana; ?>
        <?php echo $commonArcana; ?>
        <?php echo $inferiorArcana; ?>
    </div>
    <div style="width:50%;float:left;">
        <?php echo $rotes; ?>
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
                Gnosis
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
                Wisdom
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
                Mana
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