<?php
use classes\core\helpers\FormHelper;
use classes\core\helpers\Pagination;
use classes\core\helpers\Request;
use classes\core\helpers\UserdataHelper;
use classes\request\repository\RequestRepository;

/** @var array $userdata */

$page_title = 'Request Dashboard';
$contentHeader = $page_title;

$page = Request::getValue('page', 1);
$pageSize = Request::getValue('page_size', 25);
$sort = Request::getValue('sort', 'updated_on DESC');

$filter = array();
$pagination = new Pagination();
$pagination->SetSort($sort);
$pagination->SetParameters(array(
    'page' => $page,
    'sort' => $sort,
    'filter' => $filter
));

// requests sent out that are still open
$requestRepository = new RequestRepository();
$userRequests = $requestRepository->ListByUserId($userdata['user_id'], $page, $pageSize, 'updated_on DESC', $filter);
$count = $requestRepository->countByUserId($userdata['user_id'], $filter);

$hasPrev = false;
$hasNext = false;

if ($page > 1) {
    $hasPrev = true;
}
if (($count / $pageSize) > $page) {
    $hasNext = true;
}

// requests player has characters attached to
$characterLinkedRequests = $requestRepository->ListRequestsLinkedByCharacterForUser($userdata['user_id']);

// requests sent to the users groups

$mainMenu['Actions']['submenu']['New Request'] = array(
    'link' => '/request.php?action=create'
);


if(UserdataHelper::mayManageRequests($userdata)) {
    $mainMenu['Actions']['submenu']['Manage Requests'] = [
        'link' => '/request.php?action=st_list'
    ];
}

ob_start();
?>
    <h2>Your Open Requests</h2>
    <table>
        <tr>
            <th>
                <?php if ($hasPrev): ?>
                    <a href="/request.php?action=dashboard&<?php echo $pagination->GetPrev(); ?>" title="Previous Page">&lt; &lt;</a>
                <?php else: ?>
                    &lt; &lt;
                <?php endif; ?>
                <form method="get" style="display: inline;" action="/request.php">
                    Page:
                    <?php echo FormHelper::Hidden('sort', $sort); ?>
                    <?php echo FormHelper::Hidden('action', 'dashboard'); ?>
                    <?php echo FormHelper::Text('page', $page, array('style' => 'width: 30px;display:inline;')); ?>
                </form>
                <?php if ($hasNext): ?>
                    <a href="/request.php?action=dashboard&<?php echo $pagination->GetNext(); ?>" title="Next">&gt; &gt;</a>
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
        <thead>
        <tr>
            <th>
                Request Title
            </th>
            <th>
                Type
            </th>
            <th>
                Status
            </th>
            <th>
                Created On
            </th>
            <th>
                Updated By
            </th>
            <th>
                Updated On
            </th>
            <th>
                Character
            </th>
        </tr>
        </thead>
        <?php foreach ($userRequests as $request): ?>
            <tr>
                <td>
                    <a href="/request.php?action=view&request_id=<?php echo $request->Id; ?>"><?php echo $request->Title; ?></a>
                </td>
                <td>
                    <?php echo $request->RequestType->Name; ?>
                </td>
                <td>
                    <?php echo $request->RequestStatus->Name; ?>
                </td>
                <td>
                    <?php echo $request->CreatedOn; ?>
                </td>
                <td>
                    <?php echo $request->UpdatedBy->Username; ?>
                </td>
                <td>
                    <?php echo $request->UpdatedOn; ?>
                </td>
                <td>
                    <?php $characterList = array(); ?>
                    <?php foreach ($request->RequestCharacter as $rc): ?>
                        <?php $characterList[] = $rc->Character->CharacterName; ?>
                    <?php endforeach; ?>
                    <?php echo implode(', ', $characterList); ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <h2>Requests Your characters are linked to</h2>
    <table>
        <thead>
        <tr>
            <th>
                Request Title
            </th>
            <th>
                Type
            </th>
            <th>
                Status
            </th>
            <th>
                Created On
            </th>
            <th>
                Updated By
            </th>
            <th>
                Updated On
            </th>
            <th>
                Character
            </th>
        </tr>
        </thead>
        <?php foreach ($characterLinkedRequests as $request): ?>
            <tr>
                <td>
                    <a href="/request.php?action=view&request_id=<?php echo $request->Id; ?>"><?php echo $request->Title; ?></a>
                </td>
                <td>
                    <?php echo $request->RequestType->Name; ?>
                </td>
                <td>
                    <?php echo $request->RequestStatus->Name; ?>
                </td>
                <td>
                    <?php echo $request->CreatedOn; ?>
                </td>
                <td>
                    <?php echo $request->UpdatedBy->Username; ?>
                </td>
                <td>
                    <?php echo $request->UpdatedOn; ?>
                </td>
                <td>
                    <?php $characterList = array(); ?>
                    <?php foreach ($request->RequestCharacter as $rc): ?>
                        <?php $characterList[] = $rc->Character->CharacterName; ?>
                    <?php endforeach; ?>
                    <?php echo implode(', ', $characterList); ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php
$page_content = ob_get_clean();
