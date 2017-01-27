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

$note = Request::getValue('note', '');
if (Request::isPost()) {
    $action = Request::getValue('action');
    if ($action == 'Cancel') {
        Response::redirect('/request.php?action=st_view&request_id=' . $requestId);
    }
    if ($action == 'Forward') {
        $newGroupId = Request::getValue('new_group_id');

        if(trim($note) == '') {
            SessionHelper::SetFlashMessage('Please Include a Note');
        }
        else if ($newGroupId == $request->GroupId){
            SessionHelper::SetFlashMessage('You selected the same group for the request');
        }
        else {
            $requestNoteRepository = new RequestNoteRepository();
            $newGroup              = $groupsRepository->getById($newGroupId);
            $oldGroup              = $groupsRepository->getById($request->GroupId);
            /* @var Group $oldGroup */
            /* @var Group $newGroup */
            $requestRepository->startTransaction();

            $requestNote              = new RequestNote();
            $requestNote->CreatedById = $userdata['user_id'];
            $requestNote->CreatedOn   = date('Y-m-d H:i:s');
            $requestNote->Note        = 'Forwarded from group: ' . $oldGroup->Name . ' to group: ' . $newGroup->Name . ' with Note: <br />' . $note;
            $requestNote->RequestId   = $requestId;

            $requestNoteRepository->Save($requestNote);

            $request->GroupId = $newGroupId;
            $requestRepository->save($request);

            $requestRepository->commitTransaction();

            Response::redirect('/request.php?action=st_list');
        }
    }
}

$groups = $groupsRepository->simpleListAll();
ob_start();
?>

    <form method="post">
        <div class="paragraph">
            Select the group that you want to forward &quot;<?php echo htmlspecialchars($request->Title); ?>&quot; to
        </div>
        <div class="paragraph">
            <?php echo FormHelper::Select($groups, 'new_group_id', $request->GroupId, array(
                'label' => 'New Group'
            )); ?>
        </div>
        <div class="formInput">
            <label>
                Note
            </label>
            <?php echo FormHelper::Textarea('note', $note, ['class' => 'tinymce-textarea']); ?>
        </div>
        <?php echo FormHelper::Hidden('request_id', $request->Id); ?>
        <?php echo FormHelper::Button('action', 'Forward'); ?>
        <?php echo FormHelper::Button('action', 'Cancel'); ?>
    </form>
<?php
$page_content = ob_get_clean();
