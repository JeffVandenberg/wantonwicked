<?php
/**
 * Created by PhpStorm.
 * User: JeffVandenberg
 * Date: 3/30/14
 * Time: 5:09 PM
 */

use classes\core\helpers\Request;
use classes\core\repository\Database;
use classes\log\CharacterLog;
use classes\log\data\ActionType;

require_once('cgi-bin/start_of_page.php');


$query = <<<EOQ
EXPLAIN SELECT
	t.*,
	p.root_level,
	p.message_time,
	p.message_subject,
	p.icon_id, p.to_address,
	p.message_attachment,
	p.bcc_address,
	u.username,
	u.username_clean,
	u.user_colour,
	p.message_reported
FROM
	phpbb_privmsgs_to t
	INNER JOIN phpbb_privmsgs p ON t.msg_id = p.msg_id
	INNER JOIN phpbb_users u ON p.author_id = u.user_id
WHERE
	t.user_id = 8
	AND t.folder_id = 0
ORDER BY p.message_time DESC
	LIMIT 50
EOQ;
//$query = <<<EOQ
//show create table phpbb_privmsgs_to
//EOQ;


$params = array();

$rows = Database::getInstance()->query($query)->all($params);

$permissions = array(
    'Is_Asst' => 4,
    'Is_GM' => 3,
    'Is_Head' => 2,
    'Is_Admin' => 1,
    'Wiki_Manager' => 5
);

/*foreach($rows as $row) {
    // migrate users
    echo "Migrating: " . $row['ID'] . '<br />';
    foreach($row as $column => $value)
    {
        if($value == 'Y') {
            echo 'Give ' . $permissions[$column] . ' permission to user.<br />';
            $sql = 'INSERT INTO permissions_users VALUES (?, ?)';
            $params = array($row['ID'], $permissions[$column]);
            Database::GetInstance()->Query($sql)->Execute($params);
        }
    }
}*/
?>

<?php if (count($rows) > 0): ?>
    <table>
        <thead>
        <tr>
            <?php foreach ($rows[0] as $header => $value): ?>
                <th>
                    <?php echo $header; ?>
                </th>
            <?php endforeach; ?>
        </tr>
        </thead>
        <?php foreach ($rows as $row): ?>
            <tr>
                <?php foreach ($row as $cell): ?>
                    <td>
                        <?php echo ($cell !== null) ? $cell : 'NULL'; ?>
                    </td>
                <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    No records
<?php endif; ?>
