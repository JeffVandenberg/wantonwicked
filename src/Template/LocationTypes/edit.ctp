<?php

use App\Model\Entity\LocationType;

/**
 * @var \App\View\AppView $this
 * @var LocationType $locationType
 */

$this->set('title_for_layout', 'Edit: ' . $locationType->name)
?>

<div class="locationTypes form small-12 columns content">
    <?= $this->Form->create($locationType, ['type' => 'file']) ?>
    <?php
    echo $this->Form->control('name');
    echo $this->Form->control('description');
    ?>
    <div>
        <div class="float-right">
            <?= $this->Html->image($locationType->icon); ?>
        </div>
        <label>Icon</label>
        <?= $this->Form->file('icon', ['required' => !$locationType->icon]); ?>
    </div>
    <div class="text-center">
        <button type="submit" value="save" class="button" name="action">Save</button>
        <button type="submit" value="cancel" class="button" name="action">Cancel</button>
    </div>
    <?= $this->Form->end() ?>
</div>
