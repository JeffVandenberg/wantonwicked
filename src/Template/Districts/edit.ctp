<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\District $district
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $district->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $district->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Districts'), ['action' => 'index']) ?></li>
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
<div class="districts form large-9 medium-8 columns content">
    <?= $this->Form->create($district) ?>
    <fieldset>
        <legend><?= __('Edit District') ?></legend>
        <?php
            echo $this->Form->control('city_id', ['options' => $cities]);
            echo $this->Form->control('district_name');
            echo $this->Form->control('district_description');
            echo $this->Form->control('district_image');
            echo $this->Form->control('is_active');
            echo $this->Form->control('created_by_id');
            echo $this->Form->control('created_on');
            echo $this->Form->control('updated_by_id');
            echo $this->Form->control('updated_on');
            echo $this->Form->control('reality_id', ['options' => $realities]);
            echo $this->Form->control('district_type_id', ['options' => $districtTypes]);
            echo $this->Form->control('slug');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
