<?php
use classes\character\data\Character;
use classes\character\helper\CharacterSheetHelper;
use classes\character\repository\CharacterRepository;
use classes\core\helpers\FormHelper;
use classes\core\helpers\MenuHelper;
use classes\core\helpers\Request;
use classes\core\helpers\SessionHelper;
use classes\log\CharacterLog;
use classes\log\data\ActionType;
/* @var array $userdata */

$page_title = "ST View";
$page_content = "";
$js = "";
$lookup_form = "";
$npc_login_link = "";
$sheet = "";
$showNpcLoginLink = false;
$character = false;
$characterSheet = "";

$characterRepository = new CharacterRepository();
$characterSheetHelper = new CharacterSheetHelper();

// test if updating
if(Request::isPost()) {
    // get character information
    $oldCharacter = $characterRepository->getById($_POST['character_id']);
    /* @var Character $oldCharacter */
    $powers = $oldCharacter->CharacterPower;

    // determine what type of update
    $viewed_sheet = false;
    $characterSheetHelper->UpdateSt($_POST, $oldCharacter, $userdata);
}

$edit_xp = "false";
$character_id = 0;
$view_character_id = Request::getValue('view_character_id', 0);
$onlySanctioned = Request::getValue('only_sanctioned', SessionHelper::Read('character.search.only_sanctioned', 1));
SessionHelper::Write('character.search.only_sanctioned', $onlySanctioned);

if ($view_character_id || $view_character_name) {
    // get character information
    $character = false;
    if ($view_character_id) {
        $character = $characterRepository->FindById($view_character_id);
    }
    if ($view_character_name) {
        $character = $characterRepository->FindByName($view_character_name);
    }

    if ($character !== false) {
        // found character
        CharacterLog::LogAction($character['id'], ActionType::ViewCharacter, 'ST View', $userdata['user_id']);
        if ($character['is_sanctioned'] == '') {
            $edit_xp = "true";
        }

        if (($character['is_npc'] == 'Y') && ($character['is_sanctioned'] == 'Y')) {
            $showNpcLoginLink = true;
        }

        $characterSheet = $characterSheetHelper->MakeStView($character, $userdata, $character['character_type']);
    }
    else {
        SessionHelper::SetFlashMessage('Unknown Character ID');
    }
}

$storytellerMenu = require_once('helpers/storyteller_menu.php');
$menu = MenuHelper::GenerateMenu($storytellerMenu);
ob_start();
?>

    <div>
        <?php echo $menu; ?>
        <form method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <table style="width: 450px;">
                <tr>
                    <td>
                        <div class="formInput">
                            <?php echo FormHelper::Text('view_character_name', '',
                                                        array(
                                                            'size'      => 20,
                                                            'maxlength' => '35',
                                                            'label'     => 'Character'
                                                        )); ?>
                            <?php echo FormHelper::Hidden('view_character_id', 0); ?>
                            <?php echo FormHelper::Hidden('action', 'st_view_xp'); ?>
                        </div>
                    </td>
                    <td>
                        <div class="formInput">
                            <?php echo FormHelper::Checkbox('only_sanctioned', 1, $onlySanctioned == 1,
                                                            array('label' => 'Only Sanctioned')); ?>
                        </div>
                    </td>
                    <td>
                        <input type="submit" value="View Character">
                    </td>
                </tr>
            </table>
        </form>
    </div>
<?php if ($showNpcLoginLink): ?>
    <div style="text-align: center;">
        <a href="character_interface.php?character_id=<?php echo $character['id']; ?>" target="_blank">Log in
            as <?php echo $character['character_name']; ?></a><br>
        <a href="notes.php?action=character&character_id=<?php echo $character['id']; ?>" target="_blank">View NPC
            Notes</a></div>
    <br>
<?php endif; ?>
<?php if ($character): ?>
    <a href="/bluebook.php?action=st_list&character_id=<?php echo $character['id']; ?>">View Bluebook</a><br/>
    <a href="/character.php?action=log&character_id=<?php echo $character['id']; ?>">View Character Log</a><br/>
    <form name="character_sheet" id="character_sheet" method="post"
          action="<?php echo $_SERVER['PHP_SELF']; ?>?action=st_view_xp">
        <a href="storyteller_index.php?action=profile_lookup&profile_name=<?php echo $character['username']; ?>">View
            all of <?php echo $character['username']; ?>'s characters</a>

        <div id="charSheet">
            <?php echo $characterSheet; ?>
        </div>
    </form>
    <script src="js/create_character_xp.js" type="text/javascript"></script>
    <script>
        $(function () {
            setXpEdit(<?php echo $edit_xp; ?>);
            setPageAction('st_view');
            drawSheet();
        });
    </script>
<?php endif; ?>
    <script>
        $(function () {
            $("#view-character-name").autocomplete({
                autoFocus: true,
                source: function (request, response) {
                    $.ajax({
                        url     : 'character.php?action=search',
                        dataType: 'json',
                        method  : 'post',
                        data    : {
                            term           : request.term,
                            only_sanctioned: $("#only-sanctioned").prop('checked') ? 1 : 0
                        },
                        success : function (data) {
                            response($.map(data, function (item) {
                                return {
                                    label: item.label,
                                    value: item.id
                                }
                            }));
                        }
                    });
                },
                close : function () {
                    if (($("#view-character-name").val() != '') && !(parseInt($("#view-character-id").val()) > 0)) {
                        alert('Select a character from the drop down.')
                    }
                },
                search: function (e) {
                },
                focus : function () {
                    return false;
                },
                select: function (e, ui) {
                    $("#view-character-id").val(ui.item.value);
                    $("#view-character-name").val(ui.item.label);
                    $(this).closest('form').submit();
                    return false;
                }
            });
        });
    </script>
<?php
$page_content = ob_get_clean();