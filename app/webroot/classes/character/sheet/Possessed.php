<?php
/**
 * Created by PhpStorm.
 * User: jvandenberg
 * Date: 9/8/2015
 * Time: 10:32 PM
 */

namespace classes\character\sheet;


use classes\core\helpers\FormHelper;

class Possessed extends SheetRenderer
{
    public function render(WodSheet $sheet, $character_name, $character_type_select, $location, $sex, $virtue, $vice,
                           $icon, $age, $is_npc, $status, $concept, $description, $equipment_public, $equipment_hidden,
                           $public_effects, $safe_place, $character_merit_list, $character_flaw_list,
                           $characterMiscList, $health_dots, $size, $wounds_bashing, $wounds_lethal, $wounds_aggravated,
                           $defense, $morality_dots, $initiative_mod, $willpower_perm_dots, $speed,
                           $willpower_temp_dots, $armor, $st_notes_table, $history_table, $skill_table,
                           $attribute_table, $show_sheet_table, $apparent_age, $power_trait_dots, $power_points_dots)
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
                    <b>Home</b>
                </td>
                <td>
                    <?php echo $safe_place; ?>
                </td>
            </tr>
        </table>
        <?php
        $information_table = ob_get_clean();

        // Vices
        // in clan
        $powers = $sheet->getPowers($sheet->stats['id'], 'ICDisc', WodSheet::NAMENOTE, 3);

        ob_start();
        ?>
        <table class="character-sheet <?php echo $sheet->table_class; ?>" id="icdisc_list">
            <tr>
                <th colspan="2">
                    Vices
                    <?php if ($sheet->viewOptions['edit_powers']): ?>
                        <a href="#" onClick="addDisc('icdisc');return false;">
                            <img src="/img/plus.png" title="Add Vice"/>
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
                    WodSheet::SUPERNATURAL, $sheet->stats['character_type'], $sheet->max_dots,
                    $sheet->viewOptions['edit_powers'], false, $sheet->viewOptions['xp_create_mode']); ?>
                <tr>
                    <td>
                        <?php if ($sheet->viewOptions['edit_powers']): ?>
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
        $viceList = ob_get_clean();

        // vestments - reuse vampire devotions
        $powers = $sheet->getPowers($sheet->stats['id'], 'Devotion', WodSheet::NAMENOTE, 2);
        $supernatural_xp_js = '';
        if ($sheet->viewOptions['xp_create_mode']) {
            $supernatural_xp_js = ' onChange="updateXP(' . WodSheet::SUPERNATURAL . ')" ';
        }
        ob_start();
        ?>
        <table class="character-sheet <?php echo $sheet->table_class; ?>" id="devotion_list">
            <tr>
                <th colspan="2">
                    Vestments
                    <?php if ($sheet->viewOptions['edit_powers']): ?>
                        <a href="#" onClick="addDevotion();return false;">
                            <img src="/img/plus.png" title="Add Vestment"/>
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
                        <?php if ($sheet->viewOptions['edit_powers']): ?>
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
                        <?php if ($sheet->viewOptions['edit_powers']): ?>
                            <label for="devotion<?php echo $i; ?>"></label><input type="text"
                                                                                  name="devotion<?php echo $i; ?>"
                                                                                  id="devotion<?php echo $i; ?>"
                                                                                  size="3"
                                                                                  maxlength="2"
                                                                                  value="<?php echo $level; ?>" <?php echo $supernatural_xp_js; ?>>
                        <?php else: ?>
                            <?php echo $level; ?>
                        <?php endif; ?>
                        <input type="hidden" name="devotion<?php echo $i; ?>_id" id="devotion<?php echo $i; ?>_id"
                               value="<?php echo $discipline_id; ?>">
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
        <?php
        $vestmentsList = ob_get_clean();

        ob_start();
        ?>
        <div style="width:50%;float:left;">
            <?php echo $character_merit_list; ?>
            <?php echo $character_flaw_list; ?>
        </div>
        <div style="width:50%;float:left;">
            <?php echo $viceList; ?>
            <?php echo $vestmentsList; ?>
            <?php echo $characterMiscList; ?>
        </div>
        <table class="character-sheet <?php echo $sheet->table_class; ?>" id="devotion_list">
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
                    Primary Vice
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
                    Demonic Willpower
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
