<?php
use classes\core\data\User;
use classes\core\helpers\FormHelper;
use classes\core\helpers\Request;
use classes\core\helpers\Response;
use classes\core\repository\PermissionRepository;
use classes\core\repository\RepositoryManager;
use classes\request\repository\GroupRepository;

/* @var array $userdata */

$page_title = "View ST Permissions";
$contentHeader = $page_title;

$userId = Request::GetValue('user_id', 0);

$page = "";
$page_content = "";
$alert = "";
$js = "";
$show_form = true;

$permissionRepository = new PermissionRepository();
$groupRepository = new GroupRepository();

$userRepository = RepositoryManager::GetRepository('classes\core\data\User');
$user = $userRepository->FindByUserId($userId);
/* @var User $user */

// test if submitting values
if (Request::IsPost()) {

    // update permissions
    $permissionRepository->SavePermissionsForUser($userId, Request::GetValue('permissions'));

    // update groups
    $groupRepository->SaveGroupsForUser($userId, Request::GetValue('groups'));
    // add js
    Response::Redirect('/storyteller_index.php?action=permissions', 'Updated Permissions for ' . $user->Username);
}


$permissions = $permissionRepository->SimpleListAll();
$userPermissions = $permissionRepository->ListPermissionsForUser($userId);

$groups = $groupRepository->SimpleListAll();
$selectedGroups = $groupRepository->ListGroupsForUser($userId);;

ob_start();
?>
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