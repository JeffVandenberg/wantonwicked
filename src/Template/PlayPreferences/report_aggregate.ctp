<?php
use App\View\AppView;

/* @var AppView $this */
/* @var array $report */

$this->set('title_for_layout', 'Aggregate Player Preference Report');
?>

<table>
    <thead>
    <tr>
        <th>
            Name
        </th>
        <th>
            Percent Interested
        </th>
    </tr>
    </thead>
    <?php foreach($report as $row): ?>
        <tr>
            <td>
                <?php echo $row['name']; ?>
            </td>
            <td>
                <?php echo $row['percentage']; ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
