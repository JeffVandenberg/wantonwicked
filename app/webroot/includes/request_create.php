<?php
/* @var array $userdata */
use classes\character\repository\CharacterRepository;
use classes\core\helpers\FormHelper;
use classes\core\helpers\Request;
use classes\core\helpers\Response;
use classes\core\helpers\SessionHelper;
use classes\core\repository\RepositoryManager;
use classes\request\data\RequestStatus;
use classes\request\data\RequestStatusHistory;
use classes\request\repository\GroupRepository;
use classes\request\repository\RequestRepository;
use classes\request\repository\RequestTypeRepository;

$characterId = Request::GetValue('character_id', 0);
$characterRepository = new CharacterRepository();
if (!$characterRepository->MayViewCharacter($characterId, $userdata['user_id'])) {
    include 'index_redirect.php';
    die();
}

$title = Request::GetValue('title');
$requestTypeId = Request::GetValue('request_type_id', 0);
$groupId = Request::GetValue('group_id');
$body = Request::GetValue('body');

if (Request::IsPost()) {
    if ($_POST['action'] == 'Cancel') {
        Response::Redirect('request.php?action=list&character_id=' . $characterId);
    }
    elseif(($_POST['action'] == 'Submit Request') || ($_POST['action'] == 'Add Attachments'))
    {
        $request = new \classes\request\data\Request();
        $request->CharacterId = $characterId;
        $request->Title = htmlspecialchars($title);
        $request->RequestTypeId = $requestTypeId;
        $request->GroupId = $groupId;
        $request->RequestStatusId = RequestStatus::NewRequest;
        $request->Body = $body;
        $request->CreatedById = $userdata['user_id'];
        $request->CreatedOn = date('Y-m-d H:i:s');
        $request->UpdatedById = $userdata['user_id'];
        $request->UpdatedOn = date('Y-m-d H:i:s');

        $requestRepository = new RequestRepository();
        if(!$requestRepository->Save($request))
        {
            SessionHelper::SetFlashMessage("Error Creating Your Request.");
        }
        else
        {
            if($_POST['action'] == 'Submit Request') {
                $request->RequestStatusId = RequestStatus::Submitted;
                $requestRepository->Save($request);
            }
            Response::Redirect('request.php?action=view&request_id='.$request->Id);
        }
    }
}

$character = $characterRepository->FindById($characterId);

$page_title = 'Create Request for ' . $character['character_name'];
$contentHeader = $page_title;

$groupRepository = new GroupRepository();
$groups = $groupRepository->SimpleListAll($characterId);
$defaultGroup = $groupRepository->FindDefaultGroupForCharacter($characterId);
$requestTypeRepository = new RequestTypeRepository();
$requestTypes = $requestTypeRepository->ListForGroupId($defaultGroup['id']);
$requestTypeOptions = array();
foreach($requestTypes as $requestType)
{
    $requestTypeOptions[$requestType->Id] = $requestType->Name;
}
ob_start();
?>

    <form method="post">
        <div class="formInput">
            <label for="title">Title:</label>
            <?php echo FormHelper::Text('title', $title); ?>
        </div>
        <div class="formInput">
            <label for="title">Group:</label>
            <?php echo FormHelper::Select($groups, 'group_id', $defaultGroup['id']); ?>
        </div>
        <div class="formInput">
            <label for="request-type">Request Type:</label>
            <?php echo FormHelper::Select($requestTypeOptions   , 'request_type_id', $requestTypeId); ?>
        </div>
        <div class="formInput">
            <label for="request-type">Body:</label>
            <?php echo FormHelper::Textarea('body', $body, array('class' => 'tinymce-request-input')); ?>
        </div>
        <div class="formInput">
            <?php echo FormHelper::Hidden('character_id', $characterId); ?>
            <?php echo FormHelper::Button('action', 'Submit Request'); ?>
            <?php echo FormHelper::Button('action', 'Add Attachments'); ?>
            <?php echo FormHelper::Button('action', 'Cancel'); ?>
        </div>
    </form>
    <script type="text/javascript" src="/js/tinymce/tinymce.min.js"></script>
    <script type="text/javascript">
        tinymce.init({
                selector: "textarea.tinymce-request-input",
            menubar: false,
            height: 200,
            plugins: [
                "advlist autolink lists link image charmap print preview anchor",
                "searchreplace wordcount visualblocks code fullscreen",
                "insertdatetime media table contextmenu paste textcolor template"
            ],
            toolbar1: "undo redo | bold italic | forecolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | copy paste | template",
            templates: '/requestTemplates/getList'
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
            });
            $("#group-id").change(function() {
                $.get(
                    '/groups/listRequestTypes/' + $(this).val() + '.json',
                    function(data) {
                        var list = $("#request-type-id");
                        list.empty();
                        for(var i = 0; i < data.list.length; i++) {
                            var item = data.list[i];
                            list.append(
                                $('<option>')
                                    .text(item.name)
                                    .val(item.id)
                            );
                        }
                    });
            });
        });
    </script>
<?php
$page_content = ob_get_clean();
