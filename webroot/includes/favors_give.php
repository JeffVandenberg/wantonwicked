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

$characterId = Request::getValue('character_id');

$characterRepository = RepositoryManager::getRepository('classes\character\data\Character');
/* @var CharacterRepository $characterRepository */

if (!$characterRepository->MayViewCharacter($characterId, $userdata['user_id'])) {
    SessionHelper::setFlashMessage('Not a valid character to view!');
    Response::redirect('');
}

$page_title = "Give Favor";
$favorType = 0;
$targetCharacter = Request::getValue('targetCharacter');
$favorDescription = Request::getValue('favorDescription');
$favorNotes = Request::getValue('favorNotes');

if (Request::isPost()) {
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

    $createFavorResult = Database::getInstance()->query($createFavorQuery)->execute(
        array(
            $characterId,
            $targetCharacterId,
            $favorTypeId,
            $description,
            $notes,
            $now
        )
    );

    SessionHelper::setFlashMessage('Favor has been created');
    Response::redirect('favors.php?action=list&character_id='.$characterId);
}


$favorTypeQuery = "SELECT * FROM favor_types";

$ids = $names = "";
$favorTypes = array();
foreach(Database::getInstance()->query($favorTypeQuery)->all() as $favorTypeDetail) {
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
            <?php echo FormHelper::select($favorTypes, 'favorTypeId', $favorType); ?>
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
            <button class="button" type="submit" name="formSubmit" id="formSubmit" value="Grant Favor">Grant Favor</button>
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
                serviceUrl: '/character.php?action=search',
                minChars: 2,
                autoSelectFirst: true,
                preserveInput: true,
                params: {},
                onSearchStart: function (query) {
                    query.city = 'Portland';
                    query.only_sanctioned = 1;
                },
                onSelect: function (item) {
                    $("#targetCharacterId").val(item.data);
                    $("#targetCharacter").val(item.value);
                    return false;
                }
            });
        });
    </script>
<?php
$page_content = ob_get_clean();
