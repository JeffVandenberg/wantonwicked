<?php
use classes\core\helpers\FormHelper;

$vitals_table = <<<EOQ
<table class="character-sheet $table_class" width="100%">
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
            <b>Sex</b>
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
            <b>Seeming</b>
        </td>
        <td>
            $splat1
        </td>
        <td>
            <b>Kith</b>
        </td>
        <td>
            $subsplat
        </td>
    </tr>
    <tr>
        <td>
            <b>Court</b>
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
            <b>Years Missing (real)</b>
        </td>
        <td>
            $apparent_age
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
<table class="character-sheet $table_class" width="100%">
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
            <b>URL</b>
        </td>
        <td>
            $url
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
            <b>Motley</b>
        </td>
        <td>
            $friends
        </td>
    </tr>
    <tr>
        <td>
            <b>Hollow</b>
        </td>
        <td>
            $safe_place
        </td>
    </tr>
    <tr>
        <td>
            <b>Exit Line</b>
        </td>
        <td>
            $exit_line
        </td>
    </tr>
</table>
EOQ;

// affinity contracts
$powers = getPowers($characterId, 'AffContract', NAMENOTE, 3);
ob_start();
?>
    <table class="character-sheet <?php echo $table_class; ?>" id="affcont_list">
        <tr>
            <th colspan="3">
                Affinity Contracts
                <?php if ($edit_powers): ?>
                    <a href="#" onClick="addContract('affcont');return false;">
                        <img src="/img/plus.png" title="Add Affinity Contract"/>
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
            <?php $dots = FormHelper::Dots("affcont${i}", $power->getPowerLevel(),
                                           $element_type['supernatural'], $character_type, $max_dots,
                                           $edit_powers, false, $edit_xp); ?>
            <tr>
                <td>
                    <?php if ($edit_powers): ?>
                        <label for="affcont<?php echo $i; ?>_name"></label>
                        <input type="text"
                               name="affcont<?php echo $i; ?>_name"
                               id="affcont<?php echo $i; ?>_name"
                               size="20"
                               value="<?php echo $power->getPowerName(); ?>">
                    <?php else: ?>
                        <?php echo $power->getPowerName(); ?>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if ($edit_powers): ?>
                        <label for="affcont<?php echo $i; ?>_note"></label>
                        <input type="text"
                               name="affcont<?php echo $i; ?>_note"
                               id="affcont<?php echo $i; ?>_note"
                               size="15"
                               value="<?php echo $power->getPowerNote(); ?>">
                    <?php else: ?>
                        <?php echo $power->getPowerNote(); ?>
                    <?php endif; ?>
                </td>
                <td>
                    <?php echo $dots; ?>
                    <input type="hidden" name="affcont<?php echo $i; ?>_id" id="affcont<?php echo $i; ?>_id"
                           value="<?php echo $power->getPowerID(); ?>">
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php
$affinityContracts = ob_get_clean();

// nonaffinity contracts
$powers = getPowers($characterId, 'NonAffContract', NAMENOTE, 3);
ob_start();
?>
    <table class="character-sheet <?php echo $table_class; ?>" id="nonaffcont_list">
        <tr>
            <th colspan="3">
                Non-Affinity Contracts
                <?php if ($edit_powers): ?>
                    <a href="#" onClick="addContract('nonaffcont');return false;">
                        <img src="/img/plus.png" title="Add Non-Affinity Contract"/>
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
            <?php $dots = FormHelper::Dots("nonaffcont${i}", $power->getPowerLevel(),
                                           $element_type['supernatural'], $character_type, $max_dots,
                                           $edit_powers, false, $edit_xp); ?>
            <tr>
                <td>
                    <?php if ($edit_powers): ?>
                        <label for="nonaffcont<?php echo $i; ?>_name"></label>
                        <input type="text"
                               name="nonaffcont<?php echo $i; ?>_name"
                               id="nonaffcont<?php echo $i; ?>_name"
                               size="20"
                               value="<?php echo $power->getPowerName(); ?>">
                    <?php else: ?>
                        <?php echo $power->getPowerName(); ?>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if ($edit_powers): ?>
                        <label for="nonaffcont<?php echo $i; ?>_note"></label>
                        <input type="text"
                               name="nonaffcont<?php echo $i; ?>_note"
                               id="nonaffcont<?php echo $i; ?>_note"
                               size="15"
                               value="<?php echo $power->getPowerNote(); ?>">
                    <?php else: ?>
                        <?php echo $power->getPowerNote(); ?>
                    <?php endif; ?>
                </td>
                <td>
                    <?php echo $dots; ?>
                    <input type="hidden" name="nonaffcont<?php echo $i; ?>_id" id="affcont<?php echo $i; ?>_id"
                           value="<?php echo $power->getPowerID(); ?>">
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php
$nonaffinityContracts = ob_get_clean();

