<?php
/* @var array $userdata */

use classes\core\helpers\FormHelper;
use classes\core\helpers\Request;
use classes\core\helpers\Response;
use classes\core\helpers\SessionHelper;
use classes\request\repository\GroupRepository;
use classes\request\repository\RequestRepository;
use classes\request\repository\RequestTypeRepository;

$requestId = Request::getValue('request_id', 0);
$requestRepository = new RequestRepository();
if (!$userdata['is_admin'] && !$requestRepository->MayViewRequest($requestId, $userdata['user_id'])) {
    Response::redirect('/', 'Unable to view that request');
}

$request = $requestRepository->getById($requestId);
/* @var \classes\request\data\Request $request */

if(Request::isPost())
{
    if($_POST['action'] == 'Cancel') {
        Response::redirect('request.php?action=list&character_id=' . $request->CharacterId);
    }
    if($_POST['action'] == 'Update Request') {
        $request->Title = htmlspecialchars(Request::getValue('title'));
        $request->GroupId = Request::getValue('group_id', 1);
        $request->RequestTypeId = Request::getValue('request_type_id', 1);
        $request->Body = Request::getValue('body');
        $request->UpdatedById = $userdata['user_id'];
        $request->UpdatedOn = date('Y-m-d H:i:s');
        if($requestRepository->save($request)) {
            SessionHelper::SetFlashMessage('Updated Request');
            Response::redirect('request.php?action=view&request_id=' . $request->Id);
        }
        else {
            SessionHelper::SetFlashMessage('Error Updating Request');
        }
    }
}

$contentHeader = $page_title = 'Edit Request: ' . $request->Title;;
$groupRepository = new GroupRepository();
$groups = $groupRepository->simpleListAll();
$requestTypeRepository = new RequestTypeRepository();
$requestTypes = $requestTypeRepository->simpleListAll();


ob_start();
?>
    <form method="post">
        <div class="formInput">
            <label for="title">Title:</label>
            <?php echo FormHelper::Text('title', $request->Title, ['maxlength' => 100]); ?>
        </div>
        <div class="formInput">
            <label for="title">Group:</label>
            <?php echo FormHelper::Select($groups, 'group_id', $request->GroupId); ?>
        </div>
        <div class="formInput">
            <label for="request-type">Request Type:</label>
            <?php echo FormHelper::Select($requestTypes, 'request_type_id', $request->RequestTypeId); ?>
        </div>
        <div class="formInput">
            <label for="request-type">Body:</label>
            <?php echo FormHelper::Textarea('body', $request->Body); ?>
        </div>
        <div class="formInput">
            <?php echo FormHelper::Hidden('character_id', $characterId); ?>
            <?php echo FormHelper::Button('action', 'Update Request'); ?>
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
            toolbar: "undo redo | bold italic | forecolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent"
        });

        $(function() {
            var submitted = true;
            $('form').submit(function() {
                if(submitted) {
                    submitted = true;
                    return true;
                }
                else {
                    return false;
                }
            })
        });
    </script>
<?php
$page_content = ob_get_clean();