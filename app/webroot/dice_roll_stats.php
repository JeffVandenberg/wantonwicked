<?php
use classes\core\repository\Database;

/**
 * Created by PhpStorm.
 * User: jvandenberg
 * Date: 9/18/13
 * Time: 12:34 PM
 */

include 'cgi-bin/start_of_page.php';

$query = <<<EOQ
SELECT
    D1.dice,
    (
    SELECT
        COUNT(*)
    FROM
        wod_dierolls AS D2
    WHERE
        D2.dice = D1.dice
        AND D2.note IN ('Success', 'Exceptional Success')
    ) as num_of_successes,
    (
    SELECT
        COUNT(*)
    FROM
        wod_dierolls AS D3
    WHERE
        D3.dice = D1.dice
    ) AS num_of_rolls

FROM
    wod_dierolls AS D1
WHERE
    D1.dice <= 20
GROUP BY
    D1.dice
EOQ;

$db = new Database();


$rows = $db->query($query)->all();
?>

<table>
    <tr>
        <td>
            Dice
        </td>
        <td>
            # Successes
        </td>
        <td>
            # Rolls
        </td>
        <td>
            Success Rate
        </td>
    </tr>
    <?php foreach($rows as $row): ?>
        <tr>
            <td>
                <?php echo $row['dice']; ?>
            </td>
            <td>
                <?php echo $row['num_of_successes']; ?>
            </td>
            <td>
                <?php echo $row['num_of_rolls']; ?>
            </td>
            <td>
                <?php echo round(($row['num_of_successes'] / $row['num_of_rolls']) * 100, 2); ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>