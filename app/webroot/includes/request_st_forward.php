<?php
/**
 * Created by PhpStorm.
 * User: JeffVandenberg
 * Date: 3/7/2015
 * Time: 10:39 PM
 */
/* @var array $userdata */

use classes\core\data\Group;
use classes\core\helpers\FormHelper;
use classes\core\helpers\Request;
use classes\core\helpers\Response;
use classes\core\helpers\SessionHelper;
use classes\request\data\RequestNote;
use classes\request\repository\GroupRepository;
use classes\request\repository\RequestNoteRepository;
use classes\request\repository\RequestRepository;

$page_title = $contentHeader = 'Forward Request';

$requestId         = Request::GetValue('request_id');
$requestRepository = new RequestRepository();
$groupsRepository  = new GroupRepository();

$request = $requestRepository->GetById($requestId);
/* @var \classes\request\data\Request $request */

if (Request::IsPost()) {
    $action = Request::GetValue('action');
    if ($action == 'Cancel') {
        Response::Redirect('/request.php?action=st_view&request_id=' . $requestId);
    }
    if ($action == 'Forward') {
        $newGroupId = Request::GetValue('new_group_id');

        if ($newGroupId != $request->GroupId) {
            $requestNoteRepository = new RequestNoteRepository();
            $newGroup              = $groupsRepository->GetById($newGroupId);
            $oldGroup              = $groupsRepository->GetById($request->GroupId);
            /* @var Group $oldGroup */
            /* @var Group $newGroup */
            $requestRepository->StartTransaction();

            $requestNote              = new RequestNote();
            $requestNote->CreatedById = $userdata['user_id'];
            $requestNote->CreatedOn   = date('Y-m-d H:i:s');
            $requestNote->Note        = 'Forwarded from group: ' . $oldGroup->Name . ' to group: ' . $newGroup->Name;
            $requestNote->RequestId   = $requestId;

            $requestNoteRepository->Save($requestNote);

            $request->GroupId = $newGroupId;
            $requestRepository->Save($request);

            $requestRepository->CommitTransaction();

            Response::Redirect('/request.php?action=st_list');
        }
        else {
            SessionHelper::SetFlashMessage('You selected the same group for the request');
        }
    }
}

$groups = $groupsRepository->SimpleListAll();
ob_start();
?>

    <form method="post">
        <div class="paragraph">
            Select the group that you want to forward &quot;<?php echo htmlspecialchars($request->Title); ?> to
        </div>
        <div class="paragraph">
            <?php echo FormHelper::Select($groups, 'new_group_id', $request->GroupId, array(
                'label' => 'New Group'
            )); ?>
        </div>
        <?php echo FormHelper::Hidden('request_id', $request->Id); ?>
        <?php echo FormHelper::Button('action', 'Forward'); ?>
        <?php echo FormHelper::Button('action', 'Cancel'); ?>
    </form>
<?php
$page_content = ob_get_clean();