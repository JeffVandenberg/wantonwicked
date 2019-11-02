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
use classes\request\data\RequestStatus;
use classes\request\repository\RequestRepository;

$characterId = Request::getValue('character_id', 0);

$character_type = "";

$characterRepository = RepositoryManager::getRepository('classes\character\data\Character');
/* @var CharacterRepository $characterRepository */
$character = $characterRepository->getById($characterId);
/* @var Character $character */
if ($character->Id == 0) {
    SessionHelper::setFlashMessage("Invalid Character");
    Response::redirect('/chat.php');
}

$mayView = false;
if (($character->IsNpc == 'Y') && UserdataHelper::isSt($userdata)) {
    $mayView = true;
} else if (($character->UserId == $userdata['user_id']) || UserdataHelper::isAdmin($userdata)) {
    $mayView = true;
}

if (!$mayView) {
    CharacterLog::logAction($characterId, ActionType::INVALID_ACCESS, 'Attempted access to character interface', $userdata['user_id']);
    SessionHelper::setFlashMessage("You're not authorized to view that character.");
    Response::redirect('/');
}

// found a character
$page_title = "Interface for $character->CharacterName";
$contentHeader = $character->CharacterName;
$character_type = $character->CharacterType;


// set up user information
$extraLinks = "";
$extra_rows = '';
switch ($character->CharacterType) {
    case 'Mortal':
        $morality = "Morality";
        break;
    case 'Vampire':
        $morality = "Humanity";

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
            <a href="/characters/viewOwn/{$character->Slug}" target="_blank">View Sheet</a>
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

$requestRepository = new RequestRepository();
$newRequests = $requestRepository->countRequestsByCharacterIdAndStatus($characterId, RequestStatus::NEW_REQUEST);
$stRequests = $requestRepository->countRequestsByCharacterIdAndStatus($characterId, RequestStatus::SUBMITTED);
$stViewedRequests = $requestRepository->countRequestsByCharacterIdAndStatus($characterId, RequestStatus::IN_PROGRESS);
$returnedRequests = $requestRepository->countRequestsByCharacterIdAndStatus($characterId, RequestStatus::RETURNED);
$approvedRequests = $requestRepository->countRequestsByCharacterIdAndStatus($characterId, RequestStatus::APPROVED);
$rejectedRequests = $requestRepository->countRequestsByCharacterIdAndStatus($characterId, RequestStatus::DENIED);

require_once('menus/character_menu.php');
/* @var array $characterMenu */
$menu = MenuHelper::generateMenu($characterMenu);
ob_start();
?>
<?php echo $menu; ?>
    <form id="character-login" method="get" action="/chat" target="_blank">
        <input type="hidden" name="character_id" value="<?php echo $characterId; ?>"/>
    </form>
    <div style="min-width:870px;width:100%;overflow:auto;">
        <div style="float:left;width:290px;min-height:300px;border:solid 0 #333333;">
            <div class="tableRowHeader" style="width:100%;">
                <div style="text-align: center;font-weight: bold;">
                    Character Information
                </div>
            </div>
            <?php echo $characterInfo; ?>
        </div>
        <div style="float:left;width:290px;min-height:300px;border:solid 0 #333333;">
            <div class="tableRowHeader" style="width:100%;">
                <div style="text-align: center;font-weight: bold;">
                    Requests
                </div>
                <table>
                    <tr>
                        <td>
                            New
                        </td>
                        <td>
                            <a href="/requests/character/<?= $characterId; ?>?request_status_id=<?php echo RequestStatus::NEW_REQUEST; ?>">
                                <?php echo $newRequests; ?>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Sent to STs
                        </td>
                        <td>
                            <a href="/requests/character/<?= $characterId; ?>?request_status_id=<?php echo RequestStatus::SUBMITTED; ?>">
                                <?php echo $stRequests; ?>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Viewed by ST
                        </td>
                        <td>
                            <a href="/requests/character/<?= $characterId; ?>?request_status_id=<?php echo RequestStatus::IN_PROGRESS; ?>">
                                <?php echo $stViewedRequests; ?>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Returned
                        </td>
                        <td>
                            <a href="/requests/character/<?= $characterId; ?>?request_status_id=<?php echo RequestStatus::RETURNED; ?>">
                                <?php echo $returnedRequests; ?>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Approved
                        </td>
                        <td>
                            <a href="/requests/character/<?= $characterId; ?>?request_status_id=<?php echo RequestStatus::APPROVED; ?>">
                                <?php echo $approvedRequests; ?>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Denied
                        </td>
                        <td>
                            <a href="/requests/character/<?= $characterId; ?>?request_status_id=<?php echo RequestStatus::DENIED; ?>">
                                <?php echo $rejectedRequests; ?>
                            </a>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div style="float:left;width:290px;min-height:300px;border:solid 0 #333333;">
            <div class="tableRowHeader" style="width:100%;">
                <div style="text-align: center;font-weight: bold;">
                    Widgets
                </div>
                <div id="plemx-root"></div>
                <a href="http://www.theweathernetwork.com">The Weather Network</a>
                <script type="text/javascript">

                    var _plm = _plm || [];
                    _plm.push(['_btn', 106918]);
                    _plm.push(['_loc', 'usfl0316']);
                    _plm.push(['location', document.location.host]);
                    (function (d, e, i) {
                        if (d.getElementById(i)) return;
                        var px = d.createElement(e);
                        px.type = 'text/javascript';
                        px.async = true;
                        px.id = i;
                        px.src = ('https:' == d.location.protocol ? 'https:' : 'http:') + '//widget.twnmm.com/js/btn/pelm.js?orig=en_ca';
                        var s = d.getElementsByTagName('script')[0];

                        var py = d.createElement('link');
                        py.rel = 'stylesheet'
                        py.href = ('https:' == d.location.protocol ? 'https:' : 'http:') + '//widget.twnmm.com/styles/btn/styles.css'

                        s.parentNode.insertBefore(px, s);
                        s.parentNode.insertBefore(py, s);
                    })(document, 'script', 'plmxbtn');</script>
                <!-- // Begin Current Moon Phase HTML (c) MoonConnection.com // --><table cellpadding="0" cellspacing="0" border="0" width="128"><tr><td align="center"><a href="https://www.moonconnection.com" target="mc_moon_ph"><img src="https://www.moonmodule.com/cs/dm/vn.gif" width="128" height="196" border="0" alt="" /></a><div style="position:relative;width:128px;"><div style="position:absolute;top:-20px;left:6px;background:#000000;width:116px;text-align:center;"><a href="https://www.moonconnection.com/moon_phases.phtml" target="mc_moon_ph"><font color="#7F7F7F" size="1" face="arial,helvetica,sans-serif"><span style="color:#7F7F7F;font-family:arial,helvetica,sans-serif;font-size:10px;">moon phase info</span></font></a></div></div></td></tr></table><!-- // end moon phase HTML // -->
                </div><!-- // end moon phase HTML // -->
            </div>
        </div>
    </div>
<?php
$page_content = ob_get_clean();
