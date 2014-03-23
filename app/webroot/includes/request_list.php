<?php
/* @var array $userdata */
use classes\character\repository\CharacterRepository;
use classes\core\helpers\FormHelper;
use classes\core\helpers\MenuHelper;
use classes\core\helpers\Pagination;
use classes\core\helpers\Request;
use classes\request\data\RequestStatus;
use classes\request\repository\RequestRepository;
use classes\request\repository\RequestStatusRepository;
use classes\request\repository\RequestTypeRepository;

$characterId = Request::GetValue('character_id', 0);
$statusId = Request::GetValue('status_id', 0);
$page = Request::GetValue('page', 1);
$pageSize = Request::GetValue('page_size', 10);
$sort = Request::GetValue('sort', 'updated_on DESC');
$filter = Request::GetValue('filter', array('title' => '', 'request_type_id' => 0, 'request_status_id' => 0));

$characterRepository = new CharacterRepository();
if (!$userdata['is_admin'] && !$characterRepository->MayViewCharacter($characterId, $userdata['user_id'])) {
    include 'index_redirect.php';
    die();
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
$requestTypes = $requestTypeRepository->SimpleListAll();
$requestTypes = array('All') + $requestTypes;
$requestStatuses = $requestStatusRepository->SimpleListAll();
$requestStatuses = array('All') + $requestStatuses;

$contentHeader = $page_title = "Requests for " . $character['character_name'];

require_once('helpers/character_menu.php');
$characterMenu['Help'] = array(
    'link' => '#',
    'submenu' => array(
        'Request System Help' => array(
            'link' => '/wiki/index.php?n=GameRef.GameInterfaceHelp'
        )
    )
);
$characterMenu['Create Request'] = array(
    'link' => "request.php?action=create&character_id=$characterId"
);
$menu = MenuHelper::GenerateMenu($characterMenu);

ob_start();
?>
<?php echo $menu; ?>
    <table>
        <tr>
            <th>
                <?php if ($hasPrev): ?>
                    <a href="/request.php?action=list&<?php echo $pagination->GetPrev(); ?>">
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
                    <?php echo FormHelper::Text('page', $page, array('style' => 'width: 30px;')); ?>
                </form>
                <?php if ($hasNext): ?>
                    <a href="/request.php?action=list&<?php echo $pagination->GetNext(); ?>">
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
                    <?php echo FormHelper::Button('page_action', 'Filter Requests'); ?>
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
                                <img src="/img/rs_view.png" title="View" alt="View"/>
                            </a>
                            <a href="/request.php?action=add_note&request_id=<?php echo $request['id']; ?>">
                                <img src="/img/rs_addnote.png" title="Add Note" alt="Add Note"/>
                            </a>
                            <?php if ($request['request_status_id'] == RequestStatus::NewRequest): ?>
                                <a href="/request.php?action=edit&request_id=<?php echo $request['id']; ?>">
                                    <img src="/img/rs_edit.png" title="Edit" alt="Edit"/>
                                </a>
                                <a href="/request.php?action=delete&request_id=<?php echo $request['id']; ?>"
                                   onclick="return confirm('Are you sure you want to delete <?php echo $request['title']; ?>?');">
                                    <img src="/img/rs_delete.png" Title="Delete" alt="Delete"/>
                                </a>
                            <?php endif; ?>
                            <?php if ($request['request_status_id'] != RequestStatus::Closed): ?>
                                <a href="/request.php?action=close&request_id=<?php echo $request['id']; ?>">
                                    <img src="/img/rs_new_close.png" title="Close" alt="Close"/>
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
    <h3>Requests to Approve</h3>
    <table>
        <tr>
            <th>
                Character
            </th>
            <th>
                Request
            </th>
            <th>
                Note
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
                    <?php echo $request['note']; ?>
                </td>
                <td>
                    <a href="/request.php?action=view&request_id=<?php echo $request['request_id']; ?>&character_id=<?php echo $characterId; ?>">
                        <img src="/img/rs_view.png" title="View" alt="View"/>
                    </a>
                    <?php if ($request['is_approved']): ?>
                        <a href="/request.php?action=update_request_character&is_approved=0&request_character_id=<?php echo $request['request_character_id']; ?>&character_id=<?php echo $characterId; ?>">Deny</a>
                    <?php else: ?>
                        <a href="/request.php?action=update_request_character&is_approved=1&request_character_id=<?php echo $request['request_character_id']; ?>&character_id=<?php echo $characterId; ?>">Approve</a>
                    <?php endif; ?>
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