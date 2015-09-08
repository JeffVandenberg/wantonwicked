<?php
/* @var array $userdata */

// get character id
use classes\character\repository\CharacterRepository;
use classes\core\helpers\FormHelper;
use classes\core\helpers\Request;
use classes\core\helpers\Response;
use classes\core\helpers\SessionHelper;
use classes\core\repository\Database;
use classes\core\repository\RepositoryManager;

$characterId = Request::GetValue('character_id');

$characterRepository = RepositoryManager::GetRepository('classes\character\data\Character');
/* @var CharacterRepository $characterRepository */

if (!$characterRepository->MayViewCharacter($characterId, $userdata['user_id'])) {
    SessionHelper::SetFlashMessage('Not a valid character to view!');
    Response::Redirect('');
}

$page_title = "Give Favor";
$favorType = 0;
$targetCharacter = Request::GetValue('targetCharacter');
$favorDescription = Request::GetValue('favorDescription');
$favorNotes = Request::GetValue('favorNotes');

if (Request::IsPost()) {
    // attempt to save favor
    $favorTypeId       = $_POST['favorTypeId'] + 0;
    $targetCharacterId = $_POST['targetCharacterId'] + 0;
    $description       = htmlspecialchars($_POST['favorDescription']);
    $notes             = htmlspecialchars($_POST['favorNotes']);
    $now               = date('Y-m-d h:i:s');

    $createFavorQuery = <<<EOQ
INSERT INTO
	favors
	(
		source_id,
		source_type_id,
		target_id,
		target_type_id,
		favor_type_id,
		description,
		notes,
		date_given
	)
VALUES
	(
		?,
		1,
		?,
		1,
		?,
		?,
		?,
		?
	)
EOQ;

    $createFavorResult = Database::GetInstance()->Query($createFavorQuery)->Execute(
        array(
            $characterId,
            $targetCharacterId,
            $favorTypeId,
            $description,
            $notes,
            $now
        )
    );

    SessionHelper::SetFlashMessage('Favor has been created');
    Response::Redirect('favors.php?action=list&character_id='.$characterId);
}


$favorTypeQuery = "SELECT * FROM favor_types";

$ids = $names = "";
$favorTypes = array();
foreach(Database::GetInstance()->Query($favorTypeQuery)->All() as $favorTypeDetail) {
    $favorTypes[$favorTypeDetail['id']] = $favorTypeDetail['name'];
}

ob_start();
?>
    <h2>Give Favor to another Character/Group</h2>

    <form id="giveFavorForm" method="post">
        <div class="formInput">
            <label for="targetCharacter">Give Favor to:</label>
            <input type="hidden" name="targetCharacterId" id="targetCharacterId" value=""/>
            <input type="text" name="targetCharacter" id="targetCharacter" value="<?php echo $targetCharacter; ?>"><br/>
        </div>
        <div class="formInput">
            <label>Favor Type:</label>
            <?php echo FormHelper::Select($favorTypes, 'favorTypeId', $favorType); ?>
        </div>
        <div class="formInput">
            <label for="favorDescription">Favor Description:</label>
            <input type="text" name="favorDescription" id="favorDescription" value="<?php echo $favorDescription; ?>"><br/>
        </div>
        <div class="formInput">
            <label for="favorNotes">Favor Notes:</label>
            <textarea name="favorNotes" id="favorNotes" rows="5" cols="50"><?php echo $favorNotes; ?></textarea>
        </div>
        <div class="formInput">
            <input type="hidden" name="sourceCharacterId" value="<?php echo $characterId; ?>"/>
            <input type="submit" name="formSubmit" id="formSubmit" value="Grant Favor"/>
        </div>
    </form>
    <script language="javascript">
        $(document).ready(function () {
            $('input:text').keypress(function (e) {
                return e.keyCode != 13;

            });
            $('#giveFavorForm').submit(function () {
                var errors = '';
                if ($.trim($('#targetCharacterId').val()) == '') {
                    errors += " - Select a character to give the favor to.\\r\\n";
                }
                if ($.trim($('#favorDescription').val()) == '') {
                    errors += ' - Provide a brief description of the favor being given.\\r\\n';
                }

                if (errors != '') {
                    alert('Please correct the following errors: \\r\\n' + errors);
                    return false;
                }
                return true;
            });
            $('#targetCharacter').autocomplete({
                source   : function (request, response) {
                    $.ajax({
                        url     : "/characters.php?action=quick_search",
                        type    : "post",
                        dataType: "json",
                        data    : {
                            term      : request.term,
                            maxResults: 20
                        },
                        success : function (data) {
                            response($.map(data, function (item) {
                                return {
                                    name : item.characterName,
                                    value: item.characterName,
                                    id   : item.id
                                }
                            }))
                        }
                    });
                },
                minLength: 2,
                select   : function (event, ui) {
                    $('#targetCharacterId').val(ui.item.id);
                }
            });
        });
    </script>
<?php
$page_content = ob_get_clean();
