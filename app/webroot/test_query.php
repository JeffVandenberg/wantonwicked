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
select
    R.*,
    G.name
from
    requests AS R
    INNER JOIN groups AS G ON R.group_id = G.id
where
    R.created_by_id = 4701
ORDER BY
    R.created_on DESC
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
