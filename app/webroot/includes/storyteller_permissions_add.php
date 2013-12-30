<?php
/* @var array $userdata */

use classes\core\helpers\FormHelper;
use classes\core\helpers\Request;

$page_title = "Add ST";
$contentHeader = $page_title;

$page = "";
$page_content = "";
$alert = "";
$js = "";
$show_form = true;
$mode = "debug";

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
if (Request::IsPost() && ($userdata['is_head'] || $userdata['is_admin'])) {
    // set variables
    $login_name = $_POST['login_name'];
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

    $selectedGroups = $_POST['groups'];

    // test of working on a valid login
    $login_check_query = "select user_id from phpbb_users where username='$login_name';";
    $login = ExecuteQueryItem($login_check_query);

    if (($login != null) && $show_form) {

        $permissions_query = <<<EOQ
INSERT INTO
    gm_permissions
    (
        id,
        is_asst,
        is_gm,
        is_head,
        is_admin,
        side_game,
        wiki_manager
    )
VALUES
    (
        $login[user_id],
        '$is_asst',
        '$is_gm',
        '$is_head',
        '$is_admin',
        '$side_game',
        '$wiki_manager'
    );

EOQ;
        //echo "$permissions_query<br>";
        ExecuteQuery($permissions_query);

        foreach($selectedGroups as $group)
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
        $login[user_id],
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
    else {
        $page_content .= "Please enter a valid login name.<br>";
    }
}

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

ob_start();
?>
    <form name="skill_form" id="skill_form" method="post"
          action="<?php echo $_SERVER['PHP_SELF']; ?>?action=permissions_add">
        <table class="normal_text">
            <tr>
                <td style="width: 100px;">
                    Login Name:
                </td>
                <td>
                    <?php echo FormHelper::Text('login_name', $login_name); ?>
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
            <?php echo FormHelper::Button('action', 'Submit', 'submit'); ?>
        </div>
    </form>
<?php
$page_content .= ob_get_clean();