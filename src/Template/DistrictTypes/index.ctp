<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\DistrictType[]|\Cake\Collection\CollectionInterface $districtTypes
 */

$this->set('title_for_layout', 'District Types');
?>

<div class="rows">
    <div class="small-12 column">
        <?= $this->Html->link('<< Back', ['controller' => 'map', 'action' => 'index'], ['class' => 'button']); ?>
        <?php if ($isMapAdmin): ?>
            <?= $this->Html->link('New', ['action' => 'add'], ['class' => 'button']); ?>
        <?php endif; ?>
    </div>
    <div class="districtTypes index small-12 column content">
        <table cellpadding="0" cellspacing="0">
            <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('name') ?></th>
                <th scope="col">Color</th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($districtTypes as $districtType): ?>
                <tr>
                    <td><?= $this->Html->link($districtType->name, ['action' => 'view', $districtType->slug]) ?></td>
                    <td style="background-color: <?= $districtType->color; ?>">&nbsp;</td>
                    <td class="actions">
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $districtType->name]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $districtType->id], ['confirm' => __('Are you sure you want to delete {0}?', $districtType->name)]) ?>
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
    </div>
</div>
