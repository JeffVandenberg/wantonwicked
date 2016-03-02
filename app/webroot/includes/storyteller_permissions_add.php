<?php
/* @var array $userdata */

use classes\core\data\User;
use classes\core\helpers\FormHelper;
use classes\core\helpers\Request;
use classes\core\helpers\Response;
use classes\core\helpers\SessionHelper;
use classes\core\helpers\UserdataHelper;
use classes\core\repository\PermissionRepository;
use classes\core\repository\RoleRepository;
use classes\core\repository\UserRepository;
use classes\request\repository\GroupRepository;

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
$userId = 0;
$userPermissions = array();
$selectedGroups = array();

// test if submitting values
$permissionRepository = new PermissionRepository();
$groupsRepository = new GroupRepository();
$roleRepository = new RoleRepository();
$userRepository = new UserRepository();

if (Request::isPost() && UserdataHelper::IsHead($userdata)) {
    $userId = Request::getValue('user_id');
    $selectedGroups = $_POST['groups'];
    $userPermissions = $_POST['permissions'];
    if (!$userId) {
        SessionHelper::SetFlashMessage('No User Indicated');
    } else {
        $permissionRepository->SavePermissionsForUser($userId, $userPermissions);
        $groupsRepository->SaveGroupsForUser($userId, $selectedGroups);
        $user = $userRepository->FindByUserId($userId);
        /* @var User $user */
        $user->RoleId = Request::getValue('role_id');
        $userRepository->save($user);

        Response::redirect('/storyteller_index.php?action=permissions', 'Set Permissions for ' . Request::getValue('login_name'));
    }
}

$groups = $groupsRepository->simpleListAll();
$permissions = $permissionRepository->simpleListAll();
$roles = $roleRepository->simpleListAll();
$rolePermissions = $roleRepository->listRolesWithPermissions();

ob_start();
?>
    <script>
        var rolePermissions = {
        <?php foreach($rolePermissions as $rp): ?>
        <?php echo $rp['id'];?>: <?php echo json_encode(
            explode(',',  $rp['permissions']));?>,
        <?php endforeach; ?>
        }
        $(function () {
            $("#role-id").change(function () {
                var permissions = rolePermissions[$(this).val()];
                $.each(permissions, function(index, value) {
                    $("input[value=" + value +"]").prop('checked', true);
                });
            });
        });
    </script>
    <form id="permission-form" method="post"
          action="<?php echo $_SERVER['PHP_SELF']; ?>?action=permissions_add">
        <table class="normal_text">
            <tr>
                <td style="width: 100px;">
                    Login Name:
                </td>
                <td>
                    <?php echo FormHelper::Text('login_name', $login_name); ?>
                    <?php echo FormHelper::Hidden('user_id', $userId); ?>
                </td>
            </tr>
            <tr>
                <td>
                    Role:
                </td>
                <td>
                    <?php echo FormHelper::Select($roles, 'role_id', $user->RoleId); ?>
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
                    Permissions
                </td>
                <td>
                    <?php echo FormHelper::CheckboxList('permissions[]',
                        $permissions,
                        $userPermissions);
                    ?>
                </td>
            </tr>
        </table>
        <div style="text-align: center;">
            <?php echo FormHelper::Button('action', 'Submit', 'submit'); ?>
        </div>
    </form>
    <script>
        $(function () {
            $(function () {
                $("#login-name").autocomplete({
                    source: '/users.php?action=search&email=0',
                    minLength: 2,
                    autoFocus: true,
                    focus: function () {
                        return false;
                    },
                    select: function (e, ui) {
                        $("#user-id").val(ui.item.value);
                        console.debug(ui);
                        $("#login-name").val(ui.item.label);
                        return false;
                    }
                });
                $('#permission-form').submit(function (e) {
                    var userId = parseInt($("#user-id").val());

                    if (isNaN(userId) || (userId == 0)) {
                        alert('Please type a user name');
                        e.preventDefault();
                        return false;
                    }
                    return true;
                });
            });
        });
    </script>
<?php
$page_content .= ob_get_clean();