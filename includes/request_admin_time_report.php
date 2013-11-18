<?php
use classes\core\helpers\TimeHelper;
use classes\core\repository\RepositoryManager;
use classes\request\repository\RequestRepository;

$contentHeader = $page_title = 'Request Time Report';

$requestRepository = RepositoryManager::GetRepository('classes\request\data\Request');
/* @var RequestRepository $requestRepository */

$rows = $requestRepository->GetTimeReport();

ob_start();
?>

<table>
    <tr>
        <th>
            Character Type
        </th>
        <th>
            Time to First View
        </th>
        <th>
            Time to Terminal
        </th>
        <th>
            Time to Close
        </th>
    </tr>
    <?php foreach($rows as $row): ?>
        <tr>
            <td>
                <?php echo $row['character_type']; ?>
            </td>
            <td>
                <?php echo TimeHelper::ToHumanTime($row['first_view']); ?>
            </td>
            <td>
                <?php echo TimeHelper::ToHumanTime($row['terminal_status']); ?>
            </td>
            <td>
                <?php echo TimeHelper::ToHumanTime($row['closed']); ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
<?php
$page_content = ob_get_clean();