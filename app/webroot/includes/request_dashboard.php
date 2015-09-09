<?php
use classes\core\helpers\Request;
use classes\request\repository\RequestRepository;
/** @var array $userdata */

$page_title = 'Request Dashboard';
$contentHeader = $page_title;

$page = Request::getValue('page', 1);
$pageSize = Request::getValue('page_size', 25);

$filter = array();
// requests sent out that are still open
$requestRepository = new RequestRepository();
$userRequests = $requestRepository->ListByUserId($userdata['user_id'], $page, $pageSize, 'updated_on DESC', 0, $filter);

// requests player has characters attached to
$characterLinkedRequests = $requestRepository->ListRequestsLinkedByCharacterForUser($userdata['user_id']);

// requests sent to the users groups

$mainMenu['Actions']['submenu']['New Request'] = array(
    'link' => '/request.php?action=create'
);

ob_start();
?>
<h2>Your Outgoing Requests</h2>
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
    <?php foreach($userRequests as $request): ?>
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
                <?php foreach($request->RequestCharacter as $rc): ?>
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
    <?php foreach($characterLinkedRequests as $request): ?>
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
                <?php foreach($request->RequestCharacter as $rc): ?>
                    <?php $characterList[] = $rc->Character->CharacterName; ?>
                <?php endforeach; ?>
                <?php echo implode(', ', $characterList); ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
<?php
$page_content = ob_get_clean();