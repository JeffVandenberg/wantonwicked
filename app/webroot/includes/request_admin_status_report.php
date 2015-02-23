<?php
use classes\core\repository\RepositoryManager;
use classes\request\repository\RequestRepository;

$contentHeader = $page_title = 'Request Status Report';

$requestRepository = RepositoryManager::GetRepository('classes\request\data\Request');
/* @var RequestRepository $requestRepository */

$rows = $requestRepository->GetStatusReport();

$page_content = '';
$lastGroup    = '';
$lastTotal    = 0;
$groupRows    = array();
foreach ($rows as $row) {
    if ($row['group_name'] !== $lastGroup) {
        if ($lastGroup !== '') {
            $page_content .= generateGroupOutput($lastGroup, $lastTotal, $groupRows);
        }
        $lastGroup = $row['group_name'];
        $lastTotal = 0;
        $groupRows = array();
    }
    $groupRows[] = $row;
    $lastTotal += $row['total'];
}
$page_content .= generateGroupOutput($lastGroup, $lastTotal, $groupRows);

function generateGroupOutput($lastGroup, $lastTotal, $groupRows)
{
    ob_start();
    ?>
    <table>
        <thead>
        <tr>
            <th style="width: 33%;">
                Group: <?php echo $lastGroup; ?>
            </th>
            <th style="width: 67%;">
                Total: <?php echo $lastTotal; ?>
            </th>
        </tr>
        <tr>
            <th>
                Status
            </th>
            <th>
                Total
            </th>
        </tr>
        </thead>
        <?php foreach ($groupRows as $row): ?>
            <tr>
                <td>
                    <?php echo $row['status_name']; ?>
                </td>
                <td>
                    <?php echo $row['total']; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <?php
    return ob_get_clean();
}