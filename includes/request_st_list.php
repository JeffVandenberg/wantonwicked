<?php
/* @var array $userdata */
use classes\core\helpers\FormHelper;
use classes\core\helpers\Pagination;
use classes\core\helpers\Request;
use classes\core\repository\StorytellerRepository;
use classes\request\repository\RequestRepository;
use classes\request\repository\RequestStatusRepository;
use classes\request\repository\RequestTypeRepository;

$contentHeader = $page_title = "Pending Requests";

$characterId = Request::GetValue('character_id', 0);
$statusId = Request::GetValue('status_id', 0);
$page = Request::GetValue('page', 1);
$pageSize = Request::GetValue('page_size', 20);
$sort = Request::GetValue('sort', 'updated_on DESC');
$filter = Request::GetValue('filter', array('character_name' => '', 'title' => '', 'request_type_id' => 0, 'request_status_id' => 0));

$pagination = new Pagination();
$pagination->SetSort($sort);

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

if($page > 1) {
    $hasPrev = true;
}
if(($count / $pageSize) > $page) {
    $hasNext = true;
}

$requestStatusRepository = new RequestStatusRepository();
$requestTypeRepository = new RequestTypeRepository();
$requestTypes = $requestTypeRepository->SimpleListAll();
$requestTypes = array('All') + $requestTypes;
$requestStatuses = $requestStatusRepository->SimpleListAll();
$requestStatuses = array('All') + $requestStatuses;


ob_start();
?>

    <table>
        <tr>
            <th>
                <?php if($hasPrev): ?>
                    <a href="request.php?action=st_list&page=<?php echo $page-1; ?>&sort=<?php echo $pagination->GetSort(); ?>">&lt; &lt;</a>
                <?php else: ?>
                    &lt; &lt;
                <?php endif; ?>
                <form method="get" style="display: inline;" action="request.php">
                    Page:
                    <?php echo FormHelper::Hidden('sort', $sort); ?>
                    <?php echo FormHelper::Hidden('action', 'st_list'); ?>
                    <?php echo FormHelper::Text('page', $page, array('style' => 'width: 30px;')); ?>
                </form>
                <?php if($hasNext): ?>
                    <a href="request.php?action=st_list&page=<?php echo $page+1; ?>&sort=<?php echo $pagination->GetSort(); ?>">&gt; &gt;</a>
                <?php else: ?>
                    &gt; &gt;
                <?php endif; ?>
                -
                Viewing Records (<?php echo (($page-1)*$pageSize) + 1; ?> to <?php echo min($page * $pageSize, $count); ?>)
                Total Records: <?php echo $count; ?>
            </th>
        </tr>
    </table>
<form method="get" action="request.php">
    <table>
        <tr>
            <th>
                <a href="request.php?action=st_list&page=<?php echo $page; ?>&sort=<?php echo $pagination->SortColumn('C.character_name'); ?>">Character</a>
            </th>
            <!--<th>
                <a href="request.php?action=st_list&page=<?php echo $page; ?>&sort=<?php echo $pagination->SortColumn('G.name'); ?>">Group</a>
            </th>-->
            <th>
                <a href="request.php?action=st_list&page=<?php echo $page; ?>&sort=<?php echo $pagination->SortColumn('R.title'); ?>">Name</a>
            </th>
            <th>
                <a href="request.php?action=st_list&page=<?php echo $page; ?>&sort=<?php echo $pagination->SortColumn('RT.name'); ?>">Type</a>
            </th>
            <th>
                <a href="request.php?action=st_list&page=<?php echo $page; ?>&sort=<?php echo $pagination->SortColumn('RS.name'); ?>">Status</a>
            </th>
            <th>
                <a href="request.php?action=st_list&page=<?php echo $page; ?>&sort=<?php echo $pagination->SortColumn('R.created_on'); ?>">Created</a>
            </th>
            <th>
                <a href="request.php?action=st_list&page=<?php echo $page; ?>&sort=<?php echo $pagination->SortColumn('UB.username'); ?>">Updated By</a>
            </th>
            <th>
                <a href="request.php?action=st_list&page=<?php echo $page; ?>&sort=<?php echo $pagination->SortColumn('R.updated_on'); ?>">Status</a>
            </th>
            <th>

            </th>
        </tr>

        <tr>
            <td>
                <?php echo FormHelper::Text('filter[character_name]', $filter['character_name']); ?>
            </td>
            <!--<td>

            </td>-->
            <td>
                <?php echo FormHelper::Text('filter[title]', $filter['title']); ?>
            </td>
            <td>
                <?php echo FormHelper::Select($requestTypes, 'filter[request_type_id]', $filter['request_type_id']); ?>
            </td>
            <td>
                <?php echo FormHelper::Select($requestStatuses, 'filter[request_status_id]', $filter['request_status_id']); ?>
            </td>
            <td>

            </td>
            <td>

            </td>
            <td>

            </td>
            <td>
                <?php echo FormHelper::Hidden('action', 'st_list'); ?>
                <?php echo FormHelper::Button('page_action', 'Filter'); ?>
            </td>
        </tr>
        <?php if (count($requests) > 0): ?>
            <?php foreach ($requests as $request): ?>
                <tr>
                    <td>
                        <a href="view_sheet.php?action=st_view_xp&view_character_id=<?php echo $request['character_id']; ?>" target="_blank">
                            <?php echo $request['character_name']; ?>
                        </a>
                    </td>
                    <!--<td>
                        <?php echo $request['group_name']; ?>
                    </td>-->
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
                        <a href="request.php?action=st_view&request_id=<?php echo $request['id']; ?>">View</a>
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
</form>
    <script>
        $(function () {
            $(".button")
                .button();
        });
    </script>
<?php
$page_content = ob_get_clean();