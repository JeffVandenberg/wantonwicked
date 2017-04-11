<?php
$this->set('title_for_layout', 'Site Roles');
if ($mayEdit) {
    $menu['Actions']['submenu']['Add Role'] = [
        'link' => [
            'action' => 'add'
        ]
    ];
}
$this->set('menu', $menu);
?>
<div class="roles index">
    <table cellpadding="0" cellspacing="0">
        <tr>
            <th><?php echo $this->Paginator->sort('name'); ?></th>
            <th class="actions"><?php echo __('Actions'); ?></th>
        </tr>
        <?php foreach ($roles as $role): ?>
            <tr>
                <td><?php echo h($role['Role']['name']); ?>&nbsp;</td>
                <td class="actions">
                    <?php echo $this->Html->link(__('View'), array('action' => 'view', $role['Role']['id'])); ?>
                    <?php if ($mayEdit): ?>
                        <?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $role['Role']['id'])); ?>
                        <?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $role['Role']['id']), null, __('Are you sure you want to delete # {0}?', $role['Role']['id'])); ?>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
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