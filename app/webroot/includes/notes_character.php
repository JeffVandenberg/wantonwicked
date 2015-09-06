<?php
/* @var array $userdata */

// grab passed variables
use classes\character\data\Character;
use classes\character\repository\CharacterRepository;
use classes\core\helpers\MenuHelper;
use classes\core\helpers\Request;
use classes\core\helpers\Response;
use classes\core\helpers\SessionHelper;
use classes\core\helpers\UserdataHelper;
use classes\core\repository\Database;
use classes\core\repository\RepositoryManager;
use classes\log\CharacterLog;
use classes\log\data\ActionType;

$characterId = Request::GetValue('character_id', 0);

// includes for sortable forms
include 'cgi-bin/js_doSort.php';
include 'cgi-bin/buildSortForm.php';

$characterRepository = RepositoryManager::GetRepository('classes\character\data\Character');
/* @var CharacterRepository $characterRepository */
$character = $characterRepository->GetById($characterId);
/* @var Character $character */

if ($character->Id == 0) {
    SessionHelper::SetFlashMessage("Invalid Character");
    Response::Redirect('chat.php');
}

if ($character->IsNpc == 'Y') {
    if (!UserdataHelper::IsSt($userdata)) {
        CharacterLog::LogAction($characterId, ActionType::InvalidAccess, 'Attempted access to character notes',
                                $userdata['user_id']);
        SessionHelper::SetFlashMessage("You're not authorized to view that character.");
        Response::Redirect('');
    }
}
else {
    if ($character->UserId != $userdata['user_id']) {
        CharacterLog::LogAction($characterId, ActionType::InvalidAccess, 'Attempted access to character notes',
                                $userdata['user_id']);
        SessionHelper::SetFlashMessage("You're not authorized to view that character.");
        Response::Redirect('');
    }
}


$page_title = "Notes for $character_detail[character_name]";
$contentHeader = $page_title;

// variables for form
$notes_list = "";
$this_order_by = "update_date";
$last_order_by = "";
$order_by = "update_date";
$order_dir = "desc";

// test if deleting
if (isset($_POST['action'])) {
    if ($_POST['action'] == 'update') {
        $note_list = $_POST['delete'];
        while (list($key, $value) = each($note_list)) {
            $delete_query = "update personal_notes set is_deleted='Y' where personal_note_id=$value;";
            $delete_result = mysql_query($delete_query) || die(mysql_error());
        }
    }
    if ($_POST['action'] == 'sort') {
        $this_order_by = $_POST['this_order_by'];
        $last_order_by = $_POST['last_order_by'];
        if (($_POST['this_order_by'] == $_POST['last_order_by']) && $_POST['this_order_dir'] == 'desc') {
            $order_dir = "asc";
        }
    }
    $order_by = "$this_order_by $order_dir, personal_note_id";
}


// query database
$note_query = "select * from personal_notes where character_id = $characterId and is_deleted='n' order by $order_by;";
$notes = Database::GetInstance()->Query($note_query)->All();

require_once('helpers/character_menu.php');
/* @var array $characterMenu */
$menu = MenuHelper::GenerateMenu($characterMenu);
ob_start();
echo $menu;
?>

    <div align="center">
        <a href="<?php echo $_SERVER['PHP_SELF']; ?>?action=view&character_id=<?php echo $characterId; ?>"
           onClick="window.open('<?php echo $_SERVER['PHP_SELF']; ?>?action=view&character_id=<?php echo $characterId; ?>', 'AddCharacterNote', 'width=535,height=335,resizable,scrollbars');return false;">Add
            Note</a>
        &nbsp;&nbsp;&nbsp;&nbsp;
        <a href="#" onClick="submitForm();return false;">Delete Notes</a>
        <br>

        <form name="note_list" id="note_list" method="post"
              action="<?php $_SERVER['PHP_SELF']; ?>?action=character&character_id=<?php echo $characterId; ?>">
            <table>
                <tr>
                    <th>&nbsp</th>
                    <th><a href="javascript:doSort('is_favorite')">Favorite</a></th>
                    <th><a href="javascript:doSort('title')">Title</a></th>
                    <th><a href="javascript:doSort('create_date')">Create Date</a></th>
                    <th><a href="javascript:doSort('update_date')">Update Date</a></th>
                </tr>

                <?php foreach($notes as $row => $note_detail): ?>
                <tr>
                    <td>
                        <input type="checkbox" name='delete[]' id='delete[]' value="<?php echo $note_detail['Personal_Note_ID']; ?>">
                    </td>
                    <td align="center">
                        <?php echo $note_detail['Is_Favorite']; ?>
                    </td>
                    <td>
                        <a href="<?php echo $_SERVER['PHP_SELF']; ?>?action=view&character_id=<?php echo $characterId; ?>&personal_note_id=<?php echo $note_detail['Personal_Note_ID']; ?>"
                           onClick="window.open('<?php echo $_SERVER['PHP_SELF']; ?>?action=view&character_id=<?php echo $characterId; ?>&personal_note_id=<?php echo $note_detail['Personal_Note_ID']; ?>', 'ViewCharacterNote', 'width=535,height=335,resizable,scrollbars');return false;"><?php echo $note_detail['Title']; ?></a>
                    </td>
                    <td>
                        <?php echo $note_detail['Create_Date']; ?>
                    </td>
                    <td>
                        <?php echo $note_detail['Update_Date']; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
            <input type="hidden" name="action" id="action" value="update">
        </form>
    </div>
    <script language="JavaScript">
        function submitForm() {
            window.document.note_list.submit();
        }
    </script>

<?php echo buildSortForm($this_order_by, $order_dir, $last_order_by,
                         "$_SERVER[PHP_SELF]?action=character&character_id=$characterId&log_npc=$log_npc"); ?>
<?php
// build list
$page_content = ob_get_clean();