<?php
use classes\core\helpers\FormHelper;
use classes\core\helpers\MenuHelper;
use classes\core\helpers\Request;
use classes\core\helpers\SessionHelper;
use classes\core\repository\Database;

$contentHeader = $page_title = "Character Profile Transfer";

$characterName = Request::GetValue('character_name');
$username = Request::GetValue('profile_name');
// test if information is provided
if (Request::IsPost()) {
    // make sure that the character exists
    $character_name  = htmlspecialchars($_POST['character_name']);
    $character_query = "select * from characters where character_name = '$character_name';";
    $character = Database::GetInstance()->Query($character_query)->Single();

    // make sure that the profile exists
    $profile_name = htmlspecialchars($_POST['profile_name']);
    $login_query  = "select * from phpbb_users where username = '$profile_name';";
    $login = Database::GetInstance()->Query($login_query)->Single();

    if ($character && $login) {
        // set the login's id as the primary login id
        $char_update_query = "update characters set user_id = $login[user_id] where character_id = $character_detail[id];";
        Database::GetInstance()->Query($char_update_query)->Execute();
        SessionHelper::SetFlashMessage("$character_name has been moved to $profile_name.");
    }
    else {
        if (!$character) {
            SessionHelper::SetFlashMessage("$character_name wasn't found.");
        }

        if (!$login) {
            SessionHelper::SetFlashMessage("$profile_name wasn't found.");
        }
    }
}

$storytellerMenu = require_once('helpers/storyteller_menu.php');
$menu = MenuHelper::GenerateMenu($storytellerMenu);

ob_start();
?>
    <?php echo $menu; ?>
    <form name="name change" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?action=profile_transfer">
        <table>
            <tr>
                <td>
                    <?php echo FormHelper::Text('character_name', $characterName, array('label' => true)); ?>
                </td>
                <td>
                    <?php echo FormHelper::Text('profile_name', $username, array('label' => 'Attach To')); ?>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <input type="submit" name="Submit" value="Move Character">
                </td>
            </tr>
        </table>
    </form>
<?php
$page_content = ob_get_clean();