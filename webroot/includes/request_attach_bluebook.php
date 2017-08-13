<?php
/* @var array $userdata */
use classes\core\helpers\FormHelper;
use classes\core\helpers\Request;
use classes\core\helpers\Response;
use classes\core\helpers\SessionHelper;
use classes\core\helpers\UserdataHelper;
use classes\request\data\RequestCharacter;
use classes\request\repository\RequestCharacterRepository;
use classes\request\repository\RequestRepository;

$requestId = Request::getValue('request_id', 0);
$requestRepository = new RequestRepository();
$requestCharacterRepository = new RequestCharacterRepository();
if (!UserdataHelper::IsAdmin($userdata) && !$requestRepository->MayViewRequest($requestId, $userdata['user_id'])) {
    Response::redirect('/', 'Unable to view that request');
}

if (isset($_POST['action'])) {
    if ($_POST['action'] == 'Cancel') {
        Response::redirect('request.php?action=view&request_id=' . $requestId);
    } elseif ($_POST['action'] == 'Attach Bluebook') {
        $bluebookId = $_POST['bluebook_id'];
        if($requestRepository->AttachBluebookToRequest($requestId, $bluebookId)) {
            $requestRepository->TouchRecord($requestId, $userdata['user_id']);
            SessionHelper::SetFlashMessage('Attached Bluebook Entry');
            Response::redirect('request.php?action=view&request_id=' . $requestId);
        }
        else {
            Response::endRequest('Unable to attach bluebook');
        }
    }
}
$request = $requestRepository->FindById($requestId);
$linkedCharacter = $requestCharacterRepository->FindLinkedCharacterForUser($requestId, $userdata['user_id']);
/* @var RequestCharacter $linkedCharacter */

if(!$linkedCharacter->Id) {
    Response::redirect('/request.php?action=view&request_id='.$requestId, 'You do not have a character linked to the request.');
}

$page_title = 'Attach Bluebook Entry to: ' . $request['title'];
$contentHeader = $page_title;

$unattachedRequests = $requestRepository->ListBlueBookEntriesNotAttachedToRequest($requestId, $linkedCharacter->CharacterId);
$requests = array();
foreach ($unattachedRequests as $unattachedRequest) {
    $requests[$unattachedRequest['id']] = $unattachedRequest['title'];
}
ob_start();
?>

<?php if(count($requests) > 0): ?>
    <form method="post">
        <div class="formInput">
            <label>
                Bluebook Entry to Attach
            </label>
            <?php echo FormHelper::Select($requests, 'bluebook_id', ''); ?>
        </div>
        <div class="formInput">
            <button class="button" name="action" value="Attach Bluebook">Attach Bluebook</button>
            <button class="button" name="action" value="Cancel">Cancel</button>
            <?php echo FormHelper::Hidden('request_id', $requestId); ?>
        </div>
    </form>
<?php else: ?>
    Bluebook Entries to Attach<br />
    <a href="request.php?action=view&request_id=<?php echo $requestId; ?>">Back</a>
<?php endif; ?>

<?php
$page_content = ob_get_clean();
