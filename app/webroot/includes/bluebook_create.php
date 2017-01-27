<?php
/* @var array $userdata */
use classes\character\repository\CharacterRepository;
use classes\core\helpers\FormHelper;
use classes\core\helpers\Request;
use classes\core\helpers\Response;
use classes\core\helpers\SessionHelper;
use classes\request\data\RequestStatus;
use classes\request\data\RequestType;
use classes\request\repository\RequestRepository;
use classes\request\repository\RequestTypeRepository;

$characterId = Request::getValue('character_id', 0);
$characterRepository = new CharacterRepository();
if (!$characterRepository->MayViewCharacter($characterId, $userdata['user_id'])) {
    Response::redirect('/');
}

if (isset($_POST['action'])) {
    if ($_POST['action'] == 'Cancel') {
        Response::redirect('bluebook.php?action=list&character_id=' . $characterId);
    }
    elseif($_POST['action'] == 'Submit Entry')
    {
        $request = new \classes\request\data\Request();
        $request->CharacterId = $characterId;
        $request->Title = htmlspecialchars(Request::getValue('title'));
        $request->RequestTypeId = RequestType::BlueBook;
        $request->GroupId = 0;
        $request->RequestStatusId = RequestStatus::NewRequest;
        $request->Body = Request::getValue('body');
        $request->CreatedById = $userdata['user_id'];
        $request->CreatedOn = date('Y-m-d H:i:s');
        $request->UpdatedById = $userdata['user_id'];
        $request->UpdatedOn = date('Y-m-d H:i:s');

        $requestRepository = new RequestRepository();
        if(!$requestRepository->save($request))
        {
            SessionHelper::SetFlashMessage('Error Saving Request');
        }
        else
        {
            Response::redirect('bluebook.php?action=list&character_id='.$characterId);
        }
    }
}

$character = $characterRepository->FindById($characterId);

$requestTypeRepository = new RequestTypeRepository();
$requestTypes = $requestTypeRepository->simpleListAll();
$page_title = 'Create Bluebook Entry for ' . $character['character_name'];
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
            <?php echo FormHelper::Textarea('body', '', ['class' => 'tinymce-textarea']); ?>
        </div>
        <div class="formInput">
            <?php echo FormHelper::Hidden('character_id', $characterId); ?>
            <?php echo FormHelper::Button('action', 'Submit Entry'); ?>
            <?php echo FormHelper::Button('action', 'Cancel'); ?>
        </div>
    </form>
<?php
$page_content = ob_get_clean();
