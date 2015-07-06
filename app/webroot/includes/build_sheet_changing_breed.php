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
                <b>Splat 1</b>
            </td>
            <td>
                <?php echo $splat1; ?>
            </td>
            <td>
                <b>Subsplat</b>
            </td>
            <td>
                <?php echo $subsplat; ?>
            </td>
        </tr>
        <tr>
            <td>
                <b>Splat 2</b>
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


$powers = getPowers($characterId, 'Aspect', NAMENOTE, 5);

ob_start();
?>
    <table class="character-sheet <?php echo $table_class; ?>" id="aspect_list">
        <tr>
            <th colspan="2">
                Aspects
                <?php if ($edit_powers): ?>
                    <a href="#" onClick="addAspect('icdisc');return false;">
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

ob_start();
?>
    <div style="width:50%;float:left;">
        <?php echo $character_merit_list; ?>
        <?php echo $character_flaw_list; ?>
        <?php echo $characterMiscList; ?>
    </div>
    <div style="width:50%;float:left;">
        <?php echo $aspects; ?>
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
                Power Trait
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
                Morality
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