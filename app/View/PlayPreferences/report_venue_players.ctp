<?php /* @var View $this */ ?>
<?php $this->set('title_for_layout', 'Report Player Interest'); ?>
<?php /* @var array $report */ ?>
<h3>
    Venue: <?php echo $venue; ?>
    Play Preference: <?Php echo $playPreferenceName; ?>
</h3>
<table>
    <thead>
    <tr>
        <th>
            Venue
        </th>
        <th>
            Preference
        </th>
        <th>
            Percent Interested
        </th>
        <th>

        </th>
    </tr>
    </thead>
    <?php foreach($report as $row): ?>
        <tr>
            <td>
                <?php echo $this->Html->link($row['C']['character_name'], '/view_sheet.php?action=st_view_xp&view_character_id=' . $row['C']['id']); ?>
            </td>
            <td>
                <?php echo $row['U']['username']; ?>
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
