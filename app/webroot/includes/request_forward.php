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

$requestId         = Request::getValue('request_id');
$requestRepository = new RequestRepository();
$groupsRepository  = new GroupRepository();

$request = $requestRepository->getById($requestId);
/* @var \classes\request\data\Request $request */

if (!$requestRepository->MayViewRequest($requestId, $userdata['user_id'])) {
    Response::redirect('/', 'You may not view that request');
}

if (Request::isPost()) {
    $action = Request::getValue('action');
    if ($action == 'Cancel') {
        Response::redirect('/request.php?action=view&request_id=' . $requestId);
    }
    if ($action == 'Forward') {
        $newGroupId = Request::getValue('new_group_id');

        if ($newGroupId != $request->GroupId) {
            $requestNoteRepository = new RequestNoteRepository();
            $newGroup              = $groupsRepository->getById($newGroupId);
            $oldGroup              = $groupsRepository->getById($request->GroupId);
            /* @var Group $oldGroup */
            /* @var Group $newGroup */
            $requestRepository->startTransaction();

            $requestNote              = new RequestNote();
            $requestNote->CreatedById = $userdata['user_id'];
            $requestNote->CreatedOn   = date('Y-m-d H:i:s');
            $requestNote->Note        = 'Forwarded from group: ' . $oldGroup->Name . ' to group: ' . $newGroup->Name;
            $requestNote->RequestId   = $requestId;

            $requestNoteRepository->Save($requestNote);

            $request->GroupId = $newGroupId;
            $requestRepository->save($request);

            $requestRepository->commitTransaction();

            Response::redirect('/request.php?action=view&request_id=' . $requestId, 'Request Forwarded');
        }
        else {
            SessionHelper::SetFlashMessage('You selected the same group for your request');
        }
    }
}

$groups = $groupsRepository->simpleListAll();
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