<?php
/* @var array $userdata */
use classes\character\repository\CharacterRepository;
use classes\core\helpers\FormHelper;
use classes\core\helpers\MenuHelper;
use classes\core\helpers\Pagination;
use classes\core\helpers\Request;
use classes\core\helpers\Response;
use classes\core\helpers\UserdataHelper;
use classes\request\data\RequestStatus;
use classes\request\repository\RequestRepository;
use classes\request\repository\RequestStatusRepository;
use classes\request\repository\RequestTypeRepository;

$characterId = Request::getValue('character_id', 0);
$statusId = Request::getValue('status_id', 0);
$page = Request::getValue('page', 1);
$pageSize = Request::getValue('page_size', 10);
$sort = Request::getValue('sort', 'updated_on DESC');
$filter = Request::getValue('filter', array('title' => '', 'request_type_id' => 0, 'request_status_id' => 0));

$characterRepository = new CharacterRepository();
if (!UserdataHelper::IsAdmin($userdata) && !$characterRepository->MayViewCharacter($characterId, $userdata['user_id'])) {
    Response::redirect('/');
}

$pagination = new Pagination();
$pagination->SetSort($sort);
$pagination->SetParameters(array(
    'character_id' => $characterId,
    'page' => $page,
    'sort' => $sort,
    'filter' => $filter
));
$character = $characterRepository->FindById($characterId);

$requestRepository = new RequestRepository();
$requests = $requestRepository->ListByCharacterId($characterId, $page, $pageSize, $pagination->GetSort(), $statusId, $filter);
$count = $requestRepository->ListByCharacterIdCount($characterId, $statusId, $filter);

$hasPrev = false;
$hasNext = false;

if ($page > 1) {
    $hasPrev = true;
}
if (($count / $pageSize) > $page) {
    $hasNext = true;
}

$associatedRequests = $requestRepository->ListRequestAssociatedWith($characterId);
$requestStatusRepository = new RequestStatusRepository();
$requestTypeRepository = new RequestTypeRepository();
$requestTypes = $requestTypeRepository->simpleListAll();
$requestTypes = [0 => 'All'] + $requestTypes;
$requestStatuses = $requestStatusRepository->simpleListAll();
$requestStatuses = [0 => 'All'] + $requestStatuses;

$contentHeader = $page_title = "Requests for " . $character['character_name'];

require_once('menus/character_menu.php');
$characterMenu['Help'] = [
    'link' => '#',
    'submenu' => [
        'Request System Help' => [
            'link' => '/wiki/GameRef/GameInterfaceHelp'
        ]
    ]
];
$characterMenu['Create Request'] = [
    'link' => "request.php?action=create&character_id=$characterId"
];
$menu = MenuHelper::GenerateMenu($characterMenu);

