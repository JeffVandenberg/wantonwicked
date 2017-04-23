<?php
use classes\character\data\Character;
use classes\character\repository\CharacterRepository;
use classes\core\helpers\Request;
use classes\core\helpers\Response;
use classes\core\helpers\UserdataHelper;
use classes\core\repository\RepositoryManager;
use classes\request\repository\RequestRepository;
use classes\scene\repository\SceneRepository;

/* @var array $userdata */

$page_title = $contentHeader = "Player Dashboard";

if (!UserdataHelper::IsLoggedIn($userdata)) {
    Response::redirect('/', 'You are not logged in');
}

$characterRepository = RepositoryManager::GetRepository('classes\character\data\Character');
$requestRepository = RepositoryManager::GetRepository('classes\request\data\Request');
$sceneRepository = new SceneRepository();
/* @var CharacterRepository $characterRepository */
/* @var RequestRepository $requestRepository */

$id = $userdata['user_id'];
if (UserdataHelper::IsAdmin($userdata)) {
    $id = Request::getValue('u', $id);
}
$characters = $characterRepository->listForDashboard($id);
/* @var Character[] $characters */
$requests = $requestRepository->listForDashboard($id);
$sceneSummary = $sceneRepository->findPlayerSceneDashboard($id);

ob_start();
?>

    <div class="row">
        <div class="small-12 medium-6 column">
            <div class="clearfix">
                <h3 class="float-left">
                    Characters
                </h3>
                <div class="button-group float-right">
                    <a class="button small" href="/characters/add">New</a>
                    <a class="button small hide-for-small-only" href="/chat">OOC Chat</a>
                </div>
            </div>
            <?php if (count($characters) > 0): ?>
                <table>
                    <tr>
                        <th>
                            Name
                        </th>
                        <th>
                            Actions
                        </th>
                    </tr>
                    <?php foreach ($characters as $character): ?>
                        <?php $identifier = ($character->Slug) ? $character->Slug : $character->Id; ?>
                        <tr>
                            <td>
                                <?php if ($character->IsSanctioned == 'Y'): ?>
                                    <div class="success badge has-tip" data-tooltip title="Sanctioned"><i
                                                class="fi-check"></i></div>
                                <?php elseif ($character->IsSanctioned == 'N'): ?>
                                    <div class="alert badge has-tip" data-tooltip title="Desanctioned"><i
                                                class="fi-x"></i></div>
                                <?php else: ?>
                                    <div class="badge has-tip" data-tooltip title="New"><i class="fi-x"></i></div>
                                <?Php endif; ?>
                                <?php echo htmlspecialchars($character->CharacterName); ?>
                            </td>
                            <td>
                                <div class="button-group float-right">
                                    <a href="/character.php?action=interface&character_id=<?php echo $character->Id; ?>"
                                       target="_blank" class="button float-right">Interface
                                    </a>
                                    <a class="dropdown button arrow-only" type="button"
                                       data-toggle="<?php echo $character->Id; ?>-dropdown">
                                        <span class="show-for-sr">Show menu</span>
                                    </a>
                                    <div class="dropdown-pane bottom" id="<?php echo $character->Id; ?>-dropdown"
                                         data-dropdown data-auto-focus="true">
                                        <ul class="vertical menu">
                                            <li>
                                                <a href="/characters/viewOwn/<?php echo $identifier; ?>"
                                                   >Sheet
                                                </a>
                                            </li>
                                            <li>
                                                <a href="/wiki/?n=Players.<?php echo preg_replace("/[^A-Za-z0-9]/", '', $character->CharacterName); ?>"
                                                   >Profile
                                                </a>
                                            </li>
                                            <li>
                                                <a href="/chat?character_id=<?php echo $character->Id; ?>"
                                                   target="_blank"
                                                   class="">Chat
                                                </a>
                                            </li>
                                            <li>
                                                <a href="request.php?action=create&character_id=<?php echo $character->Id; ?>">
                                                    New Request
                                                </a>
                                            </li>
                                            <?php if($character->IsSanctioned === 'Y'): ?>
                                                <li>
                                                    <a href="/characters/beats/<?php echo $character->Slug; ?>">
                                                        Beat Tracker
                                                    </a>
                                                </li>
                                            <?php endif; ?>
                                        </ul>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php else: ?>
                <div class="paragraph">
                    You have no characters currently.
                </div>
            <?php endif; ?>
        </div>
        <div class="small-12 medium-6 column">
            <div class="clearfix">
                <h3 class="float-left">
                    Active Requests
                </h3>
                <div class="button-group float-right">
                    <a class="button small" href="/request.php">All Requests</a>
                </div>
            </div>
            <div class="" style="">
                <?php if (count($requests)): ?>
                    <table>
                        <thead>
                        <tr>
                            <th>
                                Request
                            </th>
                            <th>
                                Type
                            </th>
                            <th>
                                Status
                            </th>
                        </tr>
                        </thead>
                        <?php foreach ($requests as $r): ?>
                            <tr>
                                <td>
                                    <a href="request.php?action=view&request_id=<?php echo $r['id']; ?>">
                                        <?php echo $r['title']; ?>
                                </td>
                                <td><?php echo $r['request_type_name']; ?></td>
                                <td><?php echo $r['request_status_name']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                <?php else: ?>
                    No pending requests at the moment
                <?php endif; ?>
            </div>
            <div class="clearfix">
                <h3 class="float-left">
                    Upcoming Scenes
                </h3>
                <div class="button-group float-right">
                    <a class="button small" href="/scenes">Scene Calendar</a>
                </div>
            </div>
            <div style="padding-top:5px;">
                <?php if (count($sceneSummary)): ?>
                    <table style="width:95%;">
                        <thead>
                        <tr>
                            <th>
                                Name
                            </th>
                            <th>
                                Run Date
                            </th>
                            <th>
                                Participants
                            </th>
                        </tr>
                        </thead>
                        <?php foreach ($sceneSummary as $row): ?>
                            <tr>
                                <td>
                                    <a href="/scenes/view/<?php echo $row['slug']; ?>"><?php echo $row['name']; ?></a>
                                </td>
                                <td class="server-time"><?php echo date('Y-m-d g:i A', strtotime($row['run_on_date'])); ?>
                                <td>
                                    <?php echo $row['participants']; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                    <div style="text-align: center;">
                        <a href="/scenes">View Upcoming Scenes</a>
                    </div>
                <?php else: ?>
                    You have no Upcoming Scenes.
                <?php endif; ?>
            </div>
        </div>
    </div>
    <script>
        $(function () {
        })
    </script>
<?php
$page_content = ob_get_clean();
