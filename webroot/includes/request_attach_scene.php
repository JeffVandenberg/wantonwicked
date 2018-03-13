<?php
use classes\core\helpers\FormHelper;
use classes\core\helpers\Request;
use classes\core\helpers\Response;
use classes\core\helpers\SessionHelper;
use classes\core\helpers\UserdataHelper;
use classes\request\data\RequestCharacter;
use classes\request\repository\RequestCharacterRepository;
use classes\request\repository\RequestRepository;
use classes\scene\repository\SceneRepository;

/* @var array $userdata */
$requestId = Request::getValue('request_id', 0);
$requestRepository = new RequestRepository();
$requestCharacterRepository = new RequestCharacterRepository();
$sceneRepository = new SceneRepository();

if (!UserdataHelper::IsAdmin($userdata) && !$requestRepository->MayViewRequest($requestId, $userdata['user_id'])) {
    Response::redirect('/', 'Unable to view that request');
}

if (isset($_POST['action'])) {
    if ($_POST['action'] == 'Cancel') {
        Response::redirect('request.php?action=view&request_id=' . $requestId);
    } elseif ($_POST['action'] == 'Attach Scene') {
        $sceneId = Request::getValue('scene_id');
        $note = Request::getValue('note');
        if ($requestRepository->AttachSceneToRequest($requestId, $sceneId, $note)) {
            $requestRepository->TouchRecord($requestId, $userdata['user_id']);
            SessionHelper::SetFlashMessage('Attached Scene');
            Response::redirect('request.php?action=view&request_id=' . $requestId);
        } else {
            Response::endRequest('Unable to attach Scene');
        }
    }
}
$request = $requestRepository->FindById($requestId);
$linkedCharacter = $requestCharacterRepository->findLinkedCharacterForUser($requestId, $userdata['user_id']);
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
            <button class="button" name="action" value="Attach Scene" type="submit">Attach Scene</button>
            <button class="button" name="action" value="Cancel" type="submit">Cancel</button>
            <?php echo FormHelper::Hidden('request_id', $requestId); ?>
        </div>
    </form>
<?php else: ?>
    <a href="request.php?action=view&request_id=<?php echo $requestId; ?>">Back</a>
<?php endif; ?>

<?php
$page_content = ob_get_clean();
