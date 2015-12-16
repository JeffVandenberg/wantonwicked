<?php
/* @var array $userdata */
use classes\character\repository\CharacterRepository;
use classes\core\helpers\FormHelper;
use classes\core\helpers\Request;
use classes\core\helpers\Response;
use classes\core\helpers\SessionHelper;
use classes\core\repository\RepositoryManager;
use classes\request\data\RequestCharacter;
use classes\request\data\RequestStatus;
use classes\request\repository\GroupRepository;
use classes\request\repository\RequestRepository;
use classes\request\repository\RequestTypeRepository;
use classes\request\RequestMailer;

$characterId = Request::getValue('character_id', 0);
if ($characterId) {
    $characterRepository = new CharacterRepository();
    if (!$characterRepository->MayViewCharacter($characterId, $userdata['user_id'])) {
        Response::redirect('/', 'Unable to view that request');
    }
}

$title = Request::getValue('title');
$requestTypeId = Request::getValue('request_type_id', 0);
$groupId = Request::getValue('group_id');
$body = Request::getValue('body');

if (Request::isPost()) {
    if ($_POST['action'] == 'Cancel') {
        if($characterId) {
            Response::redirect('request.php?action=list&character_id=' . $characterId);
        }
        else {
            Response::redirect('request.php?action=dashboard');
        }
    } elseif (($_POST['action'] == 'Submit Request') || ($_POST['action'] == 'Add Attachments')) {
        $request = new \classes\request\data\Request();
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
        if (!$requestRepository->save($request)) {
            SessionHelper::SetFlashMessage("Error Creating Your Request.");
        } else {
            if ($characterId) {
                $requestCharacter = new RequestCharacter();
                $requestCharacter->CharacterId = $characterId;
                $requestCharacter->RequestId = $request->Id;
                $requestCharacter->IsPrimary = true;
                $requestCharacterRepository = RepositoryManager::GetRepository('classes\request\data\RequestCharacter');
                $requestCharacterRepository->save($requestCharacter);
            }
            if ($_POST['action'] == 'Submit Request') {
                $request->RequestStatusId = RequestStatus::Submitted;
                $requestRepository->save($request);

                $mailer = new RequestMailer();
                $mailer->newRequestSubmission($request);
            }
            Response::redirect('request.php?action=view&request_id=' . $request->Id);
        }
    }
}

$groupRepository = new GroupRepository();
$requestTypeRepository = new RequestTypeRepository();

$page_title = 'Create Request';
if ($characterId) {
    $character = $characterRepository->FindById($characterId);
    $page_title .= ' for ' . $character['character_name'];
    $defaultGroup = $groupRepository->FindDefaultGroupForCharacter($characterId);
    $defaultGroupId = $defaultGroup['id'];

} else {
    $defaultGroupId = 1;
}

$contentHeader = $page_title;

// prepare variables for page
$groups = $groupRepository->simpleListAll();
$requestTypes = $requestTypeRepository->ListForGroupId($defaultGroupId);
$requestTypeOptions = array();
foreach ($requestTypes as $requestType) {
    $requestTypeOptions[$requestType->Id] = $requestType->Name;
}
ob_start();
?>

    <form method="post">
        <div class="formInput">
            <label for="title">Title:</label>
            <?php echo FormHelper::Text('title', $title, ['maxlength' => 100]); ?>
        </div>
        <div class="formInput">
            <label for="title">Group:</label>
            <?php echo FormHelper::Select($groups, 'group_id', $defaultGroupId); ?>
        </div>
        <div class="formInput">
            <label for="request-type">Request Type:</label>
            <?php echo FormHelper::Select($requestTypeOptions, 'request_type_id', $requestTypeId); ?>
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
            paste_preprocess : function(pl, o) {
                //example: keep bold,italic,underline and paragraphs
                //o.content = strip_tags( o.content,'<b><u><i><p>' );

                // remove all tags => plain text
                o.content = strip_tags( o.content,'<br>' );
            },
            plugins: [
                "advlist autolink lists link image charmap print preview anchor",
                "searchreplace wordcount visualblocks code fullscreen",
                "insertdatetime media table contextmenu paste textcolor template"
            ],
            toolbar1: "undo redo | bold italic | forecolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | copy paste | template",
            templates: '/requestTemplates/getList'
        });

        $(function () {
            var submitted = true;
            $('form').submit(function () {
                if (submitted) {
                    submitted = true;
                    return true;
                }
                else {
                    return false;
                }
            });
            $("#group-id").change(function () {
                $.get(
                    '/groups/listRequestTypes/' + $(this).val() + '.json',
                    function (data) {
                        var list = $("#request-type-id");
                        list.empty();
                        for (var i = 0; i < data.list.length; i++) {
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
