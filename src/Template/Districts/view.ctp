<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\District $district
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit District'), ['action' => 'edit', $district->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete District'), ['action' => 'delete', $district->id], ['confirm' => __('Are you sure you want to delete # {0}?', $district->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Districts'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New District'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Cities'), ['controller' => 'Cities', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New City'), ['controller' => 'Cities', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Realities'), ['controller' => 'Realities', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Reality'), ['controller' => 'Realities', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List District Types'), ['controller' => 'DistrictTypes', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New District Type'), ['controller' => 'DistrictTypes', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Locations'), ['controller' => 'Locations', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Location'), ['controller' => 'Locations', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="districts view large-9 medium-8 columns content">
    <h3><?= h($district->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('City') ?></th>
            <td><?= $district->has('city') ? $this->Html->link($district->city->id, ['controller' => 'Cities', 'action' => 'view', $district->city->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('District Name') ?></th>
            <td><?= h($district->district_name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('District Image') ?></th>
            <td><?= h($district->district_image) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Reality') ?></th>
            <td><?= $district->has('reality') ? $this->Html->link($district->reality->name, ['controller' => 'Realities', 'action' => 'view', $district->reality->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('District Type') ?></th>
            <td><?= $district->has('district_type') ? $this->Html->link($district->district_type->name, ['controller' => 'DistrictTypes', 'action' => 'view', $district->district_type->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Slug') ?></th>
            <td><?= h($district->slug) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($district->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created By Id') ?></th>
            <td><?= $this->Number->format($district->created_by_id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Updated By Id') ?></th>
            <td><?= $this->Number->format($district->updated_by_id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created On') ?></th>
            <td><?= h($district->created_on) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Updated On') ?></th>
            <td><?= h($district->updated_on) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Is Active') ?></th>
            <td><?= $district->is_active ? __('Yes') : __('No'); ?></td>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('District Description') ?></h4>
        <?= $this->Text->autoParagraph(h($district->district_description)); ?>
    </div>
    <div class="related">
        <h4><?= __('Related Locations') ?></h4>
        <?php if (!empty($district->locations)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('District Id') ?></th>
                <th scope="col"><?= __('Location Name') ?></th>
                <th scope="col"><?= __('Location Description') ?></th>
                <th scope="col"><?= __('Location Image') ?></th>
                <th scope="col"><?= __('Is Active') ?></th>
                <th scope="col"><?= __('Created By Id') ?></th>
                <th scope="col"><?= __('Created On') ?></th>
                <th scope="col"><?= __('Updated By Id') ?></th>
                <th scope="col"><?= __('Updated On') ?></th>
                <th scope="col"><?= __('Is Private') ?></th>
                <th scope="col"><?= __('Character Id') ?></th>
                <th scope="col"><?= __('Owning Character Name') ?></th>
                <th scope="col"><?= __('Location Type Id') ?></th>
                <th scope="col"><?= __('Location Rules') ?></th>
                <th scope="col"><?= __('Point') ?></th>
                <th scope="col"><?= __('Slug') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($district->locations as $locations): ?>
            <tr>
                <td><?= h($locations->id) ?></td>
                <td><?= h($locations->district_id) ?></td>
                <td><?= h($locations->location_name) ?></td>
                <td><?= h($locations->location_description) ?></td>
                <td><?= h($locations->location_image) ?></td>
                <td><?= h($locations->is_active) ?></td>
                <td><?= h($locations->created_by_id) ?></td>
                <td><?= h($locations->created_on) ?></td>
                <td><?= h($locations->updated_by_id) ?></td>
                <td><?= h($locations->updated_on) ?></td>
                <td><?= h($locations->is_private) ?></td>
                <td><?= h($locations->character_id) ?></td>
                <td><?= h($locations->owning_character_name) ?></td>
                <td><?= h($locations->location_type_id) ?></td>
                <td><?= h($locations->location_rules) ?></td>
                <td><?= h($locations->point) ?></td>
                <td><?= h($locations->slug) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Locations', 'action' => 'view', $locations->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Locations', 'action' => 'edit', $locations->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Locations', 'action' => 'delete', $locations->id], ['confirm' => __('Are you sure you want to delete # {0}?', $locations->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>
