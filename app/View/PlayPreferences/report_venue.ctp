<?php /* @var View $this */ ?>
<?php $this->set('title_for_layout', 'Venue Player Preference Report'); ?>
<?php /* @var array $report */ ?>

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
    </tr>
    </thead>
    <?php foreach($report as $row): ?>
        <tr>
            <td>
                <?php echo $row['C']['character_type']; ?>
            </td>
            <td>
                <?php echo $row['PP']['name']; ?>
            </td>
            <td>
                <?php if($row[0]['total'] > 0): ?>
                    <?php echo (int) (($row[0]['hits'] / $row[0]['total']) * 100); ?>
                    (
                    <?php echo $row[0]['total']; ?>
                    Responses
                    )
                <?php else: ?>
                    No Responses
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>