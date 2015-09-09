<?php
/* @var array $userdata */

use classes\core\data\User;
use classes\core\helpers\FormHelper;
use classes\core\helpers\Request;
use classes\core\helpers\Response;
use classes\core\helpers\SessionHelper;
use classes\core\repository\RepositoryManager;
use classes\request\data\RequestNote;
use classes\request\data\RequestStatus;
use classes\request\repository\RequestNoteRepository;
use classes\request\repository\RequestRepository;
use classes\request\RequestMailer;

$requestId = Request::getValue('request_id', 0);
$requestRepository = new RequestRepository();

if (isset($_POST['action'])) {
    if ($_POST['action'] == 'Cancel') {
        Response::redirect('request.php?action=st_view&request_id=' . $requestId);
    }
    elseif ($_POST['action'] == 'Approve') {
        if(trim(Request::getValue('note')) == '') {
            SessionHelper::SetFlashMessage('Please Include a Note');
        }
        else {
            if($requestRepository->UpdateStatus($requestId, RequestStatus::Approved, $userdata['user_id']))
            {
                $requestNote = new RequestNote();
                $requestNote->RequestId = $requestId;
                $requestNote->Note = Request::getValue('note');
                $requestNote->CreatedById = $userdata['user_id'];
                $requestNote->CreatedOn = date('Y-m-d H:i:s');

                $requestNoteRepository = new RequestNoteRepository();
                if($requestNoteRepository->Save($requestNote)) {
                    // send notice to the player
                    $request = $requestRepository->getById($requestId);
                    /* @var \classes\request\data\Request $request */
                    $userRepository = RepositoryManager::GetRepository('classes\core\data\User');
                    $user = $userRepository->getById($request->CreatedById);
                    /* @var User $user */
                    $mailer = new RequestMailer();
                    $mailer->SendMailToPlayer(
                        $user->UserEmail,
                        $userdata['username'],
                        'Approved',
                        $requestNote->Note,
                        $request
                    );
                    SessionHelper::SetFlashMessage('Approved Request');
                    Response::redirect('request.php?action=st_list');
                }
                else {
                    SessionHelper::SetFlashMessage('Error Attaching Note');
                }
            }
            else
            {
                SessionHelper::SetFlashMessage('Error Updating Status');
            }
        }
    }
}
$request = $requestRepository->getById($requestId);
/* @var \classes\request\data\Request $request */
$requestNoteRepository = new RequestNoteRepository();
$requestNotes = $requestNoteRepository->ListByRequestId($requestId);

$page_title = 'Approve Request: ' . $request->Title;
$contentHeader = $page_title;

ob_start();
?>

    <form method="post">
        <div class="formInput">
            <label>
                Note
            </label>
            <?php echo FormHelper::Textarea('note', ''); ?>
        </div>
        <div class="formInput">
            <?php echo FormHelper::Hidden('request_id', $requestId); ?>
            <?php echo FormHelper::Button('action', 'Approve'); ?>
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

    <script type="text/javascript" src="/js/tinymce/tinymce.min.js"></script>
    <script type="text/javascript">
        tinymce.init({
            selector: "textarea",
            menubar: false,
            height: 200,
            paste_preprocess : function(pl, o) {
                //example: keep bold,italic,underline and paragraphs
                //o.content = strip_tags( o.content,'<b><u><i><p>' );

                // remove all tags => plain text
                o.content = strip_tags( o.content,'<br>' );
            },
            plugins: [
                "advlist autolink lists link image charmap print preview anchor",
                "searchreplace wordcount visualblocks code fullscreen",
                "insertdatetime media table contextmenu paste textcolor"
            ],
            toolbar: "undo redo | bold italic | forecolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | copy paste "
        });
    </script>
<?php
$page_content = ob_get_clean();