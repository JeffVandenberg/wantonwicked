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

$requestId = Request::GetValue('request_id', 0);
$requestRepository = new RequestRepository();

if (isset($_POST['action'])) {
    if ($_POST['action'] == 'Cancel') {
        Response::Redirect('request.php?action=st_view&request_id=' . $requestId);
    }
    elseif ($_POST['action'] == 'Return') {
        if (trim(Request::GetValue('note')) == '') {
            SessionHelper::SetFlashMessage('Please Include a Note');
        }
        else {
            if ($requestRepository->UpdateStatus($requestId, RequestStatus::Returned, $userdata['user_id'])) {
                $requestNote = new RequestNote();
                $requestNote->RequestId = $requestId;
                $requestNote->Note = Request::GetValue('note');
                $requestNote->CreatedById = $userdata['user_id'];
                $requestNote->CreatedOn = date('Y-m-d H:i:s');

                $requestNoteRepository = new RequestNoteRepository();
                if ($requestNoteRepository->Save($requestNote)) {
                    SessionHelper::SetFlashMessage('Returned Request');
                    Response::Redirect('request.php?action=st_list');
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
$request = $requestRepository->FindById($requestId);

$page_title = 'Return Request: ' . $request['title'];
$contentHeader = $page_title;

ob_start();
?>

    <form method="post">
        <div class="formInput">
            <label>
                Character
            </label>
            <a href="view_sheet.php?action=st_view_xp&view_character_id=<?php echo $request['character_id']; ?>"
               target="_blank"><?php echo $request['character_name']; ?></a>
        </div>
        <div class="formInput">
            <label>
                Note
            </label>
            <?php echo FormHelper::Textarea('note', ''); ?>
        </div>
        <div class="formInput">
            <?php echo FormHelper::Hidden('request_id', $requestId); ?>
            <?php echo FormHelper::Button('action', 'Return'); ?>
            <?php echo FormHelper::Button('action', 'Cancel'); ?>
        </div>
    </form>
    <script type="text/javascript" src="/js/tinymce/tinymce.min.js"></script>
    <script type="text/javascript">
        tinymce.init({
            selector: "textarea",
            menubar: false,
            height: 200,
            plugins: [
                "advlist autolink lists link image charmap print preview anchor",
                "searchreplace wordcount visualblocks code fullscreen",
                "insertdatetime media table contextmenu paste textcolor"
            ],
            toolbar: "undo redo | bold italic | forecolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent"
        });
    </script>
<?php
$page_content = ob_get_clean();