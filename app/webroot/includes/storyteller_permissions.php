<?php
/* @var array $userdata */
use classes\core\helpers\MenuHelper;
use classes\core\repository\PermissionRepository;

$page_title = "List ST Permissions";
$contentHeader = $page_title;

// test if removing any permissions
if (isset($_POST['action'])) {
    if (($_POST['action'] == 'update') && isset($_POST['delete'])) {
        $list                 = $_POST['delete'];
        $permissionRepository = new PermissionRepository();
        foreach ($list as $value) {
            $permissionRepository->RemovePermissions($value);
        }
    }
}

// get details of GM Permissions from database
$login_query = <<<EOQ
SELECT
    group_concat(G.name separator ', ') as groups,
    L.user_id,
    L.username as Name,
    GP.*
FROM
    phpbb_users AS L
    INNER JOIN gm_permissions AS GP ON L.user_id = GP.ID
    LEFT JOIN st_groups AS SG ON GP.ID = SG.user_id
    LEFT JOIN groups AS G ON SG.group_id = G.id
GROUP BY
    L.user_id
ORDER BY
    GP.Side_Game DESC,
    L.username;
EOQ;
$storytellers = ExecuteQueryData($login_query);

$storytellerMenu = require_once('helpers/storyteller_menu.php');
$menu = MenuHelper::GenerateMenu($storytellerMenu);
ob_start();
?>
<?php echo $menu; ?>
    <div class="paragraph">
        <a href="/storyteller_index.php?action=permissions_add">Add ST Permission</a>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <a href="/storyteller_index.php?action=permissions" onClick="submitForm();return false;">Delete ST
            Permissions</a>
    </div>
    <form name="gm_list" id="gm_list" method="post">
        <input type="hidden" name="action" id="action" value="update">
        <table>
            <tr>
                <th>
                    Delete
                </th>
                <th>
                    Login
                </th>
                <th>
                    Group(s)
                </th>
                <th>
                    Asst
                </th>
                <th>
                    ST
                </th>
                <th>
                    Head
                </th>
                <th>
                    Admin
                </th>
                <th>
                    Side
                </th>
                <th>
                    Wiki
                </th>
            </tr>
            <?php foreach ($storytellers as $login_detail): ?>
                <tr>
                    <?php if ($userdata['is_admin'] || $login_detail['Is_Admin'] != 'Y'): ?>
                        <td>
                            <label>
                                <input type="checkbox" name="delete[]" value="<?php echo $login_detail['user_id']; ?>">
                            </label>
                        </td>
                    <?php else: ?>
                        <td>
                            &nbsp;
                        </td>
                    <?php endif; ?>
                    <td>
                        <a href="/storyteller_index.php?action=permissions_view&permission_id=<?php echo $login_detail['Permission_ID']; ?>"
                           Name><?php echo $login_detail['Name']; ?></a>
                    </td>
                    <td>
                        <?php echo $login_detail['groups']; ?>
                    </td>
                    <td style="background-color: <?php echo ($login_detail['Is_Asst'] == 'Y') ? '#ada' : '#baa'; ?>">
                        <?php echo $login_detail['Is_Asst']; ?>
                    </td>
                    <td style="background-color: <?php echo ($login_detail['Is_GM'] == 'Y') ? '#ada' : '#baa'; ?>">
                        <?php echo $login_detail['Is_GM']; ?>
                    </td>
                    <td style="background-color: <?php echo ($login_detail['Is_Head'] == 'Y') ? '#ada' : '#baa'; ?>">
                        <?php echo $login_detail['Is_Head']; ?>
                    </td>
                    <td style="background-color: <?php echo ($login_detail['Is_Admin'] == 'Y') ? '#ada' : '#baa'; ?>">
                        <?php echo $login_detail['Is_Admin']; ?>
                    </td>
                    <td style="background-color: <?php echo ($login_detail['Side_Game'] == 'Y') ? '#ada' : '#baa'; ?>">
                        <?php echo $login_detail['Side_Game']; ?>
                    </td>
                    <td style="background-color: <?php echo ($login_detail['Wiki_Manager'] == 'Y') ? '#ada'
                        : '#baa'; ?>">
                        <?php echo $login_detail['Wiki_Manager']; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </form>
    <script language="JavaScript">
        function submitForm() {
            window.document.gm_list.submit();
        }
    </script>

<?php
$page_content = ob_get_clean();