<?php
use classes\core\helpers\FormHelper;
use classes\core\helpers\Request;

/* @var array $userdata */

$page_title = "View ST Permissions";
$contentHeader = $page_title;

$permission_id = Request::GetValue('permission_id', 0);

$page = "";
$page_content = "";
$alert = "";
$js = "";
$show_form = true;

// form variables
$login_name = "";
$login_id = 0;
$is_asst = "N";
$is_gm = "N";
$is_head = "N";
$is_admin = "N";
$side_game = "N";
$wiki_manager = "N";
$selectedGroups = array();

// test if submitting values
if (Request::IsPost()) {
    // set variables
    $is_asst = (!empty($_POST['is_asst'])) ? "Y" : "N";
    $is_gm = (!empty($_POST['is_gm'])) ? "Y" : "N";
    $is_head = (!empty($_POST['is_head'])) ? "Y" : "N";
    $side_game = (!empty($_POST['side_game'])) ? "Y" : "N";
    $wiki_manager = (!empty($_POST['wiki_manager'])) ? "Y" : "N";
    if ($userdata['is_admin']) {
        if (!empty($_POST['is_admin'])) {
            $is_admin = "Y";
        }
    }

    // get permission (mostly to have the user_id)
    $permission_query = <<<EOQ
SELECT
    *
FROM
    gm_permissions
WHERE
    permission_id = $permission_id;
EOQ;
    $permission = ExecuteQueryItem($permission_query);

    // update permissions
    $permission_query = <<<EOQ
UPDATE
    gm_permissions
SET
    is_asst='$is_asst',
    is_gm='$is_gm',
    is_head='$is_head',
    side_game = '$side_game',
    wiki_manager = '$wiki_manager'
EOQ;
    if ($userdata['is_admin']) {
        $permission_query .= ", is_admin = '$is_admin'";
    }
    $permission_query .= " where permission_id=$permission_id;";
    ExecuteQuery($permission_query);

    // update groups
    $query = <<<EOQ
DELETE FROM
    st_groups
WHERE
    user_id = $permission[ID];
EOQ;
    ExecuteQuery($query);

    foreach($_POST['groups'] as $group)
    {
        $query = <<<EOQ
INSERT INTO
    st_groups
    (
        user_id,
        group_id
    )
VALUES
    (
        $permission[ID],
        $group
    )
EOQ;
        ExecuteQuery($query);
    }

    // add js
    $java_script = <<<EOQ
<script language="JavaScript">
window.location.href="$_SERVER[PHP_SELF]?action=permissions";
</script>
EOQ;
}

// get information from database
$permission_query = <<<EOQ
SELECT
    gm_permissions.*,
    U.username AS Name
FROM
    gm_permissions
    INNER JOIN phpbb_users AS U on U.user_id = gm_permissions.id
WHERE
    permission_id = $permission_id;
EOQ;
$permissions = ExecuteQueryData($permission_query);

// get groups
$group_query = <<<EOQ
SELECT
    G.id,
    G.name
FROM
    groups AS G
ORDER BY
    name
EOQ;

$group_list = ExecuteQueryData($group_query);
$groups = array();
foreach ($group_list as $group) {
    $groups[$group['id']] = $group['name'];
}

if (count($permissions) == 0) {
    $page_content .= "That ID is invalid.";
    die();
}

$row = $permissions[0];
$login_name = $row['Name'];
$login_id = $row['ID'];
$is_asst = $row['Is_Asst'];
$is_gm = $row['Is_GM'];
$is_head = $row['Is_Head'];
$is_admin = $row['Is_Admin'];
$side_game = $row['Side_Game'];
$wiki_manager = $row['Wiki_Manager'];

$sql = <<<EOQ
SELECT
    group_id
FROM
    st_groups
WHERE
    user_id = $login_id
EOQ;
$selectedGroupsList = ExecuteQueryData($sql);

$selectedGroups = array();
foreach($selectedGroupsList as $selectedGroup)
{
    $selectedGroups[] = $selectedGroup['group_id'];
}

ob_start();
?>
    <form name="skill_form" id="skill_form" method="post"
          action="<?php echo $_SERVER['PHP_SELF']; ?>?action=permissions_view">
        <table class="normal_text">
            <tr>
                <td style="width: 100px;">
                    Login Name:
                </td>
                <td>
                    <?php echo $login_name; ?>
                </td>
            </tr>
            <tr>
                <td>
                    Groups:
                </td>
                <td>
                    <?php echo FormHelper::Multiselect($groups, 'groups[]', $selectedGroups); ?>
                </td>
            </tr>
            <tr>
                <td>
                    Is Asst:
                </td>
                <td>
                    <?php echo FormHelper::Checkbox('is_asst', 'Y', $is_asst == 'Y'); ?>
                </td>
            </tr>
            <tr>
                <td>
                    Is ST:
                </td>
                <td>
                    <?php echo FormHelper::Checkbox('is_gm', 'Y', $is_gm == 'Y'); ?>
                </td>
            </tr>
            <tr>
                <td>
                    Is Head:
                </td>
                <td>
                    <?php echo FormHelper::Checkbox('is_head', 'Y', $is_head == 'Y'); ?>
                </td>
            </tr>
            <tr>
                <td>
                    Side Game:
                </td>
                <td>
                    <?php echo FormHelper::Checkbox('side_game', 'Y', $side_game == 'Y'); ?>
                </td>
            </tr>
            <tr>
                <td>
                    Wiki Manager:
                </td>
                <td>
                    <?php echo FormHelper::Checkbox('wiki_manager', 'Y', $wiki_manager == 'Y'); ?>
                </td>
            </tr>
            <?php if ($userdata['is_admin']): ?>
                <tr>
                    <td>
                        Is Admin:
                    </td>
                    <td>
                        <?php echo FormHelper::Checkbox('is_admin', 'Y', $is_admin == 'Y'); ?>
                    </td>
                </tr>
            <?php endif; ?>
        </table>
        <div style="text-align: center;">
            <?php echo FormHelper::Hidden('permission_id', $permission_id); ?>
            <?php echo FormHelper::Button('action', 'Submit', 'submit'); ?>
        </div>
    </form>
<?php
$page_content .= ob_get_clean();