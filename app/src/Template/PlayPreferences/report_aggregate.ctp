<?php /* @var View $this */ ?>
<?php $this->set('title_for_layout', 'Aggregate Player Preference Report'); ?>
<?php /* @var array $report */ ?>

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
                <?php echo $row['PP']['name']; ?>
            </td>
            <td>
                <?php echo $row[0]['percentage']; ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>