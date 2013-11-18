<?php
/* @var array $userdata */
use classes\character\repository\CharacterRepository;
use classes\core\helpers\FormHelper;
use classes\core\helpers\Request;
use classes\core\helpers\Response;
use classes\request\data\RequestStatus;
use classes\request\data\RequestType;
use classes\request\repository\RequestRepository;
use classes\request\repository\RequestTypeRepository;

$characterId = Request::GetValue('character_id', 0);
$characterRepository = new CharacterRepository();
if (!$characterRepository->MayViewCharacter($characterId, $userdata['user_id'])) {
    /*include 'index_redirect.php';
    die();*/
}

if (isset($_POST['action'])) {
    if ($_POST['action'] == 'Cancel') {
        Response::Redirect('bluebook.php?action=list&character_id=' . $characterId);
    }
    elseif($_POST['action'] == 'Submit Entry')
    {
        $request = new \classes\request\data\Request();
        $request->CharacterId = $characterId;
        $request->Title = htmlspecialchars(Request::GetValue('title'));
        $request->RequestTypeId = RequestType::BlueBook;
        $request->GroupId = 0;
        $request->RequestStatusId = RequestStatus::NewRequest;
        $request->Body = Request::GetValue('body');
        $request->CreatedById = $userdata['user_id'];
        $request->CreatedOn = date('Y-m-d H:i:s');
        $request->UpdatedById = $userdata['user_id'];
        $request->UpdatedOn = date('Y-m-d H:i:s');

        $requestRepository = new RequestRepository();
        if(!$requestRepository->Save($request))
        {
            echo mysql_error();
            die();
        }
        else
        {
            Response::Redirect('bluebook.php?action=list&character_id='.$characterId);
        }
    }
}

$character = $characterRepository->FindById($characterId);

$requestTypeRepository = new RequestTypeRepository();
$requestTypes = $requestTypeRepository->SimpleListAll();
$page_title = 'Create Request for ' . $character['Character_Name'];
$contentHeader = $page_title;

ob_start();
?>

    <form method="post">
        <div class="formInput">
            <label for="title">Title:</label>
            <?php echo FormHelper::Text('title', ''); ?>
        </div>
        <div class="formInput">
            <label for="request-type">Body:</label>
            <?php echo FormHelper::Textarea('body'); ?>
        </div>
        <div class="formInput">
            <?php echo FormHelper::Hidden('character_id', $characterId); ?>
            <?php echo FormHelper::Button('action', 'Submit Entry'); ?>
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
