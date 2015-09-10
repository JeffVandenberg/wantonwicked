<?php
/* @var array $userdata */
use classes\core\helpers\MenuHelper;
use classes\core\helpers\Request;
use classes\core\helpers\Response;
use classes\core\helpers\SessionHelper;
use classes\request\repository\RequestRepository;

$requestId = Request::getValue('request_id', 0);
$requestRepository = new RequestRepository();

if (!$userdata['is_admin'] && !$requestRepository->MayViewRequest($requestId, $userdata['user_id'])) {
    SessionHelper::SetFlashMessage('Unable to view Request History');
    Response::redirect('/');
}

$request = $requestRepository->getById($requestId);
/* @var \classes\request\data\Request $request */

$contentHeader = $page_title = $request->Title . ' History';

$characterId = $request->CharacterId;
require_once('menus/character_menu.php');
$characterMenu['Actions'] = array(
    'link' => '#',
    'submenu' => array(
        'Back' => array(
            'link' => 'request.php?action=view&request_id=' . $request->Id
        ),
    )
);

$menu = MenuHelper::GenerateMenu($characterMenu);
ob_start();
?>

<?php echo $menu; ?>
<table style="width: 50%;margin: 0 auto;">
    <tr>
        <th>
            Request Status
        </th>
        <th>
            Created By
        </th>
        <th>
            Created On
        </th>
    </tr>
    <?php foreach($request->RequestStatusHistory as $requestStatusHistory): ?>
        <tr>
            <td>
                <?php echo $requestStatusHistory->RequestStatus->Name; ?>
            </td>
            <td>
                <?php echo $requestStatusHistory->CreatedBy->Username; ?>
            </td>
            <td>
                <?php echo date('m/d/Y H:i:s', strtotime($requestStatusHistory->CreatedOn)); ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
<?php
$page_content = ob_get_clean();