<?php
use classes\core\helpers\FormHelper;
use classes\core\helpers\Request;
use classes\core\helpers\Response;
use classes\core\helpers\SessionHelper;
use classes\request\data\RequestCharacter;
use classes\request\repository\RequestCharacterRepository;
use classes\request\repository\RequestRepository;
use classes\scene\repository\SceneRepository;

/* @var array $userdata */
$requestId = Request::GetValue('request_id', 0);
$requestRepository = new RequestRepository();
$requestCharacterRepository = new RequestCharacterRepository();
$sceneRepository = new SceneRepository();

if (!$requestRepository->MayViewRequest($requestId, $userdata['user_id'])) {
    Response::Redirect('/', 'Unable to view that request');
}

if (isset($_POST['action'])) {
    if ($_POST['action'] == 'Cancel') {
        Response::Redirect('request.php?action=view&request_id=' . $requestId);
    } elseif ($_POST['action'] == 'Attach Scene') {
        $sceneId = Request::GetValue('scene_id');
        $note = Request::GetValue('note');
        if ($requestRepository->AttachSceneToRequest($requestId, $sceneId, $note)) {
            $requestRepository->TouchRecord($requestId, $userdata['user_id']);
            SessionHelper::SetFlashMessage('Attached Scene');
            Response::Redirect('request.php?action=view&request_id=' . $requestId);
        } else {
            Response::EndRequest('Unable to attach Scene');
        }
    }
}
$request = $requestRepository->FindById($requestId);
$linkedCharacter = $requestCharacterRepository->FindLinkedCharacterForUser($requestId, $userdata['user_id']);
/* @var RequestCharacter $linkedCharacter */

$page_title = 'Attach Scene to: ' . $request['title'];
$contentHeader = $page_title;

$scenes = $sceneRepository->ListScenesForCharacter($linkedCharacter->CharacterId);

ob_start();
?>

<?php if (count($scenes) > 0): ?>
    <form method="post">
        <div class="formInput">
            <label>
                Scene to Attach
            </label>
            <?php echo FormHelper::Select($scenes, 'scene_id', ''); ?>
        </div>
        <div class="formInput">
            <label>
                Note
            </label>
            <?php echo FormHelper::Textarea('note', '', array('class' => 'tinymce-textarea')); ?>
        </div>
        <div class="formInput">
            <?php echo FormHelper::Hidden('request_id', $requestId); ?>
            <?php echo FormHelper::Button('action', 'Attach Scene'); ?>
            <?php echo FormHelper::Button('action', 'Cancel'); ?>
        </div>
    </form>
<?php else: ?>
    <a href="request.php?action=view&request_id=<?php echo $requestId; ?>">Back</a>
<?php endif; ?>

<?php
$page_content = ob_get_clean();