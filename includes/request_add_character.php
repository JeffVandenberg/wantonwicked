<?php
/* @var array $userdata */
use classes\core\helpers\FormHelper;
use classes\core\helpers\Request;
use classes\core\helpers\Response;
use classes\core\helpers\SessionHelper;
use classes\request\repository\RequestRepository;

$requestId = Request::GetValue('request_id', 0);
$requestRepository = new RequestRepository();
if (!$requestRepository->MayViewRequest($requestId, $userdata['user_id'])) {
    include 'index_redirect.php';
    die();
}

$note = "";
$characterId = "";
$characterName = "";
if (Request::IsPost()) {
    if ($_POST['action'] == 'Cancel') {
        Response::Redirect('request.php?action=view&request_id=' . $requestId);
    }
    elseif ($_POST['action'] == 'Add Character') {
        $characterId = Request::GetValue('character_id', 0);
        $characterName = Request::GetValue('character_name');
        $note = htmlspecialchars(Request::GetValue('note'));
        if($requestRepository->AddCharacter($requestId, $characterId, $note)) {
            $requestRepository->TouchRecord($requestId, $userdata['user_id']);
            SessionHelper::SetFlashMessage('Attached Character');
            Response::Redirect('request.php?action=view&request_id=' . $requestId);
        }
        else {
            SessionHelper::SetFlashMessage('Error Attaching Character');
        }
    }
}
$request = $requestRepository->FindById($requestId);

$page_title = 'Add Character to: ' . $request['title'];
$contentHeader = $page_title;

ob_start();
?>

    <form method="post">
        <table>
            <tr>
                <td>
                    <div class="formInput">
                        <label>
                            Character
                        </label>
                        <?php echo FormHelper::Text('character_name', $characterName); ?>
                        <?php echo FormHelper::Hidden('character_id', $characterId); ?>
                    </div>
                </td>
                <td>
                    <div class="formInput">
                        <label>
                            Only Sanctioned Characters
                        </label>
                        <?php echo FormHelper::Checkbox('only_sanctioned', 1, true); ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <div class="formInput">
                        <label>
                            Note
                        </label>
                        <?php echo FormHelper::Text('note', $note); ?>
                    </div>
                </td>
            </tr>
        </table>
        <div class="formInput">
            <?php echo FormHelper::Hidden('request_id', $requestId); ?>
            <?php echo FormHelper::Button('action', 'Add Character', 'submit', array('id' => 'save-button')); ?>
            <?php echo FormHelper::Button('action', 'Cancel', 'submit', array('id' => 'cancel-button')); ?>
        </div>
    </form>
    <script>
        $(function () {
            $("#save-button").click(function(e) {
                if($("#character-id").val() == '') {
                    alert('Please select a character.');
                    e.preventDefault();
                }
                if($("#note").val() == '') {
                    alert('Please enter a note to indicate the character\'s involvement.');
                    e.preventDefault();
                }
            });
            $("#character-name").autocomplete({
                source: function(request, response) {
                    $.ajax({
                        url: 'request.php?action=character_search',
                        dataType: 'json',
                        method: 'post',
                        data: {
                            term: request.term,
                            request_id: $("#request-id").val(),
                            only_sanctioned: $("#only-sanctioned").prop('checked')
                        },
                        success: function(data) {
                            response( $.map( data, function( item ) {
                                return {
                                    label: item.label,
                                    value: item.id
                                }
                            }));
                        }
                    });
                },
                close: function() {
                    if(!(parseInt($("#character-id").val()) > 0)) {
                        alert('Select a character from the drop down.')
                    }
                },
                search: function(e) {
                },
                focus: function () {
                    return false;
                },
                select: function (e, ui) {
                    $("#character-id").val(ui.item.value);
                    $("#character-name").val(ui.item.label);
                    return false;
                }
            });
        });
    </script>
<?php
$page_content = ob_get_clean();