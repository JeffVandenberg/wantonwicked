<?php /* @var View $this */; ?>
<?php $this->set('title_for_layout', 'Edit Role'); ?>

<div class="roles form">
    <?php echo $this->Form->create('Role'); ?>
    <?php
    echo $this->Form->input('id');
    echo $this->Form->input('name');
    echo $this->Form->input('Permission', [
        'multiple' => 'checkbox'
    ]);
    ?>
    <?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
    <h3><?php echo __('Actions'); ?></h3>
    <ul>
        <li><?php echo $this->Html->link(__('List Roles'), array('action' => 'index')); ?></li>
    </ul>
</div>
