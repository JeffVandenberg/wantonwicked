<?php
/**
 * Created by PhpStorm.
 * User: jvandenberg
 * Date: 9/7/2015
 * Time: 12:33 AM
 */

namespace classes\character\sheet;


use classes\core\helpers\FormHelper;

class Mage extends SheetRenderer
{
    public function render(WodSheet $sheet, $character_name, $character_type_select, $location, $sex, $virtue,
                               $vice, $icon, $age, $is_npc, $status, $concept, $description, $equipment_public,
                               $equipment_hidden, $public_effects, $safe_place, $character_merit_list,
                               $character_flaw_list, $characterMiscList, $health_dots, $size, $wounds_bashing,
                               $wounds_lethal, $wounds_aggravated, $defense, $morality_dots, $initiative_mod,
                               $willpower_perm_dots, $speed, $willpower_temp_dots, $armor, $st_notes_table,
                               $history_table, $skill_table, $attribute_table, $show_sheet_table, $splat1,
                               $subsplat, $splat2, $friends, $helper, $power_points_dots, $power_trait_dots)
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
                    <b>Path</b>
                </td>
                <td>
                    <?php echo $splat1; ?>
                </td>
                <td>
                    <b>Legacy</b>
                </td>
                <td>
                    <?php echo $subsplat; ?>
                </td>
            </tr>
            <tr>
                <td>
                    <b>Order</b>
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
                    <b>Cabal</b>
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
                    <b>Familiar</b>
                </td>
                <td>
                    <?php echo $helper; ?>
                </td>
            </tr>
            <tr>
                <td>
                    <b>Sanctum/Hallow</b>
                </td>
                <td>
                    <?php echo $safe_place; ?>
                </td>
            </tr>
        </table>
        <?php
        $information_table = ob_get_clean();


        $powers = $sheet->getPowers($sheet->stats['id'], 'RulingArcana', WodSheet::NAMENOTE, 2);

        ob_start();
        ?>
        <table class="character-sheet <?php echo $sheet->table_class; ?>" id="rulingarcana_list">
            <tr>
                <th colspan="2">
                    Ruling Arcana
                    <?php if ($sheet->viewOptions['edit_powers']): ?>
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
                <?php $dots = FormHelper::Dots("rulingarcana${i}", $power->getPowerLevel(), WodSheet::Supernatural,
                    $sheet->stats['character_type'], $sheet->max_dots, $sheet->viewOptions['edit_powers'], false,
                    $sheet->viewOptions['xp_create_mode']); ?>
                <tr>
                    <td>
                        <?php if ($sheet->viewOptions['edit_powers']): ?>
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
                        <input type="hidden" name="rulingarcana<?php echo $i; ?>_id"
                               id="rulingarcana<?php echo $i; ?>_id"
                               value="<?php echo $power->getPowerID(); ?>">
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
        <?php
        $rulingArcana = ob_get_clean();

        $powers = $sheet->getPowers($sheet->stats['id'], 'CommonArcana', WodSheet::NAMENOTE, 2);

        ob_start();
        ?>
        <table class="character-sheet <?php echo $sheet->table_class; ?>" id="commonarcana_list">
            <tr>
                <th colspan="2">
                    Common Arcana
                    <?php if ($sheet->viewOptions['edit_powers']): ?>
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
                <?php $dots = FormHelper::Dots("commonarcana${i}", $power->getPowerLevel(), WodSheet::Supernatural,
                    $sheet->stats['character_type'], $sheet->max_dots, $sheet->viewOptions['edit_powers'], false,
                    $sheet->viewOptions['xp_create_mode']); ?>
                <tr>
                    <td>
                        <?php if ($sheet->viewOptions['edit_powers']): ?>
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
                        <input type="hidden" name="commonarcana<?php echo $i; ?>_id"
                               id="commonarcana<?php echo $i; ?>_id"
                               value="<?php echo $power->getPowerID(); ?>">
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
        <?php
        $commonArcana = ob_get_clean();

        $powers = $sheet->getPowers($sheet->stats['id'], 'InferiorArcana', WodSheet::NAMENOTE, 1);

        ob_start();
        ?>
        <table class="character-sheet <?php echo $sheet->table_class; ?>" id="inferiorarcana_list">
            <tr>
                <th colspan="2">
                    Inferior Arcana
                    <?php if ($sheet->viewOptions['edit_powers']): ?>
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
                <?php $dots = FormHelper::Dots("inferiorarcana${i}", $power->getPowerLevel(), WodSheet::Supernatural,
                    $sheet->stats['character_type'], $sheet->max_dots, $sheet->viewOptions['edit_powers'], false,
                    $sheet->viewOptions['xp_create_mode']); ?>
                <tr>
                    <td>
                        <?php if ($sheet->viewOptions['edit_powers']): ?>
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


        $powers = $sheet->getPowers($sheet->stats['id'], 'Rote', WodSheet::NAMENOTE, 5);
        $supernatural_xp_js = "";
        if ($sheet->viewOptions['xp_create_mode']) {
            $supernatural_xp_js = ' onChange="updateXP(' . WodSheet::Supernatural . ')" ';
        }

        ob_start();
        ?>
        <table class="character-sheet <?php echo $sheet->table_class; ?>" id="rote_list">
            <tr>
                <th colspan="3">
                    Rotes
                    <?php if ($sheet->viewOptions['edit_powers']): ?>
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
                <tr>
                    <td>
                        <?php if ($sheet->viewOptions['edit_powers']): ?>
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
                        <?php if ($sheet->viewOptions['edit_powers']): ?>
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
                        <?php if ($sheet->viewOptions['edit_powers']): ?>
                            <label for="rote<?php echo $i; ?>">
                                <input type="text" name="rote<?php echo $i; ?>" id="rote<?php echo $i; ?>" size="3"
                                       maxlength="2" <?php echo $supernatural_xp_js; ?>
                                       value="<?php echo $power->getPowerLevel(); ?>">
                            </label>
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

        return $this->renderSheet($sheet, $show_sheet_table, $vitals_table, $information_table, $attribute_table,
            $skill_table, $traits_table, $history_table, $st_notes_table);
    }

}