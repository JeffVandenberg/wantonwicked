<?php
/* @var array $userdata */
use classes\character\helper\CharacterSheetHelper;
use classes\character\sheet\WodSheet;
use classes\core\helpers\Request;
use classes\core\helpers\Response;
use classes\core\helpers\SessionHelper;

$contentHeader = $page_title = "Create Character";
$characterType = Request::GetValue('character_type', 'Mortal');
$characterSheetHelper = new CharacterSheetHelper();

if (isset($_POST['character_name'])) {
    $show_form = false;
    if ($userdata['user_id'] != 1) {
        $error = $characterSheetHelper->UpdateNew($_POST);
        if ($error == '') {
            SessionHelper::SetFlashMessage('Added ' . $_POST['character_name'] . ' to your profile');
            Response::Redirect('chat.php');
        }
        else {
            SessionHelper::SetFlashMessage($error);
        }
    }
    else {
        SessionHelper::SetFlashMessage("You're not logged in");
        Response::Redirect('/');
    }
}


// load for an AJAX Style solution
$java_script .= <<<EOQ
EOQ;

$wodSheet = new WodSheet();
$options = array(
    'edit_show_sheet' => true,
    'edit_name' => true,
    'edit_vitals' => true,
    'edit_is_dead' => true,
    'edit_concept' => true,
    'edit_description' => true,
    'edit_equipment' => true,
    'edit_public_effects' => true,
    'edit_group' => true,
    'edit_is_npc' => true,
    'edit_attributes' => true,
    'edit_skills' => true,
    'edit_perm_traits' => true,
    'edit_temp_traits' => true,
    'edit_powers' => true,
    'edit_history' => true,
    'edit_goals' => true,
    'edit_experience' => true,
    'show_st_notes' => false,
    'calculate_derived' => true,
    'xp_create_mode' => true,
    'user_type' => 'player'
);

$characterType = Request::GetValue('type', 'Mortal');
$id = Request::GetValue('id', null);
$characterSheet = $wodSheet->buildSheet($characterType, array('id' => $id), $options);
//$characterSheet = $characterSheetHelper->MakeNewView('', $characterType);

ob_start();
?>
    When creating a character, please make sure you have reviewed
    <a href="/wiki/index.php?n=GameRef.CharacterCreation" target="_blank">Character Creation Guidelines</a>.<br>
    <br>
    <br>
    Please make sure you have read over the
    <a href="/wiki/index.php?n=GameRef.CharacterSheetFAQ" target="_blank">Character Sheet FAQ</a>.
    his character sheet is based off of XP Pools rather than dot allocation.
    <br>
    <form name="character_sheet" id="character_sheet" method="post"
          action="<?php echo $_SERVER['PHP_SELF']; ?>?action=create_xp">
        <div id="charSheet">
            <?php echo $characterSheet; ?>
        </div>
    </form>
    <script src="/js/create_character_xp.js" type="text/javascript"></script>
    <script>
        $(function() {
            setXpEdit(true);
            setPageAction('view_own');
            drawSheet();
        });
    </script>
<?php
$page_content .= ob_get_clean();
