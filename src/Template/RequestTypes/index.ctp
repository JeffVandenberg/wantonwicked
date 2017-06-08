<?php
/**
 * @var \App\View\AppView $this
 */
$this->set('title_for_layout', 'Request Types');
?>
<?php echo $this->Html->link('New Request Type', ['action' => 'add'], ['class' => 'button']); ?>
<table cellpadding="0" cellspacing="0" class="stack">
    <thead>
    <tr>
        <th scope="col"><?= $this->Paginator->sort('name') ?></th>
        <th scope="col" class="actions"><?= __('Actions') ?></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($requestTypes as $requestType): ?>
        <tr>
            <td><?= h($requestType->name) ?></td>
            <td class="actions">
                <?= $this->Html->link(__('View'), ['action' => 'view', $requestType->id]) ?>
                <?= $this->Html->link(__('Edit'), ['action' => 'edit', $requestType->id]) ?>
                <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $requestType->id], ['confirm' => __('Are you sure you want to delete # {0}?', $requestType->id)]) ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
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
