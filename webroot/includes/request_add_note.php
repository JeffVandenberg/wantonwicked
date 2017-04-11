<?php
/* @var array $userdata */
use classes\core\helpers\FormHelper;
use classes\core\helpers\Request;
use classes\core\helpers\Response;
use classes\request\data\RequestNote;
use classes\request\repository\RequestNoteRepository;
use classes\request\repository\RequestRepository;

$requestId = Request::getValue('request_id', 0);
$requestRepository = new RequestRepository();
if (!$requestRepository->MayViewRequest($requestId, $userdata['user_id'])) {
    Response::redirect('/', 'Unable to view that request');
}

if (isset($_POST['action'])) {
    if ($_POST['action'] == 'Cancel') {
        Response::redirect('request.php?action=view&request_id=' . $requestId);
    } elseif ($_POST['action'] == 'Add Note') {
        $requestNote = new RequestNote();
        $requestNote->RequestId = $requestId;
        $requestNote->Note = $_POST['note'];
        $requestNote->CreatedById = $userdata['user_id'];
        $requestNote->CreatedOn = date('Y-m-d H:i:s');
        $requestNoteRepository = new RequestNoteRepository();
        if($requestNoteRepository->Save($requestNote))
        {
            $requestRepository->TouchRecord($requestId, $userdata['user_id']);
            Response::redirect('request.php?action=view&request_id=' . $requestId);
        }
        else
        {
            Response::endRequest('Error updating request');
        }
    }
}
$request = $requestRepository->getById($requestId);
/* @var \classes\request\data\Request $request */
$requestNoteRepository = new RequestNoteRepository();
$requestNotes = $requestNoteRepository->ListByRequestId($requestId);

$contentHeader = $page_title = 'Add Note to: ' . $request->Title;

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
            <?php echo FormHelper::Button('action', 'Add Note'); ?>
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
