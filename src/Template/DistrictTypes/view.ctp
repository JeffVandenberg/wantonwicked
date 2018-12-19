<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\DistrictType $districtType
 */
$this->set('title_for_layout', 'District Type: ' . $districtType->name);
?>
<div class="row">
    <div class="small-12 column">
        <?= $this->Html->link('<< Back', ['action' => 'index'], ['class' => 'button']); ?>
        <?php if ($isMapAdmin): ?>
            <?= $this->Html->link('Edit', ['action' => 'edit', $districtType->slug], ['class' => 'button']); ?>
        <?php endif; ?>
    </div>
    <div class="small-12 columns">
        <label>Description</label>
        <?= $districtType->description; ?>
    </div>
    <div class="small-12 columns">
        <label>Color</label>
        <div style="background-color: <?= $districtType->color; ?>">&nbsp;</div>
    </div>
</div>
