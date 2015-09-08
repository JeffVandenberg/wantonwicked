<?php
/**
 * Created by PhpStorm.
 * User: jvandenberg
 * Date: 9/7/2015
 * Time: 11:47 PM
 */

namespace classes\character\sheet;


use classes\core\helpers\FormHelper;

class Changeling extends SheetRenderer
{
    public function render(WodSheet $sheet, $character_name, $character_type_select, $location, $sex, $virtue, $vice,
                           $icon, $age, $is_npc, $status, $concept, $description, $equipment_public, $equipment_hidden,
                           $public_effects, $safe_place, $character_merit_list, $character_flaw_list,
                           $characterMiscList, $health_dots, $size, $wounds_bashing, $wounds_lethal, $wounds_aggravated,
                           $defense, $morality_dots, $initiative_mod, $willpower_perm_dots, $speed,
                           $willpower_temp_dots, $armor, $st_notes_table, $history_table, $skill_table,
                           $attribute_table, $show_sheet_table, $splat1, $subsplat, $splat2, $friends,
                           $power_points_dots, $power_trait_dots, $apparent_age)
    {
        ob_start()
        ?>
        <table class="character-sheet <?php echo $sheet->table_class; ?>" width="100%">
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
                    <b>Sex</b>
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
                    <b>Seeming</b>
                </td>
                <td>
                    <?php echo $splat1; ?>
                </td>
                <td>
                    <b>Kith</b>
                </td>
                <td>
                    <?php echo $subsplat; ?>
                </td>
            </tr>
            <tr>
                <td>
                    <b>Court</b>
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
                    <b>Years Missing (real)</b>
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
        <table class="character-sheet <?php echo $sheet->table_class; ?>" width="100%">
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
                    <b>Motley</b>
                </td>
                <td>
                    <?php echo $friends; ?>
                </td>
            </tr>
            <tr>
                <td>
                    <b>Hollow</b>
                </td>
                <td>
                    <?php echo $safe_place; ?>
                </td>
            </tr>
        </table>
        <?php
        $information_table = ob_get_clean();

        // affinity contracts
        $powers = getPowers($sheet->stats['id'], 'AffContract', WodSheet::NAMENOTE, 3);
        ob_start();
        ?>
        <table class="character-sheet <?php echo $sheet->table_class; ?>" id="affcont_list">
            <tr>
                <th colspan="3">
                    Affinity Contracts
                    <?php if ($sheet->viewOptions['edit_powers']): ?>
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
                    WodSheet::SUPERNATURAL, $sheet->stats['character_type'], $sheet->max_dots,
                    $sheet->viewOptions['edit_powers'], false, $sheet->viewOptions['xp_create_mode']); ?>
                <tr>
                    <td>
                        <?php if ($sheet->viewOptions['edit_powers']): ?>
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
                        <?php if ($sheet->viewOptions['edit_powers']): ?>
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
        $powers = getPowers($sheet->stats['id'], 'NonAffContract', NAMENOTE, 3);
        ob_start();
        ?>
        <table class="character-sheet <?php echo $sheet->table_class; ?>" id="nonaffcont_list">
            <tr>
                <th colspan="3">
                    Non-Affinity Contracts
                    <?php if ($sheet->viewOptions['edit_powers']): ?>
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
                    WodSheet::SUPERNATURAL, $sheet->stats['character_type'], $sheet->max_dots,
                    $sheet->viewOptions['edit_powers'], false, $sheet->viewOptions['xp_create_mode']); ?>
                <tr>
                    <td>
                        <?php if ($sheet->viewOptions['edit_powers']): ?>
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
                        <?php if ($sheet->viewOptions['edit_powers']): ?>
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

        // Goblin Contracts
        $powers = getPowers($sheet->stats['id'], 'GoblinContract', NAMENOTE, 2);
        ob_start();
        ?>
        <table class="character-sheet <?php echo $sheet->table_class; ?>" id="gobcont_list">
            <tr>
                <th colspan="3">
                    Goblin Contracts
                    <?php if ($sheet->viewOptions['edit_powers']): ?>
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
                    WodSheet::SUPERNATURAL, $sheet->stats['character_type'], $sheet->max_dots,
                    $sheet->viewOptions['edit_powers'], false, $sheet->viewOptions['xp_create_mode']); ?>
                <tr>
                    <td>
                        <?php if ($sheet->viewOptions['edit_powers']): ?>
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
        <div style="width:50%;float:left;">
            <?php echo $character_merit_list; ?>
            <?php echo $character_flaw_list; ?>
            <?php echo $characterMiscList; ?>
        </div>
        <div style="width:50%;float:left;">
            <?php echo $affinityContracts; ?>
            <?php echo $nonaffinityContracts; ?>
            <?php echo $goblinContracts; ?>
        </div>
        <table class="character-sheet <?php echo $sheet->table_class; ?>">
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
                    Wyrd
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
                    Clarity
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
                    Glamour
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

        return $this->renderSheet($sheet, $show_sheet_table, $vitals_table, $information_table, $attribute_table,
            $skill_table, $traits_table, $history_table, $st_notes_table);
    }
}