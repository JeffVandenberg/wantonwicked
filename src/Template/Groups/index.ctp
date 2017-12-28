<?php
use App\Model\Entity\Group;
use App\View\AppView;

/* @var AppView $this */
/* @var Group[] $groups */
$this->set('title_for_layout', 'Groups');
?>

<div class="groups index">
    <?php echo $this->Html->link('New Group', ['action' => 'add'], ['class' => 'button']); ?>
    <table>
        <tr>
            <th><?php echo $this->Paginator->sort('name'); ?></th>
            <th><?php echo $this->Paginator->sort('group_type_id'); ?></th>
            <th><?php echo $this->Paginator->sort('is_deleted'); ?></th>
            <th><?php echo $this->Paginator->sort('created_by'); ?></th>
            <th class="actions"><?php echo __('Actions'); ?></th>
        </tr>
        <?php foreach ($groups as $group): ?>
            <tr>
                <td><?php echo h($group->name); ?>&nbsp;</td>
                <td>
                    <?php echo $this->Html->link($group->group_type->name, array('controller' => 'group_types', 'action' => 'view', $group->group_type->id)); ?>
                </td>
                <td><?php echo ($group->is_deleted) ? 'Yes' : 'No'; ?>&nbsp;</td>
                <td><?php echo h($group->user->username); ?>&nbsp;</td>
                <td class="actions">
                    <?php echo $this->Html->link(__('View'), array('action' => 'view', $group->id)); ?>
                    <?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $group->id)); ?>
                    <?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $group->id), ['confirm' =>  __('Are you sure you want to delete # {0}?', $group->id)]); ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <div class="paginator small callout">
        <ul class="pagination">
            <?php if ($this->Paginator->hasPrev()): ?>
                <?= $this->Paginator->first('<< ' . __('First')) ?>
                <?= $this->Paginator->prev('< ' . __('Previous')) ?>
            <?php endif; ?>
            <?= $this->Paginator->numbers() ?>
            <?php if ($this->Paginator->hasNext()): ?>
                <?= $this->Paginator->next(__('Next') . ' >') ?>
                <?= $this->Paginator->last(__('Last') . ' >>') ?>
            <?php endif; ?>
        </ul>
        <p><?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]) ?></p>
    </div>
</div>
