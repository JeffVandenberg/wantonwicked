-<?php
/* @var array $userdata */
use classes\character\data\Character;
use classes\character\helper\CharacterSheetHelper;
use classes\character\repository\CharacterRepository;
use classes\core\helpers\MenuHelper;
use classes\core\helpers\Request;
use classes\core\helpers\Response;
use classes\core\helpers\SessionHelper;

$page_title = "Update/View Character";

// page variables
$show_form = true;
$characterRepository = new CharacterRepository();
$characterSheetHelper = new CharacterSheetHelper();

$characterId = Request::GetValue('character_id', 0);
$character = $characterRepository->FindById($characterId);

if($character['user_id'] != $userdata['user_id']) {
    die("You may only view your own sheets.");
}

// save
if (Request::IsPost()) {
    // test to make sure that a person's viewing their own
    $oldCharacter = $characterRepository->GetById($characterId);
    /* @var Character $oldCharacter */
    $powers = $oldCharacter->CharacterPower;

    if (($character['asst_sanctioned'] != '') || ($character['is_sanctioned'] != '')) {
        $characterSheetHelper->UpdateOwnLimited($oldCharacter, $_POST);
    }
    else {
        $characterSheetHelper->UpdateOwnFull($oldCharacter, $_POST);
    }
    SessionHelper::SetFlashMessage('Updated Character');
    Response::Redirect('view_sheet.php?action=view_own_xp&character_id='.$characterId);
}


$edit_xp = "false";
if ($character['is_sanctioned'] == '' && $character['asst_sanctioned'] == '') {
    $edit_xp = "true";
}

$java_script .= <<<EOQ
<script src="js/create_character_xp.js" type="text/javascript"></script>
<script>
    $(function() {
        setXpEdit($edit_xp);
        drawSheet();
    });
</script>
EOQ;

require_once('helpers/character_menu.php');
/* @var array $characterMenu */
$characterMenu['Help'] = array(
    'link'    => '#',
    'submenu' => array(
        'Character Creation'  => array(
            'link'   => '/wiki/?CharacterCreation',
            'target' => '_blank'
        ),
        'ORB List'            => array(
            'link'   => '/wiki/?ORBList',
            'target' => '_blank'
        ),
        'Goals &amp; Beliefs' => array(
            'link'   => '/wiki/?GoalsAndBeliefs',
            'target' => '_blank'
        )
    )
);
$menu = MenuHelper::GenerateMenu($characterMenu);
$characterSheet = $characterSheetHelper->MakeViewOwn($character, $character['character_type']);
ob_start();
?>

<?php echo $menu; ?>
    <form name="character_sheet" id="character_sheet" method="post"
          action="<?php echo $_SERVER['PHP_SELF']; ?>?action=view_own_xp">
        <div style="text-align: center;" id="charSheet">
            <?php echo $characterSheet; ?>
        </div>
    </form>
<?php
$page_content = ob_get_clean();
