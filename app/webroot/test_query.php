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
SELECT
    R.*,
    RT.name as request_type_name,
    RS.name as request_status_name,
    UB.username AS updated_by_username
FROM
    requests as R
    LEFT JOIN request_characters AS RC ON R.id = RC.request_id
    LEFT JOIN request_types AS RT ON R.request_type_id = RT.id
    LEFT JOIN request_statuses AS RS ON R.request_status_id = RS.id
    LEFT JOIN phpbb_users AS UB ON R.updated_by_id = UB.user_id
WHERE
    RC.character_id = 12443
    AND RC.is_primary = 1

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
