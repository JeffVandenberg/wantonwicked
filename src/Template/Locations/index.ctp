<?php
/**
  * @var \App\View\AppView $this
  * @var \App\Model\Entity\Location[]|\Cake\Collection\CollectionInterface $locations
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Location'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Districts'), ['controller' => 'Districts', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New District'), ['controller' => 'Districts', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Location Types'), ['controller' => 'LocationTypes', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Location Type'), ['controller' => 'LocationTypes', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Characters'), ['controller' => 'Characters', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Character'), ['controller' => 'Characters', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Location Traits'), ['controller' => 'LocationTraits', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Location Trait'), ['controller' => 'LocationTraits', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="locations index large-9 medium-8 columns content">
    <h3><?= __('Locations') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('district_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('location_name') ?></th>
                <th scope="col"><?= $this->Paginator->sort('location_image') ?></th>
                <th scope="col"><?= $this->Paginator->sort('is_active') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created_by_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created_on') ?></th>
                <th scope="col"><?= $this->Paginator->sort('updated_by_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('updated_on') ?></th>
                <th scope="col"><?= $this->Paginator->sort('is_private') ?></th>
                <th scope="col"><?= $this->Paginator->sort('character_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('owning_character_name') ?></th>
                <th scope="col"><?= $this->Paginator->sort('location_type_id') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($locations as $location): ?>
            <tr>
                <td><?= $this->Number->format($location->id) ?></td>
                <td><?= $location->has('district') ? $this->Html->link($location->district->id, ['controller' => 'Districts', 'action' => 'view', $location->district->id]) : '' ?></td>
                <td><?= h($location->location_name) ?></td>
                <td><?= h($location->location_image) ?></td>
                <td><?= h($location->is_active) ?></td>
                <td><?= $this->Number->format($location->created_by_id) ?></td>
                <td><?= h($location->created_on) ?></td>
                <td><?= $this->Number->format($location->updated_by_id) ?></td>
                <td><?= h($location->updated_on) ?></td>
                <td><?= h($location->is_private) ?></td>
                <td><?= $this->Number->format($location->character_id) ?></td>
                <td><?= h($location->owning_character_name) ?></td>
                <td><?= $location->has('location_type') ? $this->Html->link($location->location_type->name, ['controller' => 'LocationTypes', 'action' => 'view', $location->location_type->id]) : '' ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $location->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $location->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $location->id], ['confirm' => __('Are you sure you want to delete # {0}?', $location->id)]) ?>
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
