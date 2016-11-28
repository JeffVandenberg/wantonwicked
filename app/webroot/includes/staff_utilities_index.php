<?php
/* @var array $userdata */

use classes\core\helpers\MenuHelper;
use classes\request\repository\RequestRepository;
use classes\scene\repository\SceneRepository;

$contentHeader = $page_title = "Staff Utilities";

$requestRepository = new RequestRepository();
$requestSummary = $requestRepository->findStRequestDashboard($userdata['user_id']);

$sceneRepository = new SceneRepository();
$sceneSummary = $sceneRepository->findStSceneDashboard($userdata['user_id']);

$staffMenu = require_once('menus/staff_menu.php');
$menu = MenuHelper::GenerateMenu($staffMenu);
ob_start();
?>
<?php echo $menu; ?>
    <div style="overflow:auto;">
        <div style="width:610px;float:left;">
            <h2>Request Summary</h2>
            <div style="padding-top:5px;">
                <table style="width:95%;">
                    <thead>
                    <tr>
                        <th>
                            Group
                        </th>
                        <th>
                            Status
                        </th>
                        <th>
                            Count
                        </th>
                    </tr>
                    </thead>
                    <?php foreach ($requestSummary as $row): ?>
                        <tr>
                            <td><?php echo $row['group_name']; ?></td>
                            <td><?php echo $row['request_status_name']; ?></td>
                            <td>
                                <a href="request.php?filter%5Btitle%5D=&filter%5Busername%5D=&filter%5Brequest_type_id%5D=0&filter%5Brequest_status_id%5D=<?php echo $row['request_status_id']; ?>&action=st_list&page_action=Update+Filters">
                                    <?php echo $row['total']; ?>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
                <div style="text-align:center;">
                    <a href="request.php">
                        View all Requests
                    </a>
                </div>
            </div>
            <h2>
                Upcoming Scenes
            </h2>
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
                        <?php foreach($sceneSummary as $row): ?>
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
                    You have no Upcoming Scenes. Perhaps you should
                    <a href="/scenes/add">create a scene?</a>
                <?php endif; ?>
            </div>
        </div>
        <div style="width:350px;float:left;">
            <iframe
                src="https://discordapp.com/widget?id=108787391015157760&theme=dark&username=<?php echo $userdata['username']; ?>"
                width="350" height="500" allowtransparency="true" frameborder="0"></iframe>
        </div>
    </div>
<?php
$page_content = ob_get_clean();
