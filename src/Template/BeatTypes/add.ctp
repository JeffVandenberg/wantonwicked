<?php
$this->set('title_for_layout', 'Add Beat Type');
?>
<div class="beatTypes form">
<?php echo $this->Form->create('BeatType'); ?>
    <div class="row align-middle">
        <div class="small-12 medium-6 column">
            <?php echo $this->Form->input('name'); ?>
        </div>
        <div class="small-6 medium-3 column">
            <?php echo $this->Form->input('number_of_beats'); ?>
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
        <div class="small-12 column">
            <button class="button" name="action" value="save">Save</button>
            <button class="button" name="action" value="cancel">Cancel</button>
        </div>
    </div>
<?php echo $this->Form->end(); ?>
</div>
