<?php
$this->set('title_for_layout', 'Edit Beat Type: ' . $this->request->data['BeatType']['name']);
?>
<div class="beatTypes form">
    <?php echo $this->Form->create('BeatType'); ?>
    <div class="row align-middle">
        <div class="small-12 medium-6 columns">
            <?php echo $this->Form->input('name'); ?>
        </div>
        <div class="small-6 medium-3 columns">
            <?php echo $this->Form->input('number_of_beats'); ?>
        </div>
        <div class="small-6 medium-3 columns" style="text-align: center;">
            <?php echo $this->Form->input('admin_only', ['div' => false, 'label' => 'Staff Only']); ?>
        </div>
        <div class="small-12 columns">
            <?php echo $this->Form->input('id'); ?>
            <button class="button" name="action" value="save">Save</button>
            <button class="button" name="action" value="cancel">Cancel</button>
        </div>
    </div>
    <?php echo $this->Form->end(); ?>
</div>
