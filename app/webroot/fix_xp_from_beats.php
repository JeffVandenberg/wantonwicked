<?php
/**
 * Created by PhpStorm.
 * User: JeffVandenberg
 * Date: 3/8/2017
 * Time: 8:31 AM
 */

use classes\character\nwod2\SheetService;
use classes\core\helpers\Request;
use classes\core\repository\Database;

require_once 'cgi-bin/start_of_page.php';

$db = Database::getInstance();
$sheetService = new SheetService();

$sql = <<<SQL
SELECT
  character_id,
  c.character_name,
  sum(bt.number_of_beats) * .2 AS beat_xp,
  c.current_experience,
  c.total_experience,
  cast((abs(sum(bt.number_of_beats) * .2)-floor(abs(sum(bt.number_of_beats) * .2)))*10 - (abs(c.current_experience)-floor(abs(c.current_experience)))*10 AS SIGNED) AS xp_off
FROM
  character_beats AS cb
  LEFT JOIN characters AS c ON cb.character_id = c.id
  LEFT JOIN beat_types AS bt ON cb.beat_type_id = bt.id
WHERE
  cb.beat_status_id = 3
GROUP BY
  character_id;
SQL;

foreach ($db->query($sql)->all() as $row) {
    if ($row['xp_off'] != 0) {
        $xpOff = $row['xp_off'] / 10;
        if ($xpOff < 0) {
            $xpOff += 1;
        }
        echo $row['character_name'] . ' (' . $row['character_id'] . ') needs ' . $xpOff . ' ';
        if (Request::isPost()) {
            $sheetService->grantXpToCharacter($row['character_id'], $xpOff, 'Manually awarding ' . $xpOff . ' to fix XP', 8);
            echo ' - awarded XP ';
        }
        echo '<br/>';
    }
}
?>
<form method="post">
    Fix the above issues?
    <input type="submit" value="Fix" name="action"/>
</form>
