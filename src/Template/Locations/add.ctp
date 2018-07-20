<?php
/**
  * @var \App\View\AppView $this
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Locations'), ['action' => 'index']) ?></li>
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
<div class="locations form large-9 medium-8 columns content">
    <?= $this->Form->create($location) ?>
    <fieldset>
        <legend><?= __('Add Location') ?></legend>
        <?php
            echo $this->Form->control('district_id', ['options' => $districts, 'empty' => true]);
            echo $this->Form->control('location_name');
            echo $this->Form->control('location_description');
            echo $this->Form->control('location_image');
            echo $this->Form->control('is_active');
            echo $this->Form->control('created_by_id');
            echo $this->Form->control('created_on');
            echo $this->Form->control('updated_by_id');
            echo $this->Form->control('updated_on');
            echo $this->Form->control('is_private');
            echo $this->Form->control('character_id');
            echo $this->Form->control('owning_character_name');
            echo $this->Form->control('location_type_id', ['options' => $locationTypes]);
            echo $this->Form->control('location_rules');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
