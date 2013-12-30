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

if (Request::IsPost()) {
    if ($_POST['action'] == 'Cancel') {
        Response::Redirect('request.php?action=list&character_id=' . $characterId);
    }
    elseif(($_POST['action'] == 'Submit Request') || ($_POST['action'] == 'Add Attachments'))
    {
        $request = new \classes\request\data\Request();
        $request->CharacterId = $characterId;
        $request->Title = htmlspecialchars(Request::GetValue('title'));
        $request->RequestTypeId = Request::GetValue('request_type_id', 0);
        $request->GroupId = Request::GetValue('group_id', 0);
        $request->RequestStatusId = RequestStatus::NewRequest;
        $request->Body = Request::GetValue('body');
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

$requestTypeRepository = new RequestTypeRepository();
$requestTypes = $requestTypeRepository->SimpleListAll();
$page_title = 'Create Request for ' . $character['Character_Name'];
$contentHeader = $page_title;

$groupRepository = new GroupRepository();
$availableGroups = $groupRepository->ListAvailableForCharacter($characterId);
$groups = array();
foreach($availableGroups as $group)
{
    $groups[$group['id']] = $group['name'];
}
$defaultGroup = $groupRepository->FindDefaultGroupForCharacter($characterId);

ob_start();
?>

    <form method="post">
        <div class="formInput">
            <label for="title">Title:</label>
            <?php echo FormHelper::Text('title', ''); ?>
        </div>
        <div class="formInput">
            <label for="title">Group:</label>
            <?php echo FormHelper::Select($groups, 'group_id', $defaultGroup['id']); ?>
        </div>
        <div class="formInput">
            <label for="request-type">Request Type:</label>
            <?php echo FormHelper::Select($requestTypes, 'request_type_id', ''); ?>
        </div>
        <div class="formInput">
            <label for="request-type">Body:</label>
            <?php echo FormHelper::Textarea('body'); ?>
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
