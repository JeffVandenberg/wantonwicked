<?php
/* @var array $userdata */
use classes\character\repository\CharacterRepository;
use classes\core\helpers\FormHelper;
use classes\core\helpers\Pagination;
use classes\core\helpers\Request;
use classes\core\repository\RepositoryManager;
use classes\log\CharacterLog;
use classes\log\data\ActionType;
use classes\request\repository\RequestRepository;

$characterId = Request::getValue('character_id', 0);
$characterRepository = new CharacterRepository();
$character = $characterRepository->FindById($characterId);

$requestRepository = RepositoryManager::GetRepository('classes\request\data\Request');
/* @var RequestRepository $requestRepository */

$page = Request::getValue('page', 1);
$pageSize = Request::getValue('page_size', 20);
$sort = Request::getValue('sort', 'updated_on DESC');
$filter = Request::getValue('filter', array());

$pagination = new Pagination();
$pagination->SetSort($sort);

CharacterLog::LogAction($characterId, ActionType::BlueBookList, 'View Bluebook List: Page: ' . $page, $userdata['user_id']);
$requests = $requestRepository->ListBlueBookByCharacterId($characterId, $page, $pageSize, $sort);
$count = $requestRepository->ListBlueBookByCharacterIdCount($characterId);

$hasPrev = false;
$hasNext = false;

if($page > 1) {
    $hasPrev = true;
}
if(($count / $pageSize) > $page) {
    $hasNext = true;
}

$contentHeader = $page_title = "Bluebook Entries for " . $character['Character_Name'];

ob_start();
?>

    <table>
        <tr>
            <th>
                <?php if($hasPrev): ?>
                    <a href="/bluebook.php?action=st_list&character_id=<?php echo $characterId; ?>&page=<?php echo $page-1; ?>&sort=<?php echo $pagination->GetSort(); ?>">&lt; &lt;</a>
                <?php else: ?>
                    &lt; &lt;
                <?php endif; ?>
                <form method="get" style="display: inline;" action="/bluebook.php">
                    Page:
                    <?php echo FormHelper::Hidden('sort', $sort); ?>
                    <?php echo FormHelper::Hidden('action', 'st_list'); ?>
                    <?php echo FormHelper::Hidden('character_id', $characterId); ?>
                    <?php echo FormHelper::Text('page', $page, array('style' => 'width: 30px;')); ?>
                </form>
                <?php if($hasNext): ?>
                    <a href="/bluebook.php?action=st_list&character_id=<?php echo $characterId; ?>&page=<?php echo $page+1; ?>&sort=<?php echo $pagination->GetSort(); ?>">&gt; &gt;</a>
                <?php else: ?>
                    &gt; &gt;
                <?php endif; ?>
                -
                Viewing Records (<?php echo (($page-1)*$pageSize) + 1; ?> to <?php echo min($page * $pageSize, $count); ?>)
                Total Records: <?php echo $count; ?>
            </th>
        </tr>
    </table>
    <table>
        <tr>
            <th>
                Name
            </th>
            <th>
                Status
            </th>
            <th>
                Created On
            </th>
            <th>
                Updated On
            </th>
        </tr>

        <?php if (count($requests) > 0): ?>
            <?php foreach ($requests as $request): ?>
                <tr>
                    <td>
                        <?php echo $request['title']; ?>
                    </td>
                    <td>
                        <?php echo $request['request_status_name']; ?>
                    </td>
                    <td>
                        <?php echo date('m/d/Y', strtotime($request['created_on'])); ?>
                    </td>
                    <td>
                        <?php echo date('m/d/Y', strtotime($request['updated_on'])); ?>
                    </td>
                    <td>
                        <a href="/bluebook.php?action=st_view&bluebook_id=<?php echo $request['id']; ?>">View</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="3" style="text-align: center;">
                    No Bluebook Entries
                </td>
            </tr>
        <?php endif; ?>
    </table>
    <script>
        $(function () {
            $(".button").button();
        })
    </script>
<?php
$page_content = ob_get_clean();