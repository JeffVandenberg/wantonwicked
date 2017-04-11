<?php
$this->set('title_for_layout', 'Scenes tagged with ' . $tag);
?>

<table>
    <thead>
    <tr>
        <th>Scene</th>
        <th>Summary</th>
        <th>Scheduled For</th>
    </tr>
    </thead>
    <?php foreach($scenes as $scene): ?>
        <tr>
            <td>
                <?php echo $this->Html->link($scene['Scene']['name'], ['action' => 'view', $scene['Scene']['slug']]); ?>
            </td>
            <td><?php echo $scene['Scene']['summary']; ?></td>
            <td>
                <?php echo date('l, Y-m-d g:i A', strtotime($scene['Scene']['run_on_date'])); ?>
            </td>
            <td>
            </td>
        </tr>
    <?php endforeach; ?>
</table>