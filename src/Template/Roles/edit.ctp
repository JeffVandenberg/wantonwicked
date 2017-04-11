<?php /* @var View $this */; ?>
<?php $this->set('title_for_layout', 'Edit Role'); ?>

<div class="roles form">
    <?php echo $this->Form->create('Role'); ?>
    <?php
    echo $this->Form->input('id');
    echo $this->Form->input('name');
    echo $this->Form->input('description', [
        'class' => 'tinymce-textarea'
    ]);
    echo $this->Form->input('Permission', [
        'multiple' => 'checkbox'
    ]);
    ?>
    <?php echo $this->Form->submit('Create', array('name' => 'action', 'div' => false)); ?>
    <?php echo $this->Form->submit('Cancel', array('name' => 'action', 'div' => false)); ?>
    <?php echo $this->Form->end(); ?>
</div>