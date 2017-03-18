<?php
/**
 * Created by PhpStorm.
 * User: JeffVandenberg
 * Date: 3/30/14
 * Time: 5:09 PM
 */

use classes\core\repository\Database;

require_once('cgi-bin/start_of_page.php');

$filterDate = date('Y-m-d', strtotime('3 months ago'));
$query = <<<EOQ
select
  U.username,
  character_type,
  count(*) AS type_views,
  (
    SELECT
      count(*)
    FROM
      log_characters AS LC2
    WHERE
      action_type_id = 1
      AND LC2.created_by_id = LC.created_by_id
      AND LC2.created > '$filterDate'
  ) as total_views,
  (
    count(*) /
    (
    SELECT
      count(*)
    FROM
      log_characters AS LC2
    WHERE
      action_type_id = 1
      AND LC2.created_by_id = LC.created_by_id
      AND LC2.created > '$filterDate'
    )
  ) * 100 as percentage
from
  log_characters AS LC
  LEFT JOIN characters AS C ON LC.character_id = C.id
  LEFT JOIN phpbb_users as U ON LC.created_by_id = U.user_id
WHERE
  created_by_id IN (
    SELECT
      DISTINCT user_id
    FROM
      permissions_users
  )
  AND action_type_id = 1
  AND LC.created > '$filterDate'
GROUP BY
  U.username,
  C.character_type;
EOQ;

$params = array();

$rows = Database::getInstance()->query($query)->all($params);
?>

<style>
    table {
        border-collapse: collapse;
        border: groove 10px #500;
    }
    th {
        font-weight: bold;
        text-transform: uppercase;
        background-color: #daa;
    }
    table tr:nth-child(even) td {
        background-color: #BBBBBB;
    }
    td {
        padding: 3px;
    }
</style>
<h3>Activity since: <?php echo $filterDate; ?></h3>
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
