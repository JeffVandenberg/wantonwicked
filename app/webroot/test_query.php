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
`Scene`.`id`,
`Scene`.`name`,
`Scene`.`run_on_date`,
`Scene`.`summary`,
`Scene`.`scene_status_id`,
`Scene`.run_by_id,
`Character`.user_id
# `Scene`.`slug`,
# `SceneStatus`.`name`,
# `CreatedBy`.`username`,
# `UpdatedBy`.`username`,
# `RunBy`.`username`,
# `RunBy`.`user_id`,
# `CreatedBy`.`user_id`,
# `UpdatedBy`.`user_id`,
# `SceneStatus`.`id`
FROM
  `gamingsandbox_wanton`.`scenes` AS `Scene`
  LEFT JOIN `gamingsandbox_wanton`.`scene_characters` AS `SceneCharacter` ON (`Scene`.`id` = `SceneCharacter`.`scene_id`)
  LEFT JOIN `gamingsandbox_wanton`.`characters` AS `Character` ON (`SceneCharacter`.`character_id` = `Character`.`id`)
  LEFT JOIN `gamingsandbox_wanton`.`phpbb_users` AS `RunBy` ON (`Scene`.`run_by_id` = `RunBy`.`user_id`)
  LEFT JOIN `gamingsandbox_wanton`.`phpbb_users` AS `CreatedBy` ON (`Scene`.`created_by_id` = `CreatedBy`.`user_id`)
  LEFT JOIN `gamingsandbox_wanton`.`phpbb_users` AS `UpdatedBy` ON (`Scene`.`updated_by_id` = `UpdatedBy`.`user_id`)
  LEFT JOIN `gamingsandbox_wanton`.`scene_statuses` AS `SceneStatus` ON (`Scene`.`scene_status_id` = `SceneStatus`.`id`)
WHERE
  1 = 1
  AND `Scene`.`scene_status_id` != 3
#   AND (
#     (`Scene`.`run_by_id` = 8)
#     OR
#     (`Character`.`user_id` = '8')
#   )
ORDER BY
  `Scene`.`run_on_date` ASC
#LIMIT 20
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
