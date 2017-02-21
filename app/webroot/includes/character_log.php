<?php
/* @var array $userdata */
use classes\character\data\Character;
use classes\character\data\LogCharacter;
use classes\character\repository\CharacterRepository;
use classes\character\repository\LogCharacterRepository;
use classes\core\helpers\FormHelper;
use classes\core\helpers\MenuHelper;
use classes\core\helpers\Request;
use classes\core\helpers\Response;
use classes\core\helpers\UserdataHelper;
use classes\core\repository\RepositoryManager;
use classes\log\data\ActionType;

$page = Request::getValue('page', 1);
$pageSize = Request::getValue('page_size', 25);

$characterId = Request::getValue('character_id', 0);
$filterLogins = Request::getValue('filter_logins', 1);
$logId = Request::getValue('log_id', null);

$characterRepository = new CharacterRepository();
if ((!$characterRepository->MayViewCharacter($characterId, $userdata['user_id'])) && !UserdataHelper::IsSt($userdata)) {
    Response::redirect('/', 'Unable to view that character');
}

$logCharacterRepository = RepositoryManager::GetRepository('classes\character\data\LogCharacter');
$filterOptions = [
    'character_id' => $characterId,
    'filter_logins' => $filterLogins,
    'log_id' => $logId
];

/* @var LogCharacterRepository $logCharacterRepository */
$records = $logCharacterRepository->ListByCharacterIdPaged($filterOptions, $page, $pageSize);
/* @var LogCharacter[] $records */
$count = $logCharacterRepository->ListByCharacterIdRowRount($filterOptions);

$hasPrev = false;
$hasNext = false;

if($page > 1) {
    $hasPrev = true;
}
if(ceil($count / $pageSize) > $page) {
    $hasNext = true;
}

$characterRepository = RepositoryManager::GetRepository('classes\character\data\Character');
$character = $characterRepository->getById($characterId);
/* @var Character $character */

$page_title = 'Log for ' . $character->CharacterName;
$contentHeader = $page_title;

$options = http_build_query([
    'action' => 'log',
    'character_id' => $characterId,
    'filter_logins' => $filterLogins
]);

require_once('menus/character_menu.php');
/* @var array $characterMenu */
$menu = MenuHelper::GenerateMenu($characterMenu);
ob_start();
?>

<?php echo $menu; ?>
<style>
    form label {
        display: inline;
    }
</style>
<div style="padding: 10px 0;">
    <form method="get" action="/character.php">
        <?php echo FormHelper::Hidden('character_id', $characterId); ?>
        <?php echo FormHelper::Hidden('action', 'log'); ?>
        <?php echo FormHelper::Checkbox('filter_logins', 1, $filterLogins == 1, [
            'label' => 'Filter out Chat Logins'
        ]); ?>
        <?php echo FormHelper::Checkbox('clear_log_id', 0, false, [
            'label' => 'Clear Log ID Filter'
        ]); ?>
        <button type="submit" class="button" value="Update">Update</button>
    </form>
</div>
<table>
    <tr>
        <th colspan="5">
            <?php if($hasPrev): ?>
                <a href="character.php?<?php echo $options; ?>&page=<?php echo $page-1; ?>">&lt; &lt;</a>
            <?php else: ?>
                &lt; &lt;
            <?php endif; ?>
            <form method="get" style="display: inline;" action="character.php">
                Page:
                <?php echo FormHelper::Hidden('character_id', $characterId); ?>
                <?php echo FormHelper::Hidden('action', 'log'); ?>
                <?php echo FormHelper::Hidden('filter_logins', $filterLogins); ?>
                <?php echo FormHelper::Text('page', $page, array('style' => 'width: 30px;display: inline-block;')); ?>
            </form>
            <?php if($hasNext): ?>
                <a href="character.php?<?php echo $options; ?>&page=<?php echo $page+1; ?>">&gt; &gt;</a>
            <?php else: ?>
                &gt; &gt;
            <?php endif; ?>
            -
            Viewing Records (<?php echo (($page-1)*$pageSize) + 1; ?> to <?php echo min($page * $pageSize, $count); ?>)
            Total Records: <?php echo $count; ?>
        </th>
    </tr>
    <tr>
        <th>
            Action
        </th>
        <th>
            Note
        </th>
        <th>
            Created By
        </th>
        <th>
            Created On
        </th>
        <th>
            Action
        </th>
    </tr>
    <?php foreach($records as $record): ?>
        <tr>
            <td>
                <?php echo $record->ActionType->Name; ?>
            </td>
            <td>
                <?php echo $record->Note; ?>
            </td>
            <td>
                <?php echo $record->CreatedBy->Username; ?>
            </td>
            <td>
                <?php echo $record->Created; ?>
            </td>
            <td>
                <?php if ($record->ActionTypeId == ActionType::ViewRequest): ?>
                    <?php if(UserdataHelper::IsSt($userdata)): ?>
                        <a href="/request.php?action=st_view&request_id=<?php echo $record->ReferenceId; ?>">View Request</a>
                    <?php else: ?>
                        <a href="/request.php?action=view&request_id=<?php echo $record->ReferenceId; ?>">View Request</a>
                    <?php endif; ?>
                <?php endif; ?>
                <a href="/character.php?action=log&character_id=<?php echo $characterId; ?>&log_id=<?php echo $record->Id; ?>">Link</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
<?php
$page_content = ob_get_clean();
