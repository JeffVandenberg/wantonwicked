<?php
/**
 * Created by PhpStorm.
 * User: jvandenberg
 * Date: 9/7/2015
 * Time: 11:59 PM
 */

namespace classes\character\sheet;


use classes\core\helpers\FormHelper;

class Promethean extends SheetRenderer
{
    public function render(WodSheet $sheet, $character_name, $character_type_select, $location, $sex, $virtue, $vice,
                           $icon, $age, $is_npc, $status, $concept, $description, $equipment_public, $equipment_hidden,
                           $public_effects, $safe_place, $character_merit_list, $character_flaw_list,
                           $characterMiscList, $health_dots, $size, $wounds_bashing, $wounds_lethal, $wounds_aggravated,
                           $defense, $morality_dots, $initiative_mod, $willpower_perm_dots, $speed,
                           $willpower_temp_dots, $armor, $st_notes_table, $history_table, $skill_table,
                           $attribute_table, $show_sheet_table, $splat1, $subsplat, $splat2, $friends,
                           $power_points_dots, $power_trait_dots)
    {
        ob_start();
        ?>
        <table class="character-sheet <?php echo $sheet->table_class; ?>">
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
                    <b>Lineage</b>
                </td>
                <td>
                    <?php echo $splat1; ?>
                </td>
                <td>
                    <b>Athanor</b>
                </td>
                <td>
                    <?php echo $subsplat; ?>
                </td>
            </tr>
            <tr>
                <td>
                    <b>Refinement</b>
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
                    <b>Throng</b>
                </td>
                <td>
                    <?php echo $friends; ?>
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
        <table class="character-sheet <?php echo $sheet->table_class; ?>">
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
                    <b>Current Lodgings</b>
                </td>
                <td>
                    <?php echo $safe_place; ?>
                </td>
            </tr>
        </table>
        <?php
        $information_table = ob_get_clean();


        // bestowments
        $powers = $sheet->getPowers($sheet->stats['id'], 'Bestowment', WodSheet::NAMELEVEL, 2);

        $supernatural_xp_js = '';
        if ($sheet->viewOptions['xp_create_mode']) {
            $supernatural_xp_js = ' onChange="updateXP(' . WodSheet::SUPERNATURAL . ')" ';
        }
        ob_start();
        ?>
        <table class="character-sheet <?php echo $sheet->table_class; ?>" id="bestowment_list">
            <tr>
                <th colspan="2">
                    Bestowments
                    <?php if ($sheet->viewOptions['edit_powers']): ?>
                        <a href="#" onClick="addBestowment();return false;">
                            <img src="/img/plus.png" title="Add Bestowment"/>
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
                <tr>
                    <td>
                        <?php if ($sheet->viewOptions['edit_powers']): ?>
                            <label for="bestowment<?php echo $i; ?>_name"></label>
                            <input type="text" name="bestowment<?php echo $i; ?>_name"
                                   id="bestowment<?php echo $i; ?>_name" size="15"
                                   value="<?php echo $power->getPowerName(); ?>">
                        <?php else: ?>
                            <?php echo $power->getPowerName(); ?>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($sheet->viewOptions['edit_powers']): ?>
                            <label for="bestowment<?php echo $i; ?>_cost"></label>
                            <input type="text" name="bestowment<?php echo $i; ?>_cost" id="bestowment<?php echo $i; ?>_cost"
                                   size="3"
                                   maxlength="2"
                                   value="<?php echo $power->getPowerLevel(); ?>" <?php echo $supernatural_xp_js; ?>>
                        <?php else: ?>
                            <?php echo $power->getPowerLevel(); ?>
                        <?php endif; ?>
                        <input type="hidden" name="bestowment<?php echo $i; ?>_id" id="devotion<?php echo $i; ?>_id"
                               value="<?php echo $power->getPowerID(); ?>">
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
        <?php
        $bestowments = ob_get_clean();


        $powers = getPowers($sheet->stats['id'], 'AffTrans', WodSheet::NOTELEVEL, 4);
        ob_start();
        ?>
        <table class="character-sheet <?php echo $sheet->table_class; ?>" id="afftrans_list">
            <tr>
                <th colspan="3">
                    Affinity Transmutations
                    <?php if ($sheet->viewOptions['edit_powers']): ?>
                        <a href="#" onClick="addTrans('afftrans');return false;">
                            <img src="/img/plus.png" title="Add Affinity Transmutation"/>
                        </a>
                    <?php endif; ?>
                </th>
            </tr>
            <tr>
                <td class="header-row">
                    List
                </td>
                <td class="header-row">
                    Name
                </td>
                <td class="header-row">
                    Level
                </td>
            </tr>
            <?php foreach ($powers as $i => $power): ?>
                <?php $dots = FormHelper::Dots("afftrans${i}", $power->getPowerLevel(),
                    WodSheet::SUPERNATURAL, $sheet->stats['character_type'], $sheet->max_dots,
                    $sheet->viewOptions['edit_powers'], false, $sheet->viewOptions['xp_create_mode']); ?>
                <tr>
                    <td>
                        <?php if ($sheet->viewOptions['edit_powers']): ?>
                            <label for="afftrans<?php echo $i; ?>_note"></label>
                            <input type="text"
                                   name="afftrans<?php echo $i; ?>_note"
                                   id="afftrans<?php echo $i; ?>_note"
                                   size="20"
                                   value="<?php echo $power->getPowerNote(); ?>">
                        <?php else: ?>
                            <?php echo $power->getPowerNote(); ?>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($sheet->viewOptions['edit_powers']): ?>
                            <label for="afftrans<?php echo $i; ?>_name"></label>
                            <input type="text" name="afftrans<?php echo $i; ?>_name" id="afftrans<?php echo $i; ?>_name"
                                   size="15" value="<?php echo $power->getPowerName(); ?>">
                        <?php else: ?>
                            <?php echo $power->getPowerName(); ?>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php echo $dots; ?>
                        <input type="hidden" name="afftrans<?php echo $i; ?>_id" id="affgift<?php echo $i; ?>_id"
                               value="<?php echo $power->getPowerID(); ?>">
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
        <?php
        $affTransList = ob_get_clean();

        $powers = getPowers($sheet->stats['id'], 'NonAffTrans', WodSheet::NOTELEVEL, 2);
        ob_start();
        ?>
        <table class="character-sheet <?php echo $sheet->table_class; ?>" id="nonafftrans_list">
            <tr>
                <th colspan="3">
                    Non-Affinity Transmutations
                    <?php if ($sheet->viewOptions['edit_powers']): ?>
                        <a href="#" onClick="addTrans('nonafftrans');return false;">
                            <img src="/img/plus.png" title="Add Non-Affinity Transmutation"/>
                        </a>
                    <?php endif; ?>
                </th>
            </tr>
            <tr>
                <td class="header-row">
                    List
                </td>
                <td class="header-row">
                    Name
                </td>
                <td class="header-row">
                    Level
                </td>
            </tr>
            <?php foreach ($powers as $i => $power): ?>
                <?php $dots = FormHelper::Dots("nonafftrans${i}", $power->getPowerLevel(),
                    WodSheet::SUPERNATURAL, $sheet->stats['character_type'], $sheet->max_dots,
                    $sheet->viewOptions['edit_powers'], false, $sheet->viewOptions['xp_create_mode']); ?>
                <tr>
                    <td>
                        <?php if ($sheet->viewOptions['edit_powers']): ?>
                            <label for="nonafftrans<?php echo $i; ?>_note"></label>
                            <input type="text"
                                   name="nonafftrans<?php echo $i; ?>_note"
                                   id="nonafftrans<?php echo $i; ?>_note"
                                   size="20"
                                   value="<?php echo $power->getPowerNote(); ?>">
                        <?php else: ?>
                            <?php echo $power->getPowerNote(); ?>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($sheet->viewOptions['edit_powers']): ?>
                            <label for="nonafftrans<?php echo $i; ?>_name"></label>
                            <input type="text" name="nonafftrans<?php echo $i; ?>_name"
                                   id="afftrans<?php echo $i; ?>_name"
                                   size="15" value="<?php echo $power->getPowerName(); ?>">
                        <?php else: ?>
                            <?php echo $power->getPowerName(); ?>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php echo $dots; ?>
                        <input type="hidden" name="nonafftrans<?php echo $i; ?>_id" id="affgift<?php echo $i; ?>_id"
                               value="<?php echo $power->getPowerID(); ?>">
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
        <?php
        $nonAffTransList = ob_get_clean();

        ob_start();
        ?>
        <div style="width:50%;float:left;">
            <?php echo $character_merit_list; ?>
            <?php echo $character_flaw_list; ?>
            <?php echo $characterMiscList; ?>
        </div>
        <div style="width:50%;float:left;">
            <?php echo $bestowments; ?>
            <?php echo $affTransList; ?>
            <?php echo $nonAffTransList; ?>
        </div>

        <table border="0" cellpadding="2" cellspacing="1" class="<?php echo $sheet->table_class; ?>" width="100%">
            <tr>
                <th colspan="6" align="center">
                    Traits
                </th>
            </tr>
            <tr>
                <td width="15%">
                    Health
                </td>
                <td colspan="2" width="30%">
                    <?php echo $health_dots; ?>
                </td>
                <td colspan="1" width="15%">
                    Wounds
                </td>
                <td colspan="2" width="40%" style="white-space: nowrap;">
                    Bashing: <?php echo $wounds_bashing; ?>
                    Lethal: <?php echo $wounds_lethal; ?>
                    Agg: <?php echo $wounds_aggravated; ?>
                </td>
            </tr>
            <tr>
                <td colspan="1">
                    Azoth
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
                    Pyros
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