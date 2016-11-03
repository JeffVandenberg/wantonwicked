<?php
$this->set('title_for_layout', 'Add Condition');
?>
<div class="conditions form">
    <?php echo $this->Form->create('Condition'); ?>
    <?php
    echo $this->Form->input('name');
    echo $this->Form->input('source');
    echo $this->Form->input('is_persistent');
    echo $this->Form->input('description');
    echo $this->Form->input('resolution');
    echo $this->Form->input('beat');
    ?>
    <?php echo $this->Form->end(__('Submit')); ?>
</div>
