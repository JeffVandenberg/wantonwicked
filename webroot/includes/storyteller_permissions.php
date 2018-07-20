<?php
/* @var array $userdata */
use classes\core\helpers\MenuHelper;
use classes\core\helpers\SessionHelper;
use classes\core\helpers\UserdataHelper;
use classes\core\repository\Database;
use classes\core\repository\PermissionRepository;

$page_title = "List ST Permissions";
$contentHeader = $page_title;

// test if removing any permissions
if (isset($_POST['action'])) {
    if (($_POST['action'] == 'update') && isset($_POST['delete'])) {
        $list = $_POST['delete'];
        $permissionRepository = new PermissionRepository();
        foreach ($list as $value) {
            $permissionRepository->RemovePermissions($value);
        }
        SessionHelper::SetFlashMessage('Removed Users');
    }
}

// get details of GM Permissions from database
$login_query = <<<EOQ
SELECT
    (
      SELECT group_concat(
        G.name SEPARATOR ', '
      )
      FROM
        st_groups AS SG
        LEFT JOIN groups AS G ON SG.group_id = G.id
      WHERE
        SG.user_id = L.user_id
      ORDER BY
        G.name
    ) AS groups,
    L.user_id,
    L.username,
    R.name as role_name
FROM
    phpbb_users AS L
    LEFT JOIN roles AS R on L.role_id = R.id
WHERE
    L.role_id != 0
GROUP BY
    L.user_id
ORDER BY
    L.username
EOQ;
$storytellers = Database::getInstance()->query($login_query)->all();

$storytellerMenu = require_once('menus/storyteller_menu.php');
$storytellerMenu['Action']['submenu']['Add Permission'] = array(
    'link' => '/storyteller_index.php?action=permissions_add'
);
$menu = MenuHelper::generateMenu($storytellerMenu);
ob_start();
?>
<?php echo $menu; ?>
    <form name="gm_list" id="gm_list" method="post">
        <input type="hidden" name="action" id="action" value="update">
        <table>
            <tr>
                <?php if (UserdataHelper::IsAdmin($userdata)): ?>
                    <th>
                        Delete
                    </th>
                <?php endif; ?>
                <th>
                    Login
                </th>
                <th>
                    Role
                </th>
                <th>
                    Group(s)
                </th>
            </tr>
            <?php foreach ($storytellers as $login_detail): ?>
                <tr>
                    <?php if (UserdataHelper::IsHead($userdata)): ?>
                        <td>
                            <label>
                                <input type="checkbox" name="delete[]" value="<?php echo $login_detail['user_id']; ?>">
                            </label>
                        </td>
                    <?php endif; ?>
                    <td>
                        <a href="/storyteller_index.php?action=permissions_view&user_id=<?php echo $login_detail['user_id']; ?>"
                           ><?php echo $login_detail['username']; ?></a>
                    </td>
                    <td>
                        <?php echo $login_detail['role_name']; ?>
                    </td>
                    <td>
                        <?php echo $login_detail['groups']; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (UserdataHelper::IsHead($userdata)): ?>
                <tfoot>
                <tr>
                    <th colspan="4">
                        <input type="submit" value="Remove Selected"/>
                    </th>
                </tr>
                </tfoot>
            <?php endif; ?>
        </table>
    </form>
    <script language="JavaScript">
        function submitForm() {
            $("#gm_list").submit();
        }
    </script>

<?php
$page_content = ob_get_clean();
