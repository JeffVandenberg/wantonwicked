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
SELECT
	UG.user_id,
	U.username,
	C.character_name
FROM
	phpbb_user_group AS UG
	INNER JOIN phpbb_users as U ON UG.user_id = U.user_Id
	LEFT JOIN characters as C ON U.user_id = C.user_id
WHERE
	UG.group_id = ?
	AND C.is_sanctioned = 'Y'
ORDER BY
	U.username,
	C.character_name
EOQ;

$params = array(1729);
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
