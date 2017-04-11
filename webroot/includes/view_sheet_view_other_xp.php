<?php
/* @var array $userdata */

use classes\character\helper\CharacterSheetHelper;
use classes\character\repository\CharacterRepository;
use classes\core\helpers\Request;
use classes\log\CharacterLog;
use classes\log\data\ActionType;

$page_title = "View Character Sheet";
$characterName = htmlspecialchars(Request::getValue('view_character_name'));
$character_sheet = '';

$characterRepository = new CharacterRepository();

if ($characterName) {
    // try to get character
    $character = $characterRepository->FindByName($characterName);
    $characterSheetHelper = new CharacterSheetHelper();
    if ($character !== false) {
        if (($character['show_sheet'] == 'Y') && ($character['view_password'] == $_POST['viewpwd'])) {
            CharacterLog::LogAction($character['id'], ActionType::ViewCharacter, 'Other Player View - Full View',
                                    $userdata['user_id']);
            ob_start();
            echo $characterSheetHelper->MakeLockedView($character, $character['character_type']);
            $character_sheet = ob_get_clean();
        }
        else {
            // show partial sheet
            CharacterLog::LogAction($character['id'], ActionType::ViewCharacter, 'Other Player View - Partial View',
                                    $userdata['user_id']);

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

ob_start();
?>

    <div>
        <form name="view_others" method="post">
            Enter the name and, if required, the <br/> password to view another player's character sheet<br>
            <label>
                Character Name:
                <input type="text" name="view_character_name" size="25" maxlength="35">
            </label>
            <br>
            <label>
                View Password:
                <input type="password" name="viewpwd" size="25" maxlength="30">
            </label><br>
            <input type="submit" name="submit" value="View the sheet">
        </form>
    </div>
    <br>
    <div>
        <?php echo $character_sheet; ?>
    </div>
<?php
$page_content = ob_get_clean();
