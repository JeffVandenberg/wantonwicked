<?php
/* @var array $userdata */
use classes\core\helpers\FormHelper;
use classes\core\helpers\Request;
use classes\core\helpers\Response;
use classes\core\helpers\SessionHelper;
use classes\request\repository\RequestRepository;

$requestId = Request::getValue('bluebook_id', 0);
$requestRepository = new RequestRepository();
if (!$requestRepository->MayViewRequest($requestId, $userdata['user_id'])) {
    Response::redirect('');
}

$request = $requestRepository->FindById($requestId);

if(Request::isPost())
{
    if($_POST['action'] == 'Cancel') {
        Response::redirect('/bluebook.php?action=view&bluebook_id=' . $request['id']);
    }
    if($_POST['action'] == 'Update Entry') {
        $newRequest = new \classes\request\data\Request();
        $newRequest->Id = $request['id'];
        $newRequest->CharacterId = $request['character_id'];
        $newRequest->Title = htmlspecialchars(Request::getValue('title'));
        $newRequest->RequestTypeId = $request['request_type_id'];
        $newRequest->GroupId = 0;
        $newRequest->RequestStatusId = $request['request_status_id'];
        $newRequest->Body = Request::getValue('body');
        $newRequest->CreatedById = $request['created_by_id'];
        $newRequest->CreatedOn = $request['created_on'];
        $newRequest->UpdatedById = $userdata['user_id'];
        $newRequest->UpdatedOn = date('Y-m-d H:i:s');
        if(!$requestRepository->save($newRequest))
        {
            SessionHelper::SetFlashMessage('Error Saving Request');
        }
        else
        {
            Response::redirect('bluebook.php?action=view&bluebook_id='.$request['id']);
        }
    }
}


$contentHeader = $page_title = 'Bluebook Entry: ' . $request['title'];

ob_start();
?>
    <form method="post">
        <div class="formInput">
            <label for="title">Title:</label>
            <?php echo FormHelper::Text('title', $request['title']); ?>
        </div>
        <div class="formInput">
            <label for="request-type">Body:</label>
            <?php echo FormHelper::Textarea('body', $request['body']); ?>
        </div>
        <div class="formInput">
            <?php echo FormHelper::Hidden('bluebook_id', $requestId); ?>
            <?php echo FormHelper::Button('action', 'Update Entry'); ?>
            <?php echo FormHelper::Button('action', 'Cancel'); ?>
        </div>
    </form>
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