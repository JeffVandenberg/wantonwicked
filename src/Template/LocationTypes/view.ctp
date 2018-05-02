<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\LocationType $locationType
 * @var bool $isMapAdmin
 */

$this->set('title_for_layout', $locationType->name);
?>
<div class="row">
    <div class="small-12">
        <?= $this->Html->link('<< Back', ['action' => 'index'], ['class' => 'button']); ?>
        <?php if ($isMapAdmin): ?>
            <?= $this->Html->link('Edit', ['action' => 'edit', $locationType->slug], ['class' => 'button']); ?>
        <?php endif; ?>
    </div>
    <div class="small-12 columns">
        <label>Icon</label>
        <?= $this->Html->image($locationType->icon); ?>
    </div>
    <div class="small-12 columns">
        <label>Description</label>
        <?= $locationType->description; ?>
    </div>
</div>
