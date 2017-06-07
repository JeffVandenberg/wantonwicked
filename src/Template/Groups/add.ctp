<?php
use App\View\AppView;

/* @var AppView $this */
$this->set('title_for_layout', 'Add Group');
?>

<div class="groups form">
    <?php echo $this->Form->create('Group'); ?>
    <?php
    echo $this->Form->control('name');
    echo $this->Form->control('group_type_id');
    ?>
    <?php echo $this->Form->button(__('Save'), [
            'class' => 'button',
            'type' => 'submit',
            'value' => 'save',
            'name' => 'action'
        ]
    ) ?>
    <?php echo $this->Form->end(); ?>
</div>
