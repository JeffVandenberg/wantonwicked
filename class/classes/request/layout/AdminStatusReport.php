<?php
/**
 * Created by PhpStorm.
 * User: jvandenberg
 * Date: 9/13/2015
 * Time: 9:19 PM
 */

namespace classes\request\layout;


class AdminStatusReport
{
    public function generateGroupOutput($lastGroup, $lastTotal, $groupRows)
    {
        ob_start();
        ?>
        <table>
            <thead>
            <tr>
                <th style="width: 33%;">
                    Group: <?php echo $lastGroup; ?>
                </th>
                <th style="width: 67%;">
                    Total: <?php echo $lastTotal; ?>
                </th>
            </tr>
            <tr>
                <th>
                    Status
                </th>
                <th>
                    Total
                </th>
            </tr>
            </thead>
            <?php foreach ($groupRows as $row): ?>
                <tr>
                    <td>
                        <?php echo $row['status_name']; ?>
                    </td>
                    <td>
                        <?php echo $row['total']; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
        <?php
        return ob_get_clean();
    }
}