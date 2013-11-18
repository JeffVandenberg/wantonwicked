<?php
/* @var array $userdata */
use classes\core\helpers\FormHelper;
use classes\core\helpers\Request;
use classes\core\helpers\Response;
use classes\request\repository\RequestRepository;

$requestId = Request::GetValue('request_id', 0);
$requestRepository = new RequestRepository();
if (!$requestRepository->MayViewRequest($requestId, $userdata['user_id'])) {
    include 'index_redirect.php';
    die();
}

if (isset($_POST['action'])) {
    if ($_POST['action'] == 'Cancel') {
        Response::Redirect('request.php?action=view&request_id=' . $requestId);
    } elseif ($_POST['action'] == 'Attach Request') {
        $fromRequestId = $_POST['from_request_id'];
        if($requestRepository->AttachRequestToRequest($requestId, $fromRequestId)) {
            $requestRepository->TouchRecord($requestId, $userdata['user_id']);
            Response::Redirect('request.php?action=view&request_id=' . $requestId);
        }
        else {
            die('Unable to attach Request');
        }
    }
}
$request = $requestRepository->FindById($requestId);

$page_title = 'Attach Request to: ' . $request['title'];
$contentHeader = $page_title;

$unattachedRequests = $requestRepository->GetOpenRequestsNotAttachedToRequest($requestId, $request['character_id']);
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