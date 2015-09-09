<?php
/* @var array $userdata */
use classes\core\helpers\FormHelper;
use classes\core\helpers\Request;
use classes\core\helpers\Response;
use classes\core\helpers\SessionHelper;
use classes\request\data\RequestCharacter;
use classes\request\repository\RequestCharacterRepository;
use classes\request\repository\RequestRepository;

$requestId = Request::getValue('request_id', 0);
$requestRepository = new RequestRepository();
$requestCharacterRepository = new RequestCharacterRepository();

if (!$requestRepository->MayViewRequest($requestId, $userdata['user_id'])) {
    Response::redirect('/', 'Unable to view that request');
}

if (isset($_POST['action'])) {
    if ($_POST['action'] == 'Cancel') {
        Response::redirect('request.php?action=view&request_id=' . $requestId);
    } elseif ($_POST['action'] == 'Attach Request') {
        $fromRequestId = $_POST['from_request_id'];
        if($requestRepository->AttachRequestToRequest($requestId, $fromRequestId)) {
            $requestRepository->TouchRecord($requestId, $userdata['user_id']);
            SessionHelper::SetFlashMessage('Attached Request');
            Response::redirect('request.php?action=view&request_id=' . $requestId);
        }
        else {
            Response::endRequest('Unable to attach Request');
        }
    }
}
$request = $requestRepository->FindById($requestId);
$linkedCharacter = $requestCharacterRepository->FindLinkedCharacterForUser($requestId, $userdata['user_id']);
/* @var RequestCharacter $linkedCharacter */

$page_title = 'Attach Request to: ' . $request['title'];
$contentHeader = $page_title;

$unattachedRequests = $requestRepository->GetOpenRequestsNotAttachedToRequest($requestId, $linkedCharacter->CharacterId);
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
                Request to Attach
            </label>
            <?php echo FormHelper::Select($requests, 'from_request_id', ''); ?>
        </div>
        <div class="formInput">
            <?php echo FormHelper::Hidden('request_id', $requestId); ?>
            <?php echo FormHelper::Button('action', 'Attach Request'); ?>
            <?php echo FormHelper::Button('action', 'Cancel'); ?>
        </div>
    </form>
    <?php else: ?>
        No Requests to Attach<br />
        <a href="request.php?action=view&request_id=<?php echo $requestId; ?>">Back</a>
    <?php endif; ?>

<?php
$page_content = ob_get_clean();