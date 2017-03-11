<?php
use classes\core\helpers\FormHelper;
use classes\core\helpers\MenuHelper;
use classes\core\helpers\Request;
use classes\core\helpers\SessionHelper;
use classes\core\repository\Database;

$contentHeader = $page_title = "Character Profile Transfer";

$characterName = Request::getValue('character_name');
$username = Request::getValue('username');

// test if information is provided
if (Request::isPost()) {
    // make sure that the character exists
    $character_name = htmlspecialchars($_POST['character_name']);
    $character_query = "SELECT id FROM characters WHERE character_name = ?;";
    $character = Database::getInstance()->query($character_query)->single([$character_name]);

    // make sure that the profile exists
    $profile_name = strtolower(htmlspecialchars($_POST['profile_name']));
    $login_query = "SELECT user_id FROM phpbb_users WHERE username_clean = ?;";
    $login = Database::getInstance()->query($login_query)->single([$profile_name]);

    if ($character && $login) {
        // set the login's id as the primary login id
        $char_update_query = "update characters set user_id = $login[user_id] where id = $character[id];";
        Database::getInstance()->query($char_update_query)->execute();
        SessionHelper::SetFlashMessage("$character_name has been moved to $profile_name.");
    } else {
        if (!$character) {
            SessionHelper::SetFlashMessage("$character_name wasn't found.");
        }

        if (!$login) {
            SessionHelper::SetFlashMessage("$profile_name wasn't found.");
        }
    }
}

$storytellerMenu = require_once('menus/storyteller_menu.php');
$menu = MenuHelper::GenerateMenu($storytellerMenu);

ob_start();
?>
<?php echo $menu; ?>
    <form name="name change" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?action=profile_transfer">
        <table>
            <tr>
                <td>
                    <?php echo FormHelper::Text('character_name', $characterName, ['label' => true]); ?>
                </td>
                <td>
                    <?php echo FormHelper::Text('username', $username, array('label' => 'Attach To')); ?>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="text-center">
                    <button class="button" name="Submit" value="Move Character">Move Character</button>
                </td>
            </tr>
        </table>
    </form>
<script>
    $(function() {
        $("#character-name").autocomplete({
            serviceUrl: '/character.php?action=search',
            minChars: 2,
            autoSelectFirst: true,
            preserveInput: true,
            params: {
            },
            onSearchStart: function (query) {
                query.only_sanctioned = 0;
            },
            onSelect: function(item) {
                $("#character-name").val(item.value);
                return false;
            }
        });
        $("#username").autocomplete({
            serviceUrl: '/users.php?action=search&email=0',
            minChars: 2,
            autoSelectFirst: true,
            onSelect: function(ui) {
                $("#username").val(ui.value);
                return false;
            }
        });
    });
</script>
<?php
$page_content = ob_get_clean();
