<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\LocationType[]|\Cake\Collection\CollectionInterface $locationTypes
 * @var bool $isMapAdmin
 */
$this->set('title_for_layout', 'Location Types')
?>
<div class="row">
    <?php if ($isMapAdmin): ?>
        <div class="small-12 column">
            <?= $this->Html->link('New', ['action' => 'add'], ['class' => 'button']); ?>
        </div>
    <?php endif; ?>
    <div class="locationTypes index small-12 columns content">
        <table cellpadding="0" cellspacing="0">
            <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('name') ?></th>
                <th scope="col"><?= $this->Paginator->sort('icon') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($locationTypes as $locationType): ?>
                <tr>
                    <td><?= $this->Html->link($locationType->name, ['action' => 'view', $locationType->slug]) ?></td>
                    <td><?= $this->Html->image($locationType->icon) ?></td>
                    <?php if ($isMapAdmin): ?>
                        <td class="actions">
                            <?= $this->Html->link(__('Edit'), ['action' => 'edit', $locationType->id]) ?>
                            <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $locationType->id], ['confirm' => __('Are you sure you want to delete # {0}?', $locationType->id)]) ?>
                        </td>
                    <?php endif; ?>
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
    </div>
</div>