// goblin contracts
// Goblin Contracts
$supernatural_xp_js = "";
if ($edit_xp) {
    $supernatural_xp_js = " onChange=\"updateXP($element_type[supernatural])\" ";
}
$powers = getPowers($characterId, 'GoblinContract', NAMENOTE, 2);
ob_start();
?>
    <table class="character-sheet <?php echo $table_class; ?>" id="gobcont_list">
        <tr>
            <th colspan="3">
                Goblin Contracts
                <?php if ($edit_powers): ?>
                    <a href="#" onClick="addContract('gobcont');return false;">
                        <img src="/img/plus.png" title="Add Goblin Contract"/>
                    </a>
                <?php endif; ?>
            </th>
        </tr>
        <tr>
            <td class="header-row">
                Name
            </td>
            <td class="header-row">
                Level
            </td>
        </tr>
        <?php foreach ($powers as $i => $power): ?>
            <?php $dots = FormHelper::Dots("gobcont${i}", $power->getPowerLevel(),
                                           $element_type['supernatural'], $character_type, $max_dots,
                                           $edit_powers, false, $edit_xp); ?>
            <tr>
                <td>
                    <?php if ($edit_powers): ?>
                        <label for="gobcont<?php echo $i; ?>_name"></label>
                        <input type="text"
                               name="gobcont<?php echo $i; ?>_name"
                               id="gobcont<?php echo $i; ?>_name"
                               size="20"
                               value="<?php echo $power->getPowerName(); ?>">
                    <?php else: ?>
                        <?php echo $power->getPowerName(); ?>
                    <?php endif; ?>
                </td>
                <td>
                    <?php echo $dots; ?>
                    <input type="hidden" name="gobcont<?php echo $i; ?>_id" id="affcont<?php echo $i; ?>_id"
                           value="<?php echo $power->getPowerID(); ?>">
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php
$goblinContracts = ob_get_clean();

ob_start();
?>

<?php
$traits_table = ob_get_clean();

$traits_table = <<<EOQ
<table class="character-sheet $table_class"  width="100%">
    <tr>
        <th colspan="3" align="center" width="50%">
            Merits
			$abilities_help
        </th>
        <th colspan="3" align="center" width="50%">
            Contracts
			$abilities_help
        </th>
    </tr>
    <tr valign="top">
        <td colspan="3">
            $character_merit_list
        </td>
        <td colspan="3" rowspan="3">
            $power_list
        </td>
    </tr>
    <tr>
        <th colspan="3" align="center">
            Flaws/Derangements
			$abilities_help
        </th>
    </tr>
    <tr>
        <td colspan="3" valign="top">
            $character_flaw_list
        </td>
    </tr>
</table>
<table class="character-sheet $table_class"  width="100%">
    <tr>
        <th colspan="6">
            Traits
        </th>
    </tr>
    <tr>
        <td width="15%">
            Health
        </td>
        <td colspan="2" width="30%">
            $health_dots
        </td>
        <td colspan="1" width="15%">
            Wounds
        </td>
        <td colspan="2" width="40%">
            Bashing: $wounds_bashing Lethal: $wounds_lethal Agg: $wounds_aggravated
        </td>
    </tr>
    <tr>
        <td colspan="1">
            Wyrd
        </td>
        <td colspan="2">
            $power_trait_dots
        </td>
        <td colspan="1">
            Size
        </td>
        <td colspan="2">
            $size
        </td>
    </tr>
    <tr>
        <td colspan="1">
            Clarity
        </td>
        <td colspan="2">
            $morality_dots
        </td>
        <td colspan="1">
            Defense
        </td>
        <td colspan="2">
            $defense
        </td>
    </tr>
    <tr>
        <td>
            Willpower Perm
        </td>
        <td colspan="2">
            $willpower_perm_dots
        </td>
        <td colspan="1">
            Initiative Mod
        </td>
        <td colspan="2">
            $initiative_mod
        </td>
    </tr>
    <tr>
        <td>
            Willpower Temp
        </td>
        <td colspan="2">
            $willpower_temp_dots
        </td>
        <td colspan="1">
            Speed
        </td>
        <td colspan="2">
            $speed
        </td>
    </tr>
    <tr>
        <td>
            Glamour
        </td>
        <td colspan="2">
            $power_points_dots
        </td>
        <td colspan="1">
            Armor
        </td>
        <td colspan="2">
            $armor
        </td>
    </tr>
</table>
EOQ;
