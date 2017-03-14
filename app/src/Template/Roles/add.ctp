<?php /* @var View $this */; ?>
<?php $this->set('title_for_layout', 'Add Role'); ?>

<div class="roles form">
    <?php echo $this->Form->create('Role'); ?>
    <?php
    echo $this->Form->input('name');
    echo $this->Form->input('description', [
        'class' => 'tinymce-textarea'
    ]);
    echo $this->Form->input('Permission', [
        'multiple' => 'checkbox'
    ]);
    ?>
    <?php echo $this->Form->button('Cancel', ['name' => 'action']); ?>
    <?php echo $this->Form->button('Submit', ['name' => 'action']); ?>
    <?php echo $this->Form->end(); ?>
</div>
