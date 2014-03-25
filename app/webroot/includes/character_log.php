<?php
/* @var array $userdata */
use classes\character\data\Character;
use classes\character\data\LogCharacter;
use classes\character\repository\CharacterRepository;
use classes\character\repository\LogCharacterRepository;
use classes\core\helpers\FormHelper;
use classes\core\helpers\MenuHelper;
use classes\core\helpers\Request;
use classes\core\helpers\UserdataHelper;
use classes\core\repository\RepositoryManager;
use classes\log\data\ActionType;

$page = Request::GetValue('page', 1);
$pageSize = Request::GetValue('page_size', 25);

$characterId = Request::GetValue('character_id', 0);
$characterRepository = new CharacterRepository();
if ((!$characterRepository->MayViewCharacter($characterId, $userdata['user_id'])) && !UserdataHelper::IsSt($userdata)) {
    include 'index_redirect.php';
    die();
}

$logCharacterRepository = RepositoryManager::GetRepository('classes\character\data\LogCharacter');
/* @var LogCharacterRepository $logCharacterRepository */
$records = $logCharacterRepository->ListByCharacterIdPaged($characterId, $page, $pageSize);
/* @var LogCharacter[] $records */
$count = $logCharacterRepository->ListByCharacterIdRowRount($characterId);

$hasPrev = false;
$hasNext = false;

if($page > 1) {
    $hasPrev = true;
}
if(ceil($count / $pageSize) > $page) {
    $hasNext = true;
}

$characterRepository = RepositoryManager::GetRepository('classes\character\data\Character');
$character = $characterRepository->GetById($characterId);
/* @var Character $character */

$page_title = 'Log for ' . $character->CharacterName;
$contentHeader = $page_title;

require_once('helpers/character_menu.php');
/* @var array $characterMenu */
$menu = MenuHelper::GenerateMenu($characterMenu);
ob_start();
?>

<?php echo $menu; ?>
<table>
    <tr>
        <th colspan="5">
            <?php if($hasPrev): ?>
                <a href="character.php?action=log&character_id=<?php echo $characterId; ?>&page=<?php echo $page-1; ?>">&lt; &lt;</a>
            <?php else: ?>
                &lt; &lt;
            <?php endif; ?>
            <form method="get" style="display: inline;" action="character.php">
                Page:
                <?php echo FormHelper::Hidden('character_id', $characterId); ?>
                <?php echo FormHelper::Hidden('action', 'log'); ?>
                <?php echo FormHelper::Text('page', $page, array('style' => 'width: 30px;')); ?>
            </form>
            <?php if($hasNext): ?>
                <a href="character.php?action=log&character_id=<?php echo $characterId; ?>&page=<?php echo $page+1; ?>">&gt; &gt;</a>
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
                    <?php if(UserdataHelper::IsHead($userdata)): ?>
                        <a href="request.php?action=st_view&request_id=<?php echo $record->ReferenceId; ?>">View Request</a>
                    <?php else: ?>
                        <a href="request.php?action=view&request_id=<?php echo $record->ReferenceId; ?>">View Request</a>
                    <?php endif; ?>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
<?php
$page_content = ob_get_clean();