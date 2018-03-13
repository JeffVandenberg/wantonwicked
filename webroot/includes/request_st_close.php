<?php
/* @var array $userdata */

use classes\core\helpers\FormHelper;
use classes\core\helpers\Request;
use classes\core\helpers\Response;
use classes\core\helpers\SessionHelper;
use classes\request\data\RequestNote;
use classes\request\data\RequestStatus;
use classes\request\repository\RequestNoteRepository;
use classes\request\repository\RequestRepository;

$requestId = Request::getValue('request_id', 0);
$requestRepository = new RequestRepository();

if (isset($_POST['action'])) {
    if ($_POST['action'] == 'Cancel') {
        Response::redirect('request.php?action=st_view&request_id=' . $requestId);
    }
    elseif ($_POST['action'] == 'Close') {
        if (trim(Request::getValue('note')) == '') {
            SessionHelper::SetFlashMessage('Please Include a Note');
        }
        else {
            if ($requestRepository->UpdateStatus($requestId, RequestStatus::CLOSED, $userdata['user_id'])) {
                $requestNote = new RequestNote();
                $requestNote->RequestId = $requestId;
                $requestNote->Note = Request::getValue('note');
                $requestNote->CreatedById = $userdata['user_id'];
                $requestNote->CreatedOn = date('Y-m-d H:i:s');

                $requestNoteRepository = new RequestNoteRepository();
                if ($requestNoteRepository->save($requestNote)) {
                    SessionHelper::SetFlashMessage('Closed Request');
                    Response::redirect('request.php?action=st_list');
                }
                else {
                    SessionHelper::SetFlashMessage('Error Attaching Note');
                }
            }
            else {
                SessionHelper::SetFlashMessage('Error Updating Status');
            }
        }
    }
}
$request = $requestRepository->getById($requestId);
/* @var \classes\request\data\Request $request */
$requestNoteRepository = new RequestNoteRepository();
$requestNotes = $requestNoteRepository->listByRequestId($requestId);

$page_title = 'Close Request: ' . $request->Title;
$contentHeader = $page_title;

ob_start();
?>

    <form method="post">
        <div class="formInput">
            <label>
                Note
            </label>
            <?php echo FormHelper::Textarea('note', '', ['class' => 'tinymce-textarea']); ?>
        </div>
        <div class="formInput">
            <?php echo FormHelper::Hidden('request_id', $requestId); ?>
            <?php echo FormHelper::Button('action', 'Close'); ?>
            <?php echo FormHelper::Button('action', 'Cancel'); ?>
        </div>
    </form>
    <h3>Request</h3>
    <div class="tinymce-content">
        <?php echo $request->Body; ?>
    </div>
    <h3>Past Notes</h3>
<?php if (count($requestNotes) > 0): ?>
    <dl>
        <?php foreach ($requestNotes as $note): ?>
            <dt>
                <?php echo $note['username']; ?>
                wrote on
                <?php echo date('m/d/Y H:i:s', strtotime($note['created_on'])); ?>
            </dt>
            <dd>
                <div class="tinymce-content">
                    <?php echo $note['note']; ?>
                </div>
            </dd>
        <?php endforeach; ?>
    </dl>
<?php else: ?>
    <div class="paragraph">
        No Notes for this Request
    </div>
<?php endif; ?>
<?php
$page_content = ob_get_clean();
