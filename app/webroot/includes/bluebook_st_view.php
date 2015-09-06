<?php
/* @var array $userdata */
use classes\core\helpers\Request;
use classes\log\CharacterLog;
use classes\log\data\ActionType;
use classes\request\data\RequestStatus;
use classes\request\repository\RequestRepository;

$requestId = Request::GetValue('bluebook_id', 0);
$requestRepository = new RequestRepository();
$request = $requestRepository->FindById($requestId);

CharacterLog::LogAction($request['character_id'], ActionType::BlueBookView, 'View Bluebook Entry', $userdata['user_id'], $requestId);
$requestRepository->UpdateStatus($requestId, RequestStatus::InProgress, $userdata['user_id']);
$page_title = 'Bluebook Entry: ' . $request['title'];
$contentHeader = $page_title;

ob_start();
?>
    <?php if(!Request::IsAjax()): ?>
        <a href="/bluebook.php?action=st_list&character_id=<?php echo $request['character_id']; ?>" class="button">Back</a>
    <?php endif; ?>
    <dl>
        <dt>
            Title:
        </dt>
        <dd>
            <?php echo $request['title']; ?>
        </dd>
        <dt>
            Body:
        </dt>
        <dd>
            <div class="tinymce-content">
                <?php echo $request['body']; ?>
            </div>
        </dd>
        <dt>
            Created On:
        </dt>
        <dd>
            <?php echo date('m/d/Y H:i:s', strtotime($request['created_on'])); ?>
        </dd>
        <dt>
            Updated On:
        </dt>
        <dd>
            <?php echo date('m/d/Y H:i:s', strtotime($request['updated_on'])); ?>
        </dd>
    </dl>
    <?php if(!Request::IsAjax()): ?>
        <script>
            $(function(){
                $(".button").button();
            })
        </script>
    <?php endif; ?>
<?php
$page_content = ob_get_clean();