<?php
/* @var array $userdata */
use classes\core\helpers\MenuHelper;
use classes\core\helpers\Request;
use classes\core\helpers\Response;
use classes\request\data\RequestStatus;
use classes\request\repository\RequestRepository;

$requestId = Request::GetValue('bluebook_id', 0);
$requestRepository = new RequestRepository();
if (!$requestRepository->MayViewRequest($requestId, $userdata['user_id'])) {
    Response::Redirect('');
}

$request = $requestRepository->FindById($requestId);

$page_title = 'Bluebook Entry: ' . $request['title'];
$contentHeader = $page_title;

$characterId = $request['character_id'];
require_once('helpers/character_menu.php');
/* @var array $characterMenu */
$characterMenu['Actions'] = array(
    'link' => '#',
    'submenu' => array(
        'Back' => array(
            'link' => '/bluebook.php?action=list&character_id=' . $characterId
        ),
    )
);
if($request['request_status_id'] == RequestStatus::NewRequest) {
    $characterMenu['Actions']['submenu']['Edit'] = array(
        'link' => '/bluebook.php?action=edit&bluebook_id=' . $requestId
    );
}
$menu = MenuHelper::GenerateMenu($characterMenu);
ob_start();
?>
<?php if(!Request::IsAjax()): ?>
    <?php echo $menu; ?>
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
            <?php echo $request['body']; ?>
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
        $(function() {
        })
    </script>
<?php endif; ?>
<?php
$page_content = ob_get_clean();