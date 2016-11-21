<?php
$this->set('title_for_layout', 'Conditions');
if ($mayEdit) {
    $menu['Actions']['submenu']['Add Condition'] = [
        'link' => [
            'action' => 'add'
        ]
    ];
}
$this->set('menu', $menu);
?>

<div class="conditions index">
    <table cellpadding="0" cellspacing="0">
        <thead>
        <tr>
            <th><?php echo $this->Paginator->sort('name'); ?></th>
            <th><?php echo $this->Paginator->sort('source'); ?></th>
            <th><?php echo $this->Paginator->sort('is_persistent'); ?></th>
            <th><?php echo $this->Paginator->sort('created_by'); ?></th>
            <th><?php echo $this->Paginator->sort('created'); ?></th>
            <th><?php echo $this->Paginator->sort('updated_by'); ?></th>
            <th><?php echo $this->Paginator->sort('updated'); ?></th>
            <th class="actions"><?php echo __('Actions'); ?></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($conditions as $condition): ?>
            <tr>
                <td><?php echo h($condition['Condition']['name']); ?>&nbsp;</td>
                <td><?php echo h($condition['Condition']['source']); ?>&nbsp;</td>
                <td><?php echo $condition['Condition']['is_persistent'] ? 'Yes' : 'no'; ?>&nbsp;</td>
                <td>
                    <?php echo $condition['CreatedBy']['username']; ?>
                </td>
                <td><?php echo h($condition['Condition']['created']); ?>&nbsp;</td>
                <td>
                    <?php echo $condition['UpdatedBy']['username']; ?>
                </td>
                <td><?php echo h($condition['Condition']['updated']); ?>&nbsp;</td>
                <td class="actions">
                    <?php echo $this->Html->link(__('View'), array('action' => 'view', $condition['Condition']['id'])); ?>
                    <?php if ($mayEdit): ?>
                        <?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $condition['Condition']['id'])); ?>
                        <?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $condition['Condition']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $condition['Condition']['id']))); ?>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <p>
        <?php
        echo $this->Paginator->counter(array(
            'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
        ));
        ?>    </p>
    <div class="paging">
        <?php
        echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
        echo $this->Paginator->numbers(array('separator' => ''));
        echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
        ?>
    </div>
</div>
