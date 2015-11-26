<?php /* @var View $this */ ?>
<?php $this->set('title_for_layout', 'Report Player Interest'); ?>
<?php /* @var array $report */ ?>
<?php
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
                <?php echo $row['U']['username']; ?>
            </td>
            <td>
                <?php echo $this->Html->link($row['C']['character_name'], '/view_sheet.php?action=st_view_xp&view_character_id=' . $row['C']['id']); ?>
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
