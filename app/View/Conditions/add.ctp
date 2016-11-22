<?php
$this->set('title_for_layout', 'Add Condition');
?>
<div class="conditions form">
    <?php echo $this->Form->create('Condition'); ?>
    <div class="row">
        <div class="column small-12">
            <?php echo $this->Form->input('name', ['placeholder' => 'Condition Name', 'label' => false]); ?>
        </div>
    </div>
    <div class="row">
        <div class="column small-12 medium-6">
            <?php echo $this->Form->input('source', ['placeholder' => 'Source (Book Name or Custom)', 'label' => false]); ?>
        </div>
        <div class="column small-12 medium-6">
            <?php echo $this->Form->input('is_persistent', ['label' => 'Persistent']); ?>
        </div>
    </div>
    <div class="row">
        <div class="column small-12">
            <?php echo $this->Form->input('description', [
                    'class' => 'tinymce-textarea',
                    'placeholder' => 'Description',
                    'label' => false,
                ]
            ); ?>
        </div>
    </div>
    <div class="row">
        <div class="column small-12">
            <?php echo $this->Form->input('resolution', ['placeholder' => 'How to resolve the Condition', 'label' => false]); ?>
        </div>
    </div>
    <div class="row">
        <div class="column small-12">
            <?php echo $this->Form->input('beat', ['placeholder' => 'How to earn a beat', 'label' => false]); ?>
        </div>
    </div>
    <div class="row">
        <div class="column small-12 text-center">
            <?php echo $this->Form->submit(__('Submit'), ['name' => 'action', 'div' => false]); ?>
            <?php echo $this->Form->submit(__('Cancel'), ['name' => 'action', 'div' => false]); ?>
        </div>
    </div>
    <?php echo $this->Form->end(); ?>
</div>
