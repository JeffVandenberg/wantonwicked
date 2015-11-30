<?php
/* @var array $userdata */
use classes\core\helpers\FormHelper;
use classes\core\helpers\MenuHelper;
use classes\core\helpers\Pagination;
use classes\core\helpers\Request;
use classes\core\repository\StorytellerRepository;
use classes\request\repository\RequestRepository;
use classes\request\repository\RequestStatusRepository;
use classes\request\repository\RequestTypeRepository;

$contentHeader = $page_title = "Pending Requests";

$characterId = Request::getValue('character_id', 0);
$page = Request::getValue('page', 1);
$pageSize = Request::getValue('page_size', 20);
$sort = Request::getValue('sort', 'updated_on DESC');
$filter = Request::getValue('filter', array('username' => '', 'title' => '', 'request_type_id' => 0, 'request_status_id' => 0));

$pagination = new Pagination();
$pagination->SetSort($sort);
$pagination->SetParameters(array(
    'character_id' => $characterId,
    'page' => $page,
    'sort' => $sort,
    'filter' => $filter
));

$storytellerRepository = new StorytellerRepository();
$stGroups = $storytellerRepository->ListGroupsForStoryteller($userdata['user_id']);
$groups = array();
foreach ($stGroups as $group) {
    $groups[] = $group['group_id'];
}

$requestRepository = new RequestRepository();
$requests = $requestRepository->ListByGroups($groups, $page, $pageSize, $pagination->GetSort(), $filter);
$count = $requestRepository->ListByByGroupsCount($groups, $filter);

$hasPrev = false;
$hasNext = false;

if ($page > 1) {
    $hasPrev = true;
}
if (($count / $pageSize) > $page) {
    $hasNext = true;
}

$requestStatusRepository = new RequestStatusRepository();
$requestTypeRepository = new RequestTypeRepository();
$requestTypes = $requestTypeRepository->simpleListAll();
$requestTypes = array('All') + $requestTypes;
$requestStatuses = $requestStatusRepository->simpleListAll();
$requestStatuses = array(0 => 'Open', -1 => 'All') + $requestStatuses;

$storytellerMenu = require_once('menus/storyteller_menu.php');
$menu = MenuHelper::GenerateMenu($storytellerMenu);

ob_start();
?>
<?php echo $menu; ?>
    <div style="padding: 10px 0;">
        <form method="get" action="/request.php">
            <h2>Filters</h2>
            <div>
                <?php echo FormHelper::Text('filter[title]', $filter['title'], ['label' => 'Request Name']); ?>
                <?php echo FormHelper::Text('filter[username]', $filter['username'], ['label' => 'User']); ?>
            </div>
            <div>
                <?php echo FormHelper::Select($requestTypes, 'filter[request_type_id]', $filter['request_type_id'], ['label' => 'Request Type']); ?>
                <?php echo FormHelper::Select($requestStatuses, 'filter[request_status_id]', $filter['request_status_id'], ['label' => 'Request Status']); ?>
                <?php echo FormHelper::Hidden('action', 'st_list'); ?>
            </div>
            <div>
                <?php echo FormHelper::Button('page_action', 'Update Filters'); ?>
            </div>
        </form>
    </div>
    <table>
        <tr>
            <th>
                <?php if ($hasPrev): ?>
                    <a href="/request.php?action=st_list&<?php echo $pagination->GetPrev(); ?>">&lt; &lt;</a>
                <?php else: ?>
                    &lt; &lt;
                <?php endif; ?>
                <form method="get" style="display: inline;" action="/request.php">
                    Page:
                    <?php echo FormHelper::Hidden('sort', $sort); ?>
                    <?php echo FormHelper::Hidden('action', 'st_list'); ?>
                    <?php echo FormHelper::Hidden('character_id', $characterId); ?>
                    <?php echo FormHelper::Hidden('filter[title]', $filter['title']); ?>
                    <?php echo FormHelper::Hidden('filter[request_type_id]', $filter['request_type_id']); ?>
                    <?php echo FormHelper::Hidden('filter[request_status_id]', $filter['request_status_id']); ?>
                    <?php echo FormHelper::Text('page', $page, array('style' => 'width: 30px;')); ?>
                </form>
                <?php if ($hasNext): ?>
                    <a href="/request.php?action=st_list&<?php echo $pagination->GetNext(); ?>">&gt; &gt;</a>
                <?php else: ?>
                    &gt; &gt;
                <?php endif; ?>
                Viewing Records (<?php echo (($page - 1) * $pageSize) + 1; ?>
                to <?php echo min($page * $pageSize, $count); ?>)
                Total Records: <?php echo $count; ?>
            </th>
        </tr>
    </table>
    <table>
        <tr>
            <th>
                <a href="/request.php?action=st_list&<?php echo $pagination->GetSortLink('R.title'); ?>">Request</a>
            </th>
            <th>
                <a href="/request.php?action=st_list&<?php echo $pagination->GetSortLink('CB.username_clean'); ?>">User</a>
            </th>
            <th>
                <a href="/request.php?action=st_list&<?php echo $pagination->GetSortLink('G.name'); ?>">Group</a>
            </th>
            <th>
                <a href="/request.php?action=st_list&<?php echo $pagination->GetSortLink('RT.name'); ?>">Type</a>
            </th>
            <th>
                <a href="/request.php?action=st_list&<?php echo $pagination->GetSortLink('RS.name'); ?>">Status</a>
            </th>
            <th>
                <a href="/request.php?action=st_list&<?php echo $pagination->GetSortLink('R.created_on'); ?>">Created</a>
            </th>
            <th>
                <a href="/request.php?action=st_list&<?php echo $pagination->GetSortLink('UB.username'); ?>">Updated
                    By</a>
            </th>
            <th>
                <a href="/request.php?action=st_list&<?php echo $pagination->GetSortLink('R.updated_on'); ?>">Updated</a>
            </th>
            <th>
            </th>
        </tr>

        <?php if (count($requests) > 0): ?>
            <?php foreach ($requests as $request): ?>
                <tr>
                    <td>
                        <a href="/request.php?action=st_view&request_id=<?php echo $request['id']; ?>">
                            <?php echo $request['title']; ?>
                        </a>
                    </td>
                    <td>
                        <?php echo $request['created_by_username']; ?>
                    </td>
                    <td>
                        <?php echo $request['group_name']; ?>
                    </td>
                    <td>
                        <?php echo $request['request_type_name']; ?>
                    </td>
                    <td>
                        <?php echo $request['request_status_name']; ?>
                    </td>
                    <td>
                        <?php echo date('m/d/Y', strtotime($request['created_on'])); ?>
                    </td>
                    <td>
                        <?php echo $request['updated_by_username']; ?>
                    </td>
                    <td>
                        <?php echo date('m/d/Y', strtotime($request['updated_on'])); ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="9" style="text-align: center;">
                    No Requests
                </td>
            </tr>
        <?php endif; ?>
    </table>
<?php
$page_content = ob_get_clean();