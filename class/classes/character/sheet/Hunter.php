<?php
/**
 * Created by PhpStorm.
 * User: jvandenberg
 * Date: 9/8/15
 * Time: 2:44 PM
 */

namespace classes\character\sheet;


use classes\core\helpers\FormHelper;

class Hunter extends SheetRenderer
{
    public function render(WodSheet $sheet, $character_name, $character_type_select, $location, $sex, $virtue, $vice,
                           $icon, $age, $is_npc, $status, $concept, $description, $equipment_public, $equipment_hidden,
                           $public_effects, $safe_place, $character_merit_list, $character_flaw_list, $characterMiscList,
                           $health_dots, $size, $wounds_bashing, $wounds_lethal, $wounds_aggravated, $defense, $morality_dots,
                           $initiative_mod, $willpower_perm_dots, $speed, $willpower_temp_dots, $armor, $st_notes_table,
                           $history_table, $skill_table, $attribute_table, $show_sheet_table, $splat1, $subsplat, $friends)
    {
        ob_start();
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
                    <b>Profession</b>
                </td>
                <td>
                    <?php echo $splat1; ?>
                </td>
                <td>
                    <b>Compact</b>
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
                    <b>Cell</b>
                </td>
                <td>
                    <?php echo $friends; ?>
                </td>
            </tr>
            <tr>
                <td>
                    <b>Safehouse</b>
                </td>
                <td>
                    <?php echo $safe_place; ?>
                </td>
            </tr>
        </table>
        <?php
        $information_table = ob_get_clean();

        // Endowments
        $powers = $sheet->getPowers($sheet->stats['id'], 'Endowment', WodSheet::NAMENOTE, 5);

        ob_start();
        ?>
        <table class="character-sheet <?php echo $sheet->table_class; ?>" id="endowments_list">
            <tr>
                <th colspan="3">
                    Endowments
                    <?php if ($sheet->viewOptions['edit_powers']): ?>
                        <a href="#" onClick="addEndowment();return false;">
                            <img src="/img/plus.png" title="Add Endowment"/>
                        </a>
                    <?php endif; ?>
                </th>
            </tr>
            <tr>
                <td style="width:35%;" class="header-row">
                    Name
                </td>
                <td style="width:35%;" class="header-row">
                    Note
                </td>
                <td style="width:30%;" class="header-row">
                    Level
                </td>
            </tr>
            <?php foreach ($powers as $i => $power): ?>
                <?php $dots = FormHelper::Dots("endowment${i}", $power->getPowerLevel(),
                    WodSheet::MERIT, $sheet->stats['character_type'], $sheet->max_dots,
                    $sheet->viewOptions['edit_powers'],
                    false, $sheet->viewOptions['xp_create_mode']); ?>
                <tr>
                    <td>
                        <?php if ($sheet->viewOptions['edit_powers']): ?>
                            <label for="endowment<?php echo $i; ?>_name"></label><input type="text"
                                                                                        name="endowment<?php echo $i; ?>_name"
                                                                                        id="endowment<?php echo $i; ?>_name"
                                                                                        size="15"
                                                                                        value="<?php echo $power->getPowerName(); ?>">
                        <?php else: ?>
                            <?php echo $power->getPowerName(); ?>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($sheet->viewOptions['edit_powers']): ?>
                            <label for="endowment<?php echo $i; ?>_note"></label><input type="text"
                                                                                        name="endowment<?php echo $i; ?>_note"
                                                                                        id="endowment<?php echo $i; ?>_note"
                                                                                        size="15"
                                                                                        value="<?php echo $power->getPowerNote(); ?>">
                        <?php else: ?>
                            <?php echo $power->getPowerNote(); ?>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php echo $dots; ?>
                        <input type="hidden" name="endowment<?php echo $i; ?>_id"
                               id="endowment<?php echo $i; ?>_id"
                               value="<?php echo $power->getPowerID(); ?>">
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
        <?php
        $endowments_list = ob_get_clean();

        // Tactics
        $powers = $sheet->getPowers($sheet->stats['id'], 'Tactic', WodSheet::NAMENOTE, 2);

        $supernatural_xp_js = "";
        if($sheet->viewOptions['xp_create_mode'])
        {
            $supernatural_xp_js = ' onChange="updateXP(' . WodSheet::MERIT . ')" ';
        }

        ob_start();
        ?>
        <table class="character-sheet <?php echo $sheet->table_class; ?>" id="tactics_list">
            <tr>
                <th colspan="3">
                    Tactics
                    <?php if ($sheet->viewOptions['edit_powers']): ?>
                        <a href="#" onClick="addTactic();return false;">
                            <img src="/img/plus.png" title="Add Tactic"/>
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
                            <label for="tactic<?php echo $i; ?>_name"></label>
                            <input type="text" name="tactic<?php echo $i; ?>_name" id="tactic<?php echo $i; ?>_name"
                                   size="15" value="<?php echo $power->getPowerName(); ?>">
                        <?php else: ?>
                            <?php echo $power->getPowerName(); ?>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($sheet->viewOptions['edit_powers']): ?>
                            <label for="tactic<?php echo $i; ?>_cost"></label>
                            <input type="text" name="tactic<?php echo $i; ?>_cost" id="tactic<?php echo $i; ?>_cost"
                                   size="3" value="<?php echo $power->getPowerLevel(); ?>" <?php echo $supernatural_xp_js; ?> >
                        <?php else: ?>
                            <?php echo $power->getPowerLevel(); ?>
                        <?php endif; ?>
                        <input type="hidden" name="tactic<?php echo $i; ?>_id"
                               id="tactic<?php echo $i; ?>_id"
                               value="<?php echo $power->getPowerID(); ?>">
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
        <?php
        $tactics_list = ob_get_clean();

        ob_start();
        ?>
        <div style="width:50%;float:left;">
            <?php echo $character_merit_list; ?>
            <?php echo $character_flaw_list; ?>
        </div>
        <div style="width:50%;float:left;">
            <?php echo $endowments_list; ?>
            <?php echo $tactics_list; ?>
            <?php echo $characterMiscList; ?>
        </div>

        <table class="character-sheet <?php echo $sheet->table_class; ?>" id="tactics_list">
            <tr>
                <th colspan="6" align="center">
                    Traits
                </th>
            </tr>
            <tr>
                <td width="15%">
                    Health
                </td>
                <td colspan="2">
                    <?php echo $health_dots; ?>
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
                    Wounds
                </td>
                <td colspan="2" style="white-space: nowrap;">
                    Bashing: <?php echo $wounds_bashing; ?>
                    Lethal: <?php echo $wounds_lethal; ?>
                    Agg: <?php echo $wounds_aggravated; ?>
                </td>
                <td colspan="1">
                    Defense
                </td>
                <td colspan="2">
                    <?php echo $defense; ?>
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
                    Initiative Mod
                </td>
                <td colspan="2">
                    <?php echo $initiative_mod; ?>
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
                    Speed
                </td>
                <td colspan="2">
                    <?php echo $speed; ?>
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
