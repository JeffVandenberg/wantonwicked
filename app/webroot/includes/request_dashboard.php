<?php
use classes\request\repository\RequestRepository;
/** @var array $userdata */

$page_title = 'Request Dashboard';
$contentHeader = $page_title;

$filter = array();
// requests sent out that are still open
$requestRepository = new RequestRepository();
$userRequests = $requestRepository->ListByUserId($userdata['user_id'], 1, 25, 'updated_on', 0, $filter);

// requests sent to the user

// requests sent to the users groups

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
                <?php foreach($request->RequestCharacter as $rc): ?>
                    <?php echo $rc->Character->CharacterName; ?>
                <?php endforeach; ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
<?php
$page_content = ob_get_clean();