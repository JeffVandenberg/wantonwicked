<?php
use App\Model\Entity\BeatType;
use App\View\AppView;

/* @var AppView $this */
/* @var BeatType $beatType */
$this->set('title_for_layout', 'Edit Beat Type: ' . $beatType->name);
?>
<div class="beatTypes form">
    <?php echo $this->Form->create($beatType); ?>
    <div class="row align-middle">
        <div class="small-12 medium-6 columns">
            <?php echo $this->Form->control('name'); ?>
        </div>
        <div class="small-6 medium-3 columns">
            <?php echo $this->Form->control('number_of_beats'); ?>
        </div>
        <div class="small-6 medium-3 columns">
            <?php echo $this->Form->control('may_rollover', [
                'div' => false,
                'label' => 'May Rollover',
                'type' => 'checkbox',
            ]); ?>
        </div>
        <div class="small-6 medium-3 columns">
            <?php echo $this->Form->control('admin_only', [
                'div' => false,
                'label' => 'Staff Only',
                'type' => 'checkbox',
            ]); ?>
        </div>
        <div class="small-12 columns">
            <?php echo $this->Form->hidden('id'); ?>
            <button class="button" name="action" value="save">Save</button>
            <button class="button" name="action" value="cancel">Cancel</button>
        </div>
    </div>
    <?php echo $this->Form->end(); ?>
</div>
