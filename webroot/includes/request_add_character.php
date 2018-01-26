<?php
/* @var array $userdata */

use classes\core\helpers\FormHelper;
use classes\core\helpers\Request;
use classes\core\helpers\Response;
use classes\core\helpers\SessionHelper;
use classes\core\helpers\UserdataHelper;
use classes\core\repository\RepositoryManager;
use classes\request\data\RequestCharacter;
use classes\request\repository\RequestRepository;

$requestId = Request::getValue('request_id', 0);
$requestRepository = new RequestRepository();
if (!UserdataHelper::IsAdmin($userdata) && !$requestRepository->MayViewRequest($requestId, $userdata['user_id'])) {
    Response::redirect('/', 'Unable to view that request');
}

$onlySanctioned = Request::getValue('only_sanctioned', true);
$isPrimary = Request::getValue('is_primary', false);
$characterId = "";
$characterName = "";
if (Request::isPost()) {
    if ($_POST['action'] == 'Cancel') {
        Response::redirect('request.php?action=view&request_id=' . $requestId);
    } elseif ($_POST['action'] == 'Add') {
        $characterId = Request::getValue('character_id', 0);
        $characterName = Request::getValue('character_name');
        $note = Request::getValue('note', '');
        if ($characterId) {
            $requestCharacter = new RequestCharacter();
            $requestCharacter->CharacterId = $characterId;
            $requestCharacter->RequestId = $requestId;
            $requestCharacter->IsPrimary = $isPrimary;
            $requestCharacter->Note = $note;
            $requestCharacter->IsApproved = false;
            $requestCharacterRepo = RepositoryManager::GetRepository(RequestCharacter::class);
            if ($requestCharacterRepo->save($requestCharacter)) {
                $requestRepository->TouchRecord($requestId, $userdata['user_id']);
                SessionHelper::SetFlashMessage('Attached ' . $characterName);
                Response::redirect('request.php?action=view&request_id=' . $requestId);
            } else {
                SessionHelper::SetFlashMessage('Error Attaching Character');
            }

        } else {
            SessionHelper::SetFlashMessage('No Selected Character given.');
        }
    }
}
$request = $requestRepository->FindById($requestId);

$primaryOptions = array();

if ($requestRepository->RequestHasPrimaryCharacter($requestId)) {
    $primaryOptions['disabled'] = 'disabled';
}


$page_title = 'Add Character to: ' . $request['title'];
$contentHeader = $page_title;

ob_start();
?>

    <form method="post">
        <div class="row">
            <div class="small-12 medium-5 column">
                <label>
                    Character
                </label>
                <?php echo FormHelper::Text('character_name', $characterName); ?>
                <?php echo FormHelper::Hidden('character_id', $characterId); ?>
            </div>
            <div class="small-5 medium-2 column">
                <label>
                    Only Sanctioned
                </label>
                <?php echo FormHelper::Checkbox('only_sanctioned', 1, $onlySanctioned); ?>
            </div>
            <div class="small-5 medium-2 column">
                <label>Make Primary</label>
                <?php echo FormHelper::Checkbox('is_primary', 1, $isPrimary, $primaryOptions); ?>
            </div>
            <div class="small-2 medium-3 column">
                <?php echo FormHelper::Hidden('request_id', $requestId); ?>
                <button class="button" type="submit" id="save-button" name="action" value="Add">Add</button>
                <button class="button" type="submit" id="cancel-button" name="action" value="Cancel">Cancel</button>
            </div>
        </div>
    </form>
    <script>
        $(function () {
            $("#save-button").click(function (e) {
                if ($("#character-id").val() == '') {
                    alert('Please select a character.');
                    e.preventDefault();
                }
                if ($("#note").val() == '') {
                    alert('Please enter a note to indicate the character\'s involvement.');
                    e.preventDefault();
                }
            });
            $("#character-name").autocomplete({
                serviceUrl: '/request.php?action=character_search',
                minChars: 2,
                autoSelectFirst: true,
                preserveInput: true,
                params: {},
                onSearchStart: function (query) {
                    query.request_id = $("#request-id").val();
                    query.only_sanctioned = $("#only-sanctioned").prop('checked');
                },
                onSelect: function (item) {
                    if (item.data > 0) {
                        $("#character-id").val(item.data);
                        $("#character-name").val(item.value);
                    } else {
                        $("#character-id").val('');
                        $("#character-name").val('');
                    }
                    return false;
                }
            });
        });
    </script>
<?php
$page_content = ob_get_clean();
