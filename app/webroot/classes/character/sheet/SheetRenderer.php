<?php
/**
 * Created by PhpStorm.
 * User: jvandenberg
 * Date: 9/7/2015
 * Time: 12:37 AM
 */

namespace classes\character\sheet;


abstract class SheetRenderer
{
    protected function renderSheet(WodSheet $sheet, $show_sheet_table, $vitals_table, $information_table,
                                 $attribute_table, $skill_table,
                                 $traits_table, $history_table, $st_notes_table)
    {
        $submit_button = $this->getSubmitButton($sheet);

        // put sheet pieces together
        ob_start();
        ?>
        <?php echo $show_sheet_table; ?>
        <?php echo $vitals_table; ?>
        <?php echo $information_table; ?>
        <?php echo $attribute_table; ?>
        <?php echo $skill_table; ?>
        <?php echo $traits_table; ?>
        <?php echo $history_table; ?>
        <?php echo $st_notes_table; ?>
        <?php echo $submit_button; ?>
        <?php
        return ob_get_clean();
    }

    /**
     * @param WodSheet $sheet
     * @return string
     */
    protected function getSubmitButton(WodSheet $sheet)
    {
        $submit_button = "";
        if ($sheet->viewOptions['allow_edits']) {
            $submit_value = "Update Character";
            if (!$sheet->stats['id']) {
                $submit_value = "Create Character";
            }

            ob_start();
            ?>
            <table class="character-sheet <?php echo $sheet->table_class; ?>">
                <tr>
                    <td align="center">
                        <input type="hidden" name="character_id" id="character_id"
                               value="<?php echo $sheet->stats['id']; ?>">
                        <input type="submit" name="submit" value="<?php echo $submit_value; ?>"
                               onClick="SubmitCharacter();return false;">
                    </td>
                </tr>
            </table>
            <?php
            $submit_button = ob_get_clean();
            return $submit_button;

        }
        return $submit_button;
    }

}