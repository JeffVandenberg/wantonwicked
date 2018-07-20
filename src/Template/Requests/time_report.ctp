<?php

use App\View\AppView;
use classes\core\helpers\TimeHelper;

/**
 * @var AppView $this
 * @var array $data
 */
$this->set('title_for_layout', 'Time Report');
?>
<table>
    <tr>
        <th>
            Character Type
        </th>
        <th>
            Time to First View
        </th>
        <th>
            Time to Terminal
        </th>
        <th>
            Time to Close
        </th>
    </tr>
    <?php foreach($data as $row): ?>
        <tr>
            <td>
                <?= $row['character_type']; ?>
            </td>
            <td>
                <?= TimeHelper::toHumanTime($row['first_view']); ?>
            </td>
            <td>
                <?= TimeHelper::toHumanTime($row['terminal_status']); ?>
            </td>
            <td>
                <?= TimeHelper::toHumanTime($row['closed']); ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
