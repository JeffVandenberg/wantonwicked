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
EXPLAIN SELECT t.*, p.root_level, p.message_time, p.message_subject, p.icon_id, p.to_address, p.message_attachment, p.bcc_address, u.username, u.username_clean, u.user_colour, p.message_reported FROM phpbb_privmsgs_to t, phpbb_privmsgs p, phpbb_users u WHERE t.user_id = 8 AND p.author_id = u.user_id AND t.folder_id = 0 AND t.msg_id = p.msg_id ORDER BY p.message_time DESC
EOQ;

//$query = <<<EOQ
//update scenes set run_by_id = 8 where slug = 'a_crow_visits'
//EOQ;


$params = array();

$rows = Database::GetInstance()->Query($query)->All($params);

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
                        <?php echo $cell; ?>
                    </td>
                <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    No records
<?php endif; ?>
