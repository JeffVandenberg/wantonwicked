<?php
/**
 * Created by PhpStorm.
 * User: jvandenberg
 * Date: 12/6/15
 * Time: 5:11 PM
 */

namespace classes\character\sheet;


use classes\core\helpers\FormHelper;

class Spirit extends SheetRenderer
{
    public function render(WodSheet $sheet, $character_name, $character_type_select, $location,
                           $icon, $is_npc, $status, $concept, $description, $safe_place,
                           $health_dots, $size, $wounds_bashing, $wounds_lethal, $wounds_aggravated,
                           $initiative_mod, $willpower_perm_dots, $speed, $willpower_temp_dots, $armor, $st_notes_table,
                           $history_table, $show_sheet_table,
                           $friends, $power_points_dots, $power_trait_dots, $attributes)
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
                    <b>Icon</b>
                </td>
                <td>
                    <?php echo $icon; ?>
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
                <th colspan="2" style="text-align: center;">
                    Attributes
                    <input type="hidden" name="bonus_attribute" id="bonus_attribute" value="" />
                </th>
            </tr>
            <tr>
                <td>
                    <b>Power</b>
                </td>
                <td>
                    <?php
                    echo $sheet->makeBaseStatDots($attributes, 'Power', 'attribute', 0,
                        WodSheet::ATTRIBUTE, 'spirit', $sheet->viewOptions['edit_attributes'],
                        $sheet->viewOptions['calculate_derived'],
                        $sheet->viewOptions['xp_create_mode'], 15);
                    ?>
                </td>
            </tr>
            <tr>
                <td>
                    <b>Finesse</b>
                </td>
                <td>
                    <?php
                    echo $sheet->makeBaseStatDots($attributes, 'Finesse', 'attribute', 1,
                        WodSheet::ATTRIBUTE, 'spirit', $sheet->viewOptions['edit_attributes'],
                        $sheet->viewOptions['calculate_derived'],
                        $sheet->viewOptions['xp_create_mode'], 15);
                    ?>
                </td>
            </tr>
            <tr>
                <td>
                    <b>Resistance</b>
                </td>
                <td>
                    <?php
                    echo $sheet->makeBaseStatDots($attributes, 'Resistance', 'attribute', 2,
                        WodSheet::ATTRIBUTE, 'spirit', $sheet->viewOptions['edit_attributes'],
                        $sheet->viewOptions['calculate_derived'],
                        $sheet->viewOptions['xp_create_mode'], 15);
                    ?>
                </td>
            </tr>
        </table>
        <?php
        $attribute_table = ob_get_clean();

        $skill_table = '';

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
                    <b>Attached Character</b>
                </td>
                <td>
                    <?php echo $friends; ?>
                </td>
            </tr>
            <tr>
                <td>
                    <b>Territory/Loci</b>
                </td>
                <td>
                    <?php echo $safe_place; ?>
                </td>
            </tr>
        </table>
        <?php
        $information_table = ob_get_clean();

        $powers = $sheet->getPowers($sheet->stats['id'], 'Influence', WodSheet::NAMELEVEL, 3);
        ob_start();
        ?>
        <table class="character-sheet <?php echo $sheet->table_class; ?>" id="influence_list">
            <tr>
                <th colspan="3">
                    Influences
                    <?php if ($sheet->viewOptions['edit_powers']): ?>
                        <a href="#" onClick="addInfluence();return false;">
                            <img src="/img/plus.png" title="Add Influence"/>
                        </a>
                    <?php endif; ?>
                </th>
            </tr>
            <tr>
                <td class="header-row">
                    Influence
                </td>
                <td class="header-row">
                    Level
                </td>
            </tr>
            <?php foreach ($powers as $i => $power): ?>
                <?php $dots = FormHelper::Dots("influence${i}", $power->getPowerLevel(),
                    WodSheet::SUPERNATURAL, $sheet->stats['character_type'], $sheet->max_dots,
                    $sheet->viewOptions['edit_powers'], false, $sheet->viewOptions['xp_create_mode']); ?>
                <tr>
                    <td>
                        <?php if ($sheet->viewOptions['edit_powers']): ?>
                            <label for="influence<?php echo $i; ?>_note"></label><input type="text"
                                                                                      name="influence<?php echo $i; ?>_name"
                                                                                      id="influence<?php echo $i; ?>_name"
                                                                                      size="20"
                                                                                      value="<?php echo $power->getPowerName(); ?>">
                        <?php else: ?>
                            <?php echo $power->getPowerName(); ?>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php echo $dots; ?>
                        <input type="hidden" name="influence<?php echo $i; ?>_id" id="influence<?php echo $i; ?>_id"
                               value="<?php echo $power->getPowerID(); ?>">
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
        <?php
        $influences = ob_get_clean();

        $powers = $sheet->getPowers($sheet->stats['id'], 'Numina', WodSheet::NAMELEVEL, 3);
        ob_start();
        ?>
        <table class="character-sheet <?php echo $sheet->table_class; ?>" id="numina_list">
            <tr>
                <th colspan="3">
                    Numina
                    <?php if ($sheet->viewOptions['edit_powers']): ?>
                        <a href="#" onClick="addNumina();return false;">
                            <img src="/img/plus.png" title="Add Numina"/>
                        </a>
                    <?php endif; ?>
                </th>
            </tr>
            <tr>
                <td class="header-row">
                    Numina
                </td>
            </tr>
            <?php foreach ($powers as $i => $power): ?>
                <tr>
                    <td>
                        <?php if ($sheet->viewOptions['edit_powers']): ?>
                            <label for="numina<?php echo $i; ?>_name"></label><input type="text"
                                                                                      name="numina<?php echo $i; ?>_name"
                                                                                      id="numina<?php echo $i; ?>_name"
                                                                                      size="20"
                                                                                      value="<?php echo $power->getPowerName(); ?>">
                        <?php else: ?>
                            <?php echo $power->getPowerName(); ?>
                        <?php endif; ?>
                        <input type="hidden" name="numina<?php echo $i; ?>_id" id="numina<?php echo $i; ?>_id"
                               value="<?php echo $power->getPowerID(); ?>">
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
        <?php
        $numina = ob_get_clean();

        ob_start()
        ?>
        <div style="width:50%;float:left;">
            <?php echo $influences; ?>
        </div>
        <div style="width:50%;float:left;">
            <?php echo $numina; ?>
        </div>
        <table class="character-sheet <?php echo $sheet->table_class; ?>">
            <tr>
                <th colspan="6">
                    Traits
                </th>
            </tr>
            <tr>
                <td style="width:15%">
                    Corpus
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
                    Rank
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

        return $this->renderSheet($sheet, $show_sheet_table, $vitals_table, $information_table, $attribute_table,
            $skill_table, $traits_table, $history_table, $st_notes_table);

    }

}