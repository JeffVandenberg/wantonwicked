<?php
use classes\core\repository\RepositoryManager;
use classes\request\layout\AdminStatusReport;
use classes\request\repository\RequestRepository;

$contentHeader = $page_title = 'Request Status Report';

$requestRepository = RepositoryManager::GetRepository('classes\request\data\Request');
/* @var RequestRepository $requestRepository */

$rows = $requestRepository->GetStatusReport();

$page_content = '';
$lastGroup = '';
$lastTotal = 0;
$groupRows = array();
$adminStatusReport = new AdminStatusReport();
foreach ($rows as $row) {
    if ($row['group_name'] !== $lastGroup) {
        if ($lastGroup !== '') {
            $page_content .= $adminStatusReport->generateGroupOutput($lastGroup, $lastTotal, $groupRows);
        }
        $lastGroup = $row['group_name'];
        $lastTotal = 0;
        $groupRows = array();
    }
    $groupRows[] = $row;
    $lastTotal += $row['total'];
}
$page_content .= $adminStatusReport->generateGroupOutput($lastGroup, $lastTotal, $groupRows);
