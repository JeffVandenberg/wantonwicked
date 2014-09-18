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
select
	id, 
	character_name,
	total_experience - (select count(*) * 5 from log_characters AS LC WHERE LC.character_id = C.id AND LC.action_type_id = 5) AS `total_earned` 
from
	characters as C
WHERE
	total_experience - (select count(*) * 5 from log_characters AS LC WHERE LC.character_id = C.id AND LC.action_type_id = 5) < 35 
	AND C.is_sanctioned = 'Y'
	AND C.is_npc = 'N'
	AND C.city = 'Savannah'
ORDER BY
	character_name
EOQ;

$rows = Database::GetInstance()->Query($query)->All();
?>
<form method="post" enctype="multipart/form-data">
    <textarea name="character_list"></textarea>
    <input type="submit" value="Upload File" />
</form>
<table>
	<thead>
	<tr>
		<th>
		ID
		</th>
		<th>
			Name
		</th>
		<th>
			XP Earned
		</th>
		<th>
			Bonus to apply
		</th>
	</tr>
	</thead>
	<?php foreach($rows as $row): ?>
	<tr>
		<td><?php echo $row['id']; ?></td>
		<td><?php echo $row['character_name']; ?></td>
		<td><?php echo $row['total_earned']; ?></td>
		<td><?php echo 35-$row['total_earned']; ?></td>
		
	<?php endforeach; ?>
</table>