<?php
use classes\character\repository\CharacterRepository;

$page_title = "View Character Sheet";
$view_character_name = (isset($_POST['view_character_name'])) ? htmlspecialchars($_POST['view_character_name']) : "";
$character_sheet = "";

$characterRepository = new CharacterRepository();
// test if looking up a character
if ($view_character_name) {
    // try to get character
    $character = $characterRepository->FindByName($view_character_name);

    if ($character !== false) {
        if (($character['show_sheet'] == 'Y') && ($character['view_password'] == $_POST['viewpwd'])) {
            // show full sheet 
            $edit_show_sheet     = false;
            $edit_name           = false;
            $edit_vitals         = false;
            $edit_is_npc         = false;
            $edit_is_dead        = false;
            $edit_location       = false;
            $edit_concept        = false;
            $edit_description    = false;
            $edit_url            = false;
            $edit_equipment      = false;
            $edit_public_effects = false;
            $edit_group          = false;
            $edit_exit_line      = false;
            $edit_is_npc         = false;
            $edit_attributes     = false;
            $edit_skills         = false;
            $edit_perm_traits    = false;
            $edit_temp_traits    = false;
            $edit_powers         = false;
            $edit_history        = false;
            $edit_goals          = false;
            $edit_login_note     = false;
            $edit_experience     = false;
            $show_st_notes       = false;
            $view_is_asst        = false;
            $view_is_st          = false;
            $view_is_head        = false;
            $view_is_admin       = false;
            $may_edit            = false;
            $edit_cell           = false;
            $calculate_derived   = false;
            $character_type      = $character['Character_Type'];
            $character_sheet     = buildWoDSheet($character, $character_type, $edit_show_sheet, $edit_name, $edit_vitals, $edit_is_npc, $edit_is_dead, $edit_location, $edit_concept, $edit_description, $edit_url, $edit_equipment, $edit_public_effects, $edit_group, $edit_exit_line, $edit_is_npc, $edit_attributes, $edit_skills, $edit_perm_traits, $edit_temp_traits, $edit_powers, $edit_history, $edit_goals, $edit_login_note, $edit_experience, $show_st_notes, $view_is_asst, $view_is_st, $view_is_head, $view_is_admin, $may_edit, $edit_cell, $calculate_derived);
        }
        else {
            // show partial sheet
            ob_start();
            ?>
            <table>
                <tr>
                    <td class="highlight">
                        Character Name
                    </td>
                    <td>
                        <?php echo $character['character_name']; ?>
                    </td>
                </tr>
                <tr>
                    <td class="highlight">
                        City
                    </td>
                    <td>
                        <?php echo $character['city']; ?>
                    </td>
                </tr>
                <tr>
                    <td class="highlight">
                        Description
                    </td>
                    <td>
                        <?php echo $character['description']; ?>
                    </td>
                </tr>
                <tr>
                    <td class="highlight">
                        Public Effects
                    </td>
                    <td>
                        <?php echo $character['public_effects']; ?>
                    </td>
                </tr>
                <tr>
                    <td class="highlight">
                        Daily Equipment
                    </td>
                    <td colspan="3">
                        <?php echo $character['equipment_public']; ?>
                    </td>
                </tr>
            </table>
            <?php
            $character_sheet = ob_get_clean();
        }
    }
    else {
        $character_sheet = "That character doesn't exist.";
    }

}

$character_query_form = <<<EOQ
<form name="view_others" method="post" action="$_SERVER[PHP_SELF]?action=view_other">
  Enter the name and, if required, the<br> password to view another player's character sheet<br>
  <span class="highlight">Character Name:</span> <input type="text" name="view_character_name" size="25" maxlength="35"><br>
  <span class="highlight">View Password:</span> <input type="password" name="viewpwd" size="25" maxlength="30"><br>
  <input type="submit" name="submit" value="View the sheet">
</form>
EOQ;

$page_content = <<<EOQ
$character_query_form
<br>
$character_sheet
EOQ;
?>