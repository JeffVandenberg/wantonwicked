<?php
/* @var array $userdata */
use classes\core\helpers\FormHelper;
use classes\core\helpers\Request;
use classes\core\helpers\Response;
use classes\request\data\RequestNote;
use classes\request\repository\RequestNoteRepository;
use classes\request\repository\RequestRepository;
$requestId = Request::GetValue('request_id', 0);
$requestRepository = new RequestRepository();
if (!$requestRepository->MayViewRequest($requestId, $userdata['user_id'])) {
    include 'index_redirect.php';
    die();
}

if (isset($_POST['action'])) {
    if ($_POST['action'] == 'Cancel') {
        Response::Redirect('request.php?action=view&request_id=' . $requestId);
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
            Response::Redirect('request.php?action=view&request_id=' . $requestId);
        }
        else
        {
            die('Error Adding Note');
        }
    }
}
$request = $requestRepository->FindById($requestId);
$contentHeader = $page_title = 'Add Note to: ' . $request['title'];

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
            <?php echo FormHelper::Button('action', 'Add Note'); ?>
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