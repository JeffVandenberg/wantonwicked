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
  floor(total_experience / 20) * 20 as `bracket`,
  count(*) as `characters`
FROM
  characters
WHERE
  is_sanctioned = 'Y'
  AND is_npc = 'N'
  AND characters.is_deleted = 'N'
GROUP BY
  bracket
EOQ;

$params = array(
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
