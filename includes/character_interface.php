<?php
/* @var array $userdata */

use classes\character\data\Character;
use classes\character\repository\CharacterRepository;
use classes\core\helpers\MenuHelper;
use classes\core\helpers\Request;
use classes\core\helpers\Response;
use classes\core\helpers\SessionHelper;
use classes\core\helpers\UserdataHelper;
use classes\core\repository\RepositoryManager;
use classes\log\CharacterLog;
use classes\log\data\ActionType;

$characterId = Request::GetValue('character_id', 0);

$character_type = "";

$characterRepository = RepositoryManager::GetRepository('classes\character\data\Character');
/* @var CharacterRepository $characterRepository */
$character = $characterRepository->FindByIdObj($characterId);
/* @var Character $character */
if($character->CharacterId == 0) {
    SessionHelper::SetFlashMessage("Invalid Character");
    Response::Redirect('chat.php');
}

if($character->IsNpc == 'Y') {
    if(!UserdataHelper::IsSt($userdata)) {
        CharacterLog::LogAction($characterId, ActionType::InvalidAccess, 'Attempted access to character interface', $userdata['user_id']);
        SessionHelper::SetFlashMessage("You're not authorized to view that character.");
        Response::Redirect('');
    }
}
else {
    if($character->PrimaryLoginId != $userdata['user_id']) {
        CharacterLog::LogAction($characterId, ActionType::InvalidAccess, 'Attempted access to character interface', $userdata['user_id']);
        SessionHelper::SetFlashMessage("You're not authorized to view that character.");
        Response::Redirect('');
    }
}


//CharacterLog::LogAction($character->CharacterId, ActionType::Login, '', $userdata['user_id']);
// found a character
$page_title = "Interface for $character->CharacterName";
$contentHeader = $character->CharacterName;
$character_type = $character->CharacterType;



// set up user information
$extraLinks = "";
switch ($character->CharacterType) {
    case 'Mortal':
        $morality = "Morality";
        break;
    case 'Vampire':
        $morality = "Humanity";

        $abpRating = $character->AveragePowerPoints;

        $extraLinks = <<<EOQ
<div style="margin-bottom:10px;text-align:center;">
    <a href="/territory.php?action=list_territories&character_id=$characterId" target="_blank">View Tenancy for Character</a>
</div>
EOQ;

        $extra_rows = <<<EOQ
<tr>
    <td>
        Coterie
    </td>
    <td>
        $character->Friends
    </td>
</tr>
<tr>
    <td>
        Vitae
    </td>
    <td>
        $character->PowerPoints
    </td>
</tr>
<tr>
    <td>
        ABP
    </td>
    <td>
        $abpRating
        <a href="abp.php?action=show_modifiers&character_id=$characterId" target="_blank">Details</a>
    </td>
</tr>
EOQ;
        break;
    case 'Ghoul':
        $morality = "Morality";
        $extra_rows = <<<EOQ
<tr>
    <td>
        Domitor
    </td>
    <td>
        $character->Friends
    </td>
</tr>
<tr>
    <td>
        Vitae
    </td>
    <td>
        $character->PowerPoints
    </td>
</tr>
EOQ;
        break;
    case 'Werewolf':
        $morality = "Harmony";
        $extra_rows = <<<EOQ
<tr>
    <td>
        Pack
    </td>
    <td>
        $character->Friends
    </td>
</tr>
<tr>
    <td>
        Essence
    </td>
    <td>
        $character->PowerPoints
    </td>
</tr>
EOQ;
        break;
    case 'Mage':
        $morality = "Wisdom";

        $extra_rows = <<<EOQ
<tr>
    <td>
        Cabal
    </td>
    <td>
        $character->Friends
    </td>
</tr>
<tr>
    <td>
        Mana
    </td>
    <td>
        $character->PowerPoints
    </td>
</tr>
EOQ;
        break;

    case 'Thaumaturge':
        $morality = "Morality";
        break;

    default:
        $morality = "Morality";
        break;
}

$characterInfo = <<<EOQ
<table>
    <tr>
        <td colspan="2" align="center">
            <a href="/view_sheet.php?action=view_own_xp&character_id=$characterId" target="_blank">View Sheet</a>
        </td>
    </tr>
    <tr>
        <td>
            Virtue
        </td>
        <td>
            $character->Virtue
        </td>
    </tr>
    <tr>
        <td>
            Vice
        </td>
        <td>
            $character->Vice
        </td>
    </tr>
    <tr>
        <td>
            $morality
        </td>
        <td>
            $character->Morality
        </td>
    </tr>
    <tr>
        <td>
            Willpower
        </td>
        <td>
            $character->WillpowerTemp
        </td>
    </tr>
    <tr>
        <td>
            Initiative Mod
        </td>
        <td>
            $character->InitiativeMod
        </td>
    </tr>
    <tr>
        <td>
            Defense
        </td>
        <td>
            $character->Defense
        </td>
    </tr>
    <tr>
        <td>
            Armor
        </td>
        <td>
            $character->Armor
        </td>
    </tr>
    <tr>
        <td>
            Wounds
        </td>
        <td>
            A: $character->WoundsAgg
            L: $character->WoundsLethal
            B: $character->WoundsBashing
        </td>
    </tr>
    <tr>
        <td>
            Experience
        </td>
        <td>
            $character->CurrentExperience
        </td>
    </tr>
    $extra_rows
</table>
EOQ;

require_once('helpers/character_menu.php');
/* @var array $characterMenu */
$menu = MenuHelper::GenerateMenu($characterMenu);
ob_start();
?>
    <?php echo $menu; ?>
    <form id="character-login" method="get" action="/chat" target="_blank">
        <input type="hidden" name="character_id" value="<?php echo $characterId; ?>"/>
    </form>
    <div style="min-width:870px;width:100%;overflow:auto;">
        <div style="float:left;width:240px;min-height:300px;border:solid 0 #333333;">
            <div class="tableRowHeader" style="width:100%;">
                <div style="text-align: center;font-weight: bold;">
                    Character Information
                </div>
            </div>
            <?php echo $characterInfo; ?>
        </div>
    </div>
    <script>
        $(function () {
        });

        function loginCharacter() {
            $("#character-login").submit();
        }
    </script>

<?php
$page_content = ob_get_clean();