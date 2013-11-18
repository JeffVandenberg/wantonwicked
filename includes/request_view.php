<?php
/* @var array $userdata */

use classes\core\helpers\MenuHelper;
use classes\core\helpers\Request;
use classes\core\helpers\Response;
use classes\core\helpers\SessionHelper;
use classes\request\data\RequestStatus;
use classes\request\repository\RequestCharacterRepository;
use classes\request\repository\RequestNoteRepository;
use classes\request\repository\RequestRepository;
use classes\request\repository\RequestStatusRepository;
use classes\request\repository\RequestTypeRepository;

$requestId = Request::GetValue('request_id', 0);
$requestRepository = new RequestRepository();

if (!$userdata['is_admin'] && !$requestRepository->MayViewRequest($requestId, $userdata['user_id'])) {
    SessionHelper::SetFlashMessage('Unable to view Request');
    Response::Redirect('/');
}

$request = $requestRepository->FindById($requestId);

$requestTypeRepository = new RequestTypeRepository();
$requestType = $requestTypeRepository->FindById($request['request_type_id']);
$requestStatusRepository = new RequestStatusRepository();
$requestStatus = $requestStatusRepository->FindById($request['request_status_id']);
$requestNoteRepository = new RequestNoteRepository();
$requestNotes = $requestNoteRepository->ListByRequestId($requestId);
$requestCharacterRepository = new RequestCharacterRepository();
$requestCharacters = $requestCharacterRepository->ListByRequestId($requestId);

$supportingRequests = $requestRepository->ListSupportingRequests($requestId);
$supportingRolls = $requestRepository->ListSupportingRolls($requestId);
$supportingBluebooks = $requestRepository->ListSupportingBluebookEntries($requestId);

$contentHeader = $page_title = 'Request: ' . $request['title'];

if($request['request_status_id'] == RequestStatus::NewRequest) {
    SessionHelper::SetFlashMessage('This request is not yet submitted to STs.');
}

$characterId = $request['character_id'];
require_once('helpers/character_menu.php');
$characterMenu['Actions'] = array(
    'link' => '#',
    'submenu' => array(
        'Back' => array(
            'link' => 'request.php?action=list&character_id=' . $request['character_id']
        )
    )
);
if($request['request_status_id'] == RequestStatus::NewRequest) {
    $characterMenu['Actions']['submenu']['Edit Request'] = array(
        'link' => 'request.php?action=edit&request_id=' . $requestId
    );
}
if ($request['request_status_id'] != RequestStatus::Closed) {
    $characterMenu['Actions']['submenu']['Close Request'] = array(
        'link' => 'request.php?action=close&request_id=' . $requestId
    );
}
if (in_array($request['request_status_id'], RequestStatus::$PlayerSubmit)) {
    $characterMenu['Actions']['submenu']['Submit Request'] = array(
        'link' => 'request.php?action=submit&request_id=' . $requestId
    );
}
if($request['request_status_id'] == RequestStatus::NewRequest) {
    $characterMenu['Actions']['submenu']['Delete Request'] = array(
        'link' => 'request.php?action=delete&request_id=' . $requestId
    );
}

if(!in_array($request['request_status_id'], RequestStatus::$Terminal)) {
    $characterMenu['Attach'] = array(
        'link' => '#',
        'submenu' => array(
            'New Note' => array(
                'link' => 'request.php?action=add_note&request_id=' . $requestId
            )
        )
    );
    if(in_array($request['request_status_id'], RequestStatus::$PlayerEdit)) {
        $characterMenu['Attach']['submenu']['Character'] = array(
                'link' => 'request.php?action=add_character&request_id=' . $requestId
            );
        $characterMenu['Attach']['submenu']['Request'] = array(
                'link' => 'request.php?action=attach_request&request_id=' . $requestId
            );
        $characterMenu['Attach']['submenu']['Bluebook Entry'] = array(
                'link' => 'request.php?action=attach_bluebook&request_id=' . $requestId
            );
        $characterMenu['Attach']['submenu']['Dice Roll'] = array(
                'link' => 'dieroller.php?action=character&character_id=' . $request['character_id']
            );
    }
}
$menu = MenuHelper::GenerateMenu($characterMenu);
ob_start();
?>
<?php if (!Request::IsAjax()): ?>
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
            Group:
        </dt>
        <dd>
            <?php echo $request['group_name']; ?>
        </dd>
        <dt>
            Request Type:
        </dt>
        <dd>
            <?php echo $requestType['name']; ?>
        </dd>
        <dt>
            Request Status:
        </dt>
        <dd>
            <?php echo $requestStatus['name']; ?>
        </dd>
        <dt>
            Request:
        </dt>
        <dd>
            <?php echo $request['body']; ?>
        </dd>
    </dl>

<?php if (count($supportingRolls) > 0): ?>
    <h3>Supporting Rolls</h3>
    <ul class="wicked">
        <?php foreach ($supportingRolls as $supportingRoll): ?>
            <li>
                <?php echo $supportingRoll['Description']; ?>
                <a href="/dieroller.php?action=view_roll&r=<?php echo $supportingRoll['Roll_ID']; ?>"
                   class="ajax-link"><?php echo $supportingRoll['Num_of_Successes']; ?> Successes</a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<?php if (count($supportingRequests) > 0): ?>
    <h3>Supporting Requests</h3>
    <ul class="wicked">
        <?php foreach ($supportingRequests as $supportingRequest): ?>
            <li>
                <a href="/request.php?action=view&request_id=<?php echo $supportingRequest['id']; ?>"
                   class="ajax-link"><?php echo $supportingRequest['title']; ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<?php if (count($supportingBluebooks) > 0): ?>
    <h3>Supporting Bluebook Entries</h3>
    <ul class="wicked">
        <?php foreach ($supportingBluebooks as $supportingBluebook): ?>
            <li>
                <a href="/bluebook.php?action=view&bluebook_id=<?php echo $supportingBluebook['id']; ?>"
                   class="ajax-link"><?php echo $supportingBluebook['title']; ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<?php if (count($requestCharacters) > 0): ?>
    <h3>Assisting Characters</h3>
    <dl>
        <?php foreach ($requestCharacters as $character): ?>
            <dt>
                <?php echo $character['character_name']; ?>
                - Approved:
                <?php echo ($character['is_approved']) ? 'Yes' : 'No'; ?>
            </dt>
            <dd>
                <?php echo $character['note']; ?>
            </dd>
        <?php endforeach; ?>
    </dl>
<?php endif; ?>


    <h3>Notes</h3>
<?php if (count($requestNotes) > 0): ?>
    <dl>
        <?php foreach ($requestNotes as $note): ?>
            <dt>
                <?php echo $note['username']; ?>
                wrote on
                <?php echo date('m/d/Y H:i:s', strtotime($note['created_on'])); ?>
            </dt>
            <dd>
                <?php echo $note['note']; ?>
            </dd>
        <?php endforeach; ?>
    </dl>
<?php else: ?>
    <div class="paragraph">
        No Notes for this Request
    </div>
<?php endif; ?>
    <div id="modal-subview" style="display:none;"></div>
    <script>
        $(function () {
            $("#request-menu")
                .menubar();
            $(".button")
                .button();
            $(".ajax-link").click(function (e) {
                var url = $(this).attr('href');
                $("#modal-subview")
                    .load(
                    url,
                    null,
                    function () {
                        $(this)
                            .dialog({
                                modal: true,
                                height: 600,
                                width: 800
                            });
                    }
                );
                e.preventDefault();
            });
        });
    </script>
<?php
$page_content = ob_get_clean();