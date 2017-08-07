<?php
/* @var array $userdata */
use classes\core\helpers\Request;
use classes\core\helpers\Response;
use classes\core\helpers\UserdataHelper;
use classes\log\CharacterLog;
use classes\log\data\ActionType;
use classes\request\data\RequestStatus;
use classes\request\repository\RequestCharacterRepository;
use classes\request\repository\RequestNoteRepository;
use classes\request\repository\RequestRepository;
use classes\request\repository\RequestStatusRepository;
use classes\request\repository\RequestTypeRepository;

$requestId = Request::getValue('request_id', 0);
$requestRepository = new RequestRepository();
$request = $requestRepository->FindById($requestId);

if($request === null)
{
    Response::redirect('/', 'Unable to find that request');
}

CharacterLog::LogAction($request['character_id'], ActionType::ViewRequest, 'View Request', $userdata['user_id'], $requestId);
if($request['request_status_id'] == RequestStatus::Submitted) {
    $requestRepository->UpdateStatus($requestId, RequestStatus::InProgress, $userdata['user_id']);
}

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

$page_title = 'Request: ' . $request['title'];
$contentHeader = $page_title;

ob_start();
?>

<?php if(!Request::isAjax()): ?>
    <a href="/request.php?action=st_list" class="button">Back</a>
    <a href="/request.php?action=st_add_note&request_id=<?php echo $request['id']; ?>" class="button">Add Note</a>
    <?php if(in_array($request['request_status_id'], RequestStatus::$Storyteller)): ?>
    <a href="/request.php?action=st_approve&request_id=<?php echo $request['id']; ?>" class="button">Approve</a>
    <a href="/request.php?action=st_forward&request_id=<?php echo $request['id']; ?>" class="button">Forward</a>
    <a href="/request.php?action=st_deny&request_id=<?php echo $request['id']; ?>" class="button">Deny</a>
    <a href="/request.php?action=st_return&request_id=<?php echo $request['id']; ?>" class="button">Return to Player</a>
    <?php endif; ?>
    <?php if(UserdataHelper::IsAdmin($userdata)): ?>
        <a href="/request.php?action=st_close&request_id=<?php echo $request['id']; ?>" class="button">Close Request</a>
    <?php endif; ?>
 <?php endif; ?>
    <dl>
        <dt>
            User
        </dt>
        <dd>
            <?php echo $request['username']; ?>
        </dd>
        <dt>
            Group:
        </dt>
        <dd>
            <?php echo $request['group_name']; ?>
        </dd>
        <dt>
            Title:
        </dt>
        <dd>
            <?php echo $request['title']; ?>
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
            Created On
        </dt>
        <dd>
            <?php echo $request['created_on']; ?>
        </dd>
        <dt>
            Updated On
        </dt>
        <dd>
            <?php echo $request['updated_on']; ?>
        </dd>
        <dt>
            Request:
        </dt>
        <dd>
            <div class="tinymce-content">
                <?php echo $request['body']; ?>
            </div>
        </dd>
    </dl>

<?php if (count($requestCharacters) > 0): ?>
    <h3>Attached Characters</h3>
    <dl>
        <?php foreach ($requestCharacters as $character): ?>
            <dt>
                <a href="/characters/stView/<?php echo $character->Character->Id; ?>" target="_blank"><?php echo $character->Character->CharacterName; ?></a>
                - Primary :
                <?php echo ($character->IsPrimary) ? 'Yes' : 'No'; ?>
            </dt>
        <?php endforeach; ?>
    </dl>
    <br />
<?php endif; ?>


<?php if (count($supportingRolls) > 0): ?>
    <h3>Supporting Rolls</h3>
    <ul class="wicked">
        <?php foreach ($supportingRolls as $supportingRoll): ?>
            <li>
                <?php echo $supportingRoll['Description']; ?>
                <a href="/dieroller.php?action=view_roll&r=<?php echo $supportingRoll['Roll_ID']; ?>" class="ajax-link"><?php echo $supportingRoll['Num_of_Successes']; ?> Successes</a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<?php if (count($supportingRequests) > 0): ?>
    <h3>Supporting Requests</h3>
    <ul class="wicked">
        <?php foreach ($supportingRequests as $supportingRequest): ?>
            <li>
                <a href="/request.php?action=st_view&request_id=<?php echo $supportingRequest['id']; ?>" class="ajax-link"><?php echo $supportingRequest['title']; ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<?php if (count($supportingBluebooks) > 0): ?>
    <h3>Supporting Bluebook Entries</h3>
    <ul class="wicked">
        <?php foreach ($supportingBluebooks as $supportingBluebook): ?>
            <li>
                <a href="/bluebook.php?action=st_view&bluebook_id=<?php echo $supportingBluebook['id']; ?>" class="ajax-link"><?php echo $supportingBluebook['title']; ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

    <h3>Notes</h3>
<?php if (count($requestNotes) > 0): ?>
    <dl>
        <?php foreach ($requestNotes as $note): ?>
            <dt>
                <?php echo $note['username']; ?>                wrote on
                <?php echo date('m/d/Y H:i:s', strtotime($note['created_on'])); ?>
            </dt>
            <dd>
                <div class="tinymce-content">
                    <?php echo $note['note']; ?>
                </div>
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
        $(function() {
            $(".button")
                .button();
            $(".ajax-link").click(function(e) {
                var url = $(this).attr('href');
                $("#modal-subview")
                    .load(
                    url,
                    null,
                    function() {
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