ob_start();
?>
<?php echo $menu; ?>
    <div class="">
        <a class="button" href="/request.php?action=create&character_id=<?php echo $characterId; ?>">New Request</a>
    </div>
    <table>
        <tr>
            <th>
                <?php if ($hasPrev): ?>
                    <a href="/request.php?action=list&<?php echo $pagination->GetPrev(); ?>" title="Previous Page">
                        &lt; &lt;</a>
                <?php else: ?>
                    &lt; &lt;
                <?php endif; ?>
                <form method="get" style="display: inline;" action="/request.php">
                    Page:
                    <?php echo FormHelper::Hidden('character_id', $characterId); ?>
                    <?php echo FormHelper::Hidden('sort', $sort); ?>
                    <?php echo FormHelper::Hidden('action', 'list'); ?>
                    <?php echo FormHelper::Hidden('filter[title]', $filter['title']); ?>
                    <?php echo FormHelper::Hidden('filter[request_type_id]', $filter['request_type_id']); ?>
                    <?php echo FormHelper::Hidden('filter[request_status_id]', $filter['request_status_id']); ?>
                    <?php echo FormHelper::Text('page', $page, array('style' => 'width: 30px;display:inline;')); ?>
                </form>
                <?php if ($hasNext): ?>
                    <a href="/request.php?action=list&<?php echo $pagination->GetNext(); ?>" title="Next Page">
                        &gt; &gt;</a>
                <?php else: ?>
                    &gt; &gt;
                <?php endif; ?>
                -
                Viewing Records (<?php echo (($page - 1) * $pageSize) + 1; ?>
                to <?php echo min($page * $pageSize, $count); ?>)
                Total Records: <?php echo $count; ?>
            </th>
        </tr>
    </table>
    <form method="get" action="/request.php">
        <table>
            <tr>
                <th>
                    <a href="/request.php?action=list&<?php echo $pagination->GetSortLink('R.title'); ?>">Name</a>
                </th>
                <th>
                    <a href="/request.php?action=list&<?php echo $pagination->GetSortLink('RT.name'); ?>">Type</a>
                </th>
                <th>
                    <a href="/request.php?action=list&<?php echo $pagination->GetSortLink('RS.name'); ?>">Status</a>
                </th>
                <th>
                    <a href="/request.php?action=list&<?php echo $pagination->GetSortLink('R.created_on'); ?>">Created
                        On</a>
                </th>
                <th>
                    <a href="/request.php?action=list&<?php echo $pagination->GetSortLink('UB.username'); ?>">Updated
                        By</a>
                </th>
                <th>
                    <a href="/request.php?action=list&<?php echo $pagination->GetSortLink('R.updated_on'); ?>">Updated
                        On</a>
                </th>
                <th>

                </th>
            </tr>
            <tr>
                <td>
                    <?php echo FormHelper::Text('filter[title]', $filter['title']); ?>
                </td>
                <td>
                    <?php echo FormHelper::Select($requestTypes, 'filter[request_type_id]', $filter['request_type_id']); ?>
                </td>
                <td>
                    <?php echo FormHelper::Select($requestStatuses, 'filter[request_status_id]', $filter['request_status_id']); ?>
                </td>
                <td></td>
                <td></td>
                <td></td>
                <td>
                    <?php echo FormHelper::Hidden('character_id', $characterId); ?>
                    <?php echo FormHelper::Hidden('action', 'list'); ?>
                    <button class="button" name="page_action" value="Filter">Filter</button>
                </td>
            </tr>
            <?php if (count($requests) > 0): ?>
                <?php foreach ($requests as $request): ?>
                    <tr>
                        <td>
                            <?php echo $request['title']; ?>
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
                        <td>
                            <a href="/request.php?action=view&request_id=<?php echo $request['id']; ?>">
                                View
                            </a>
                            <a href="/request.php?action=add_note&request_id=<?php echo $request['id']; ?>">
                                Add Note
                            </a>
                            <?php if ($request['request_status_id'] == RequestStatus::NewRequest): ?>
                                <a href="/request.php?action=edit&request_id=<?php echo $request['id']; ?>">
                                    Edit
                                </a>
                                <a href="/request.php?action=delete&request_id=<?php echo $request['id']; ?>"
                                   onclick="return confirm('Are you sure you want to delete <?php echo $request['title']; ?>?');">
                                    Delete
                                </a>
                            <?php endif; ?>
                            <?php if ($request['request_status_id'] != RequestStatus::Closed): ?>
                                <a href="/request.php?action=close&request_id=<?php echo $request['id']; ?>">
                                    Close
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" style="text-align: center;">
                        No Requests
                    </td>
                </tr>
            <?php endif; ?>
        </table>
    </form>
<?php if (count($associatedRequests) > 0): ?>
    <h3>Requests <?php echo $character['character_name']; ?> is linked to</h3>
    <table>
        <tr>
            <th>
                Character
            </th>
            <th>
                Request
            </th>
            <th>

            </th>
        </tr>
        <?php foreach ($associatedRequests as $request): ?>
            <tr>
                <td>
                    <?php echo $request['character_name']; ?>
                </td>
                <td>
                    <?php echo $request['title']; ?>
                </td>
                <td>
                    <a href="/request.php?action=view&request_id=<?php echo $request['request_id']; ?>&character_id=<?php echo $characterId; ?>">
                        <img src="/img/rs_view.png" title="View" alt="View"/>
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>
    <script>
        $(function () {
            $(".button")
                .button();
        });
    </script>
<?php
$page_content = ob_get_clean();
