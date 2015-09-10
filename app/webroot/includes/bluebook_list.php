<?php
/* @var array $userdata */
use classes\character\repository\CharacterRepository;
use classes\core\helpers\FormHelper;
use classes\core\helpers\MenuHelper;
use classes\core\helpers\Pagination;
use classes\core\helpers\Request;
use classes\core\helpers\Response;
use classes\request\data\RequestStatus;
use classes\request\repository\RequestRepository;

$characterId = Request::getValue('character_id', 0);
$characterRepository = new CharacterRepository();
if (!$characterRepository->MayViewCharacter($characterId, $userdata['user_id'])) {
    Response::redirect('/', 'Unable to view that character');
}

$character = $characterRepository->FindById($characterId);

$page = Request::getValue('page', 1);
$pageSize = Request::getValue('page_size', 20);
$sort = Request::getValue('sort', 'updated_on DESC');
$filter = Request::getValue('filter', array());

$pagination = new Pagination();
$pagination->SetSort($sort);

$requestRepository = new RequestRepository();
$requests = $requestRepository->ListBlueBookByCharacterId($characterId, $page, $pageSize, $sort, $filter);
$count = $requestRepository->ListBlueBookByCharacterIdCount($characterId);

$hasPrev = false;
$hasNext = false;

if($page > 1) {
    $hasPrev = true;
}
if(($count / $pageSize) > $page) {
    $hasNext = true;
}

$contentHeader = $page_title = "Bluebook Entries for " . $character['character_name'];

require_once('menus/character_menu.php');
/* @var array $characterMenu */
$characterMenu['Create Entry'] = array(
    'link' => 'bluebook.php?action=create&character_id=' . $characterId
);
$menu = MenuHelper::GenerateMenu($characterMenu);
ob_start();
?>

<?php echo $menu; ?>
    <table>
        <tr>
            <th>
                <?php if($hasPrev): ?>
                    <a href="/bluebook.php?action=list&character_id=<?php echo $characterId; ?>&page=<?php echo $page-1; ?>&sort=<?php echo $pagination->GetSort(); ?>">&lt; &lt;</a>
                <?php else: ?>
                    &lt; &lt;
                <?php endif; ?>
                <form method="get" style="display: inline;" action="/bluebook.php">
                    Page:
                    <?php echo FormHelper::Hidden('sort', $sort); ?>
                    <?php echo FormHelper::Hidden('action', 'list'); ?>
                    <?php echo FormHelper::Hidden('character_id', $characterId); ?>
                    <?php echo FormHelper::Text('page', $page, array('style' => 'width: 30px;')); ?>
                </form>
                <?php if($hasNext): ?>
                    <a href="/bluebook.php?action=list&character_id=<?php echo $characterId; ?>&page=<?php echo $page+1; ?>&sort=<?php echo $pagination->GetSort(); ?>">&gt; &gt;</a>
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
                        <a href="/bluebook.php?action=view&bluebook_id=<?php echo $request['id']; ?>">View</a>
                        <?php if($request['request_status_id'] == RequestStatus::NewRequest): ?>
                            <a href="/bluebook.php?action=edit&bluebook_id=<?php echo $request['id']; ?>">Edit</a>
                        <?php endif; ?>
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