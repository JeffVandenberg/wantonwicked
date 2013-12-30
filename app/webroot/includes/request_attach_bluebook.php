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
    } elseif ($_POST['action'] == 'Attach Bluebook') {
        $bluebookId = $_POST['bluebook_id'];
        if($requestRepository->AttachBluebookToRequest($requestId, $bluebookId)) {
            $requestRepository->TouchRecord($requestId, $userdata['user_id']);
            Response::Redirect('request.php?action=view&request_id=' . $requestId);
        }
        else {
            die('Unable to attach Bluebook Entry');
        }
    }
}
$request = $requestRepository->FindById($requestId);

$page_title = 'Attach Bluebook Entry to: ' . $request['title'];
$contentHeader = $page_title;

$unattachedRequests = $requestRepository->ListBlueBookEntriesNotAttachedToRequest($requestId, $request['character_id']);
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
            <?php echo FormHelper::Hidden('request_id', $requestId); ?>
            <?php echo FormHelper::Button('action', 'Attach Bluebook'); ?>
            <?php echo FormHelper::Button('action', 'cancel'); ?>
        </div>
    </form>
<?php else: ?>
    Bluebook Entries to Attach<br />
    <a href="request.php?action=view&request_id=<?php echo $requestId; ?>">Back</a>
<?php endif; ?>

<?php
$page_content = ob_get_clean();