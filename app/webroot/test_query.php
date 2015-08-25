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
select * from gm_permissions;
EOQ;

//CREATE TABLE `permissions` (
//`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
// `permission_name` varchar(45) NOT NULL,
// PRIMARY KEY (`id`)
//) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1
//
//$query = <<<EOQ
//update scenes set run_by_id = 8 where slug = 'a_crow_visits'
//EOQ;


$params = array();

$rows = Database::GetInstance()->Query($query)->All($params);

$permissions = array(
    'Is_Asst' => 4,
    'Is_GM' => 3,
    'Is_Head' => 2,
    'Is_Admin' => 1,
    'Wiki_Manager' => 5
);

foreach($rows as $row) {
    // migrate users
    echo "Migrating: " . $row['ID'] . '<br />';
    foreach($row as $column => $value)
    {
        if($value == 'Y') {
            echo 'Give ' . $permissions[$column] . ' permission to user.<br />';
            $sql = 'INSERT INTO permissions_users VALUES (?, ?)';
            $params = array($row['ID'], $permissions[$column]);
            //Database::GetInstance()->Query($sql)->Execute($params);
        }
    }
}
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
