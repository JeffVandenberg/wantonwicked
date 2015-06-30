<?php
/**
 * Created by PhpStorm.
 * User: JeffVandenberg
 * Date: 3/30/14
 * Time: 5:09 PM
 */

use classes\core\helpers\Request;
use classes\core\repository\Database;
use classes\log\data\ActionType;

require_once('cgi-bin/start_of_page.php');


$query = <<<EOQ
EXPLAIN
SELECT
    DISTINCT
    id
FROM
    characters AS C
    LEFT JOIN (
        SELECT
            LC.character_id,
            count(*) AS `rows`
        FROM
            log_characters AS LC
        WHERE
            LC.created >= ?
            AND LC.action_type_id IN (?, ?)
		GROUP BY
			LC.character_id
    ) AS A ON C.id = A.character_id
WHERE
    C.is_sanctioned = 'Y'
    AND C.is_npc = 'N'
	AND A.rows IS NULL
EOQ;

$params = array(
	date('Y-m-d', mktime(0, 0, 0, date('m') - 0, date('d') - 1, date('Y'))),
	ActionType::Login,
	ActionType::Sanctioned
);
$rows = Database::GetInstance()->Query($query)->All($params);

?>

<?php if(count($rows) > 0): ?>
<table>
	<thead>
	<tr>
		<?php foreach($rows[0] as $header => $value): ?>
			<th>
				<?php echo $header; ?>
			</th>
		<?php endforeach; ?>
	</tr>
	</thead>
	<?php foreach($rows as $row): ?>
	<tr>
		<?php foreach($row as $cell): ?>
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
