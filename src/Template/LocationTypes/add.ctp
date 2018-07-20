<?php
/**
 * @var \App\View\AppView $this
 * @var LocationType $locationType
 */

use App\Model\Entity\LocationType;

$this->set('title_for_layout', 'Add Location Type')
?>
<div class="row">
    <div class="locationTypes form small-12 columns content">
        <?= $this->Form->create($locationType, ['type' => 'file']) ?>
        <?php
        echo $this->Form->control('name');
        echo $this->Form->control('description');
        echo $this->Form->file('icon');
        ?>
        <div class="text-center">
            <button type="submit" value="save" class="button" name="action">Save</button>
            <button type="submit" value="cancel" class="button" name="action">Cancel</button>
        </div>
        <?= $this->Form->end() ?>
    </div>
</div>
