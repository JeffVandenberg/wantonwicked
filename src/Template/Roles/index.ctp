<?php
use App\Model\Entity\Role;
use App\View\AppView;

/* @var AppView $this */
/* @var bool $mayEdit */
/* @var Role[] $roles */

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
                <td><?php echo h($role->name); ?>&nbsp;</td>
                <td class="actions">
                    <?php echo $this->Html->link(__('View'), array('action' => 'view', $role->id)); ?>
                    <?php if ($mayEdit): ?>
                        <?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $role->id)); ?>
                    <?php endif; ?>
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
