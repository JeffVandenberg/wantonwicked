<?php
use App\View\AppView;

/* @var AppView $this */
/* @var array $report */
/* @var string $venue */
/* @var string $playPreferenceName */

$this->set('title_for_layout', 'Report Player Interest');
$menu['Actions']['submenu']['Back'] = [
    'link' => [
        'action' => 'report_venue',
        $venue
    ]
];
$this->set('menu', $menu);
?>
<div style="padding:10px 0;text-align: center;">
    <strong>Venue:</strong> <?php echo $venue; ?>
    <strong>Play Preference:</strong> <?Php echo $playPreferenceName; ?>
</div>
<table>
    <thead>
    <tr>
        <th>
            Username
        </th>
        <th>
            Character
        </th>
        <th>
            Actions
        </th>
    </tr>
    </thead>
    <?php foreach($report as $row): ?>
        <tr>
            <td>
                <?php echo $row['username']; ?>
            </td>
            <td>
                <?php echo $this->Html->link($row['character_name'], '/characters/stView/' . $row['character_id']); ?>
            </td>
            <td>
                View Player Response
<!--                --><?php //echo $this->Html->link('View Player Response', [
//                    'action' => 'report_venue_players',
//                    $row['C']['character_type'],
//                    $row['PP']['name']
//                ]); ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
