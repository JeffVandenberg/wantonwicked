<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\DistrictType $districtType
 */
$this->set('title_for_layout', 'Add District Type');
?>
<?= $this->Form->create($districtType) ?>
<div class="row">
    <div class="small-12 columns"><?= $this->Form->control('name'); ?></div>
    <div class="small-12 columns"><?= $this->Form->control('description'); ?></div>
    <div class="small-12 columns"><?= $this->Form->control('color', ['type' => 'color']); ?></div>
    <div class="small-12 columns text-center">
        <button type="submit" value="save" class="button" name="action">Save</button>
        <button type="submit" value="cancel" class="button" name="action">Cancel</button>
    </div>
</div>
<?= $this->Form->end() ?>
