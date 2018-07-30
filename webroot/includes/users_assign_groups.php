<?php
use classes\core\helpers\FormHelper;
use classes\core\repository\Database;


$page_title = 'Assign User to Groups';
$groupSql = <<<EOQ
SELECT
    group_id,
    group_name
FROM
    phpbb_groups
ORDER BY
    group_name
EOQ;

$groups = Database::getInstance()->query($groupSql)->all();

ob_start();
?>

    <form method="post">
        <div style="text-align:center;">
            <?php echo FormHelper::text('username', '', array('label' => 'User Name')); ?>
            <?php echo FormHelper::hidden('user_id', ''); ?>
            <?php echo FormHelper::button('load_user', 'Load Permissions', 'button'); ?>
            <?php echo FormHelper::button('save_user', 'Save Permissions', 'button'); ?>
        </div>
        <table>
            <thead>
            <tr>
                <th>
                    Group Name
                </th>
                <th>
                    Is Member
                </th>
                <th>
                    Is Moderator
                </th>
            </tr>
            </thead>
            <?php foreach ($groups as $group): ?>
                <tr>
                    <td>
                        <?php echo $group['group_name']; ?>
                    </td>
                    <td>
                        <?php echo FormHelper::checkbox('group_id[]', $group['group_id'], false); ?>
                    </td>
                    <td>
                        <?php echo FormHelper::checkbox('is_moderator[]', 1, false); ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </form>
<?php
$page_content = ob_get_clean();
