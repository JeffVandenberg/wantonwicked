<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\District[]|\Cake\Collection\CollectionInterface $districts
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New District'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Cities'), ['controller' => 'Cities', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New City'), ['controller' => 'Cities', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Realities'), ['controller' => 'Realities', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Reality'), ['controller' => 'Realities', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List District Types'), ['controller' => 'DistrictTypes', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New District Type'), ['controller' => 'DistrictTypes', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Locations'), ['controller' => 'Locations', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Location'), ['controller' => 'Locations', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="districts index large-9 medium-8 columns content">
    <h3><?= __('Districts') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('city_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('district_name') ?></th>
                <th scope="col"><?= $this->Paginator->sort('district_image') ?></th>
                <th scope="col"><?= $this->Paginator->sort('is_active') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created_by_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created_on') ?></th>
                <th scope="col"><?= $this->Paginator->sort('updated_by_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('updated_on') ?></th>
                <th scope="col"><?= $this->Paginator->sort('reality_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('district_type_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('slug') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($districts as $district): ?>
            <tr>
                <td><?= $this->Number->format($district->id) ?></td>
                <td><?= $district->has('city') ? $this->Html->link($district->city->id, ['controller' => 'Cities', 'action' => 'view', $district->city->id]) : '' ?></td>
                <td><?= h($district->district_name) ?></td>
                <td><?= h($district->district_image) ?></td>
                <td><?= h($district->is_active) ?></td>
                <td><?= $this->Number->format($district->created_by_id) ?></td>
                <td><?= h($district->created_on) ?></td>
                <td><?= $this->Number->format($district->updated_by_id) ?></td>
                <td><?= h($district->updated_on) ?></td>
                <td><?= $district->has('reality') ? $this->Html->link($district->reality->name, ['controller' => 'Realities', 'action' => 'view', $district->reality->id]) : '' ?></td>
                <td><?= $district->has('district_type') ? $this->Html->link($district->district_type->name, ['controller' => 'DistrictTypes', 'action' => 'view', $district->district_type->id]) : '' ?></td>
                <td><?= h($district->slug) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $district->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $district->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $district->id], ['confirm' => __('Are you sure you want to delete # {0}?', $district->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]) ?></p>
    </div>
</div>
