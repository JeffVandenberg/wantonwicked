<?php
/**
 * Created by PhpStorm.
 * User: JeffVandenberg
 * Date: 3/30/14
 * Time: 5:09 PM
 */

use classes\core\repository\Database;

require_once('cgi-bin/start_of_page.php');


$query = <<<EOQ
select
  id,
  character_name,
  total_experience,
  sanction_date
from
  characters AS C
  INNER JOIN (
               SELECT
                 log_characters.character_id,
                 max(log_characters.created) as sanction_date
               from
                 log_characters
               where
                 action_type_id = 6
               group by
                 character_id
  ) as SD ON C.id = SD.character_id
where
  is_sanctioned = 'Y'
  and is_deleted = 'N'
  AND C.is_npc = ''
  AND sanction_date > '2015-06-20'
order by
  sanction_date desc;
EOQ;

$params = array();

$rows = Database::getInstance()->query($query)->all($params);
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
