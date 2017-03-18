<?php
use classes\core\helpers\FormHelper;
use classes\core\helpers\Request;
use classes\core\helpers\UserdataHelper;
use classes\core\repository\RepositoryManager;
use classes\request\repository\RequestRepository;

$contentHeader = $page_title = 'ST Activity Report';

$requestRepository = RepositoryManager::GetRepository('classes\request\data\Request');
/* @var RequestRepository $requestRepository */

$startDate = Request::getValue('start_date', date('Y-m-d', strtotime('-7 days')));
$endDate   = Request::getValue('end_date', date('Y-m-d'));

$userId = UserdataHelper::IsHead($userdata) ? null : $userdata['user_id'];
$rows = $requestRepository->GetSTActivityReport($userId, $startDate, $endDate);


ob_start();
?>

    <form method="post">
        <?php echo FormHelper::Text('start_date', $startDate, array('label' => true)); ?>
        <?php echo FormHelper::Text('end_date', $endDate, array('label' => true)); ?>
        <?php echo FormHelper::Button('', 'Update'); ?>
    </form>
    <table>
        <thead>
        <tr>
            <th>Story Teller</th>
            <th>Status Name</th>
            <th>Total</th>
        </tr>
        </thead>
        <?php $lastSt = ''; ?>
        <?php foreach ($rows as $row): ?>
            <tr>
                <td>
                    <?php if ($row['username'] != $lastSt): ?>
                        <?php echo $lastSt = $row['username']; ?>
                    <?php endif; ?>
                </td>
                <td><?php echo $row['status_name']; ?></td>
                <td><?php echo $row['total']; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
    <script type="application/javascript">
        $(function () {
            $("#start-date, #end-date").datepicker({
                dateFormat: 'yy-mm-dd'
            });
        })
    </script>
<?php
$page_content = ob_get_clean();