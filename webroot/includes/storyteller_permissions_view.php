<?php
use classes\core\data\User;
use classes\core\helpers\FormHelper;
use classes\core\helpers\Request;
use classes\core\helpers\Response;
use classes\core\repository\PermissionRepository;
use classes\core\repository\RepositoryManager;
use classes\core\repository\RoleRepository;
use classes\request\repository\GroupRepository;

/* @var array $userdata */

$page_title = "View ST Permissions";
$contentHeader = $page_title;

$userId = Request::getValue('user_id', 0);

$page = "";
$page_content = "";
$alert = "";
$js = "";
$show_form = true;

$permissionRepository = new PermissionRepository();
$groupRepository = new GroupRepository();
$roleRepository = new RoleRepository();

$userRepository = RepositoryManager::GetRepository('classes\core\data\User');
$user = $userRepository->FindByUserId($userId);
/* @var User $user */

// test if submitting values
if (Request::isPost()) {

    // update permissions
    $permissionRepository->SavePermissionsForUser($userId, Request::getValue('permissions'));

    // update groups
    $groupRepository->SaveGroupsForUser($userId, Request::getValue('groups'));

    // update role
    $user->RoleId = Request::getValue('role_id');
    $userRepository->save($user);

    // add js
    Response::redirect('/storyteller_index.php?action=permissions', 'Updated Permissions for ' . $user->Username);
}


$permissions = $permissionRepository->simpleListAll();
$userPermissions = $permissionRepository->ListPermissionsForUser($userId);

$groups = $groupRepository->simpleListAll();
$selectedGroups = $groupRepository->ListGroupsForUser($userId);;

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
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?action=permissions_view">
        <table class="normal_text">
            <tr>
                <td style="width: 100px;">
                    Login Name:
                </td>
                <td>
                    <?php echo $user->Username; ?>
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
            <?php echo FormHelper::Hidden('user_id', $userId); ?>
            <?php echo FormHelper::Button('action', 'Submit', 'submit'); ?>
        </div>
    </form>
<?php
$page_content .= ob_get_clean();