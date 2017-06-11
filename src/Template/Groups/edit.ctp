<?php
use App\Model\Entity\Group;
use App\View\AppView;

/* @var AppView $this */
/* @var Group $group */

$this->set('title_for_layout', 'Edit Group: ' . $group->name);
?>
<div class="groups form">
    <?= $this->Form->create($group) ?>
    <div class="rows">
        <div class="small-12 columns">
            <?php echo $this->Html->link('<< Back', ['action' => 'index'], ['class' => 'button']); ?>
        </div>
        <div class="small-12 columns">
            <?php echo $this->Form->control('name'); ?>
        </div>
        <div class="small-12 columns">
            <?php echo $this->Form->control('group_type_id'); ?>
        </div>
        <div class="small-12 columns text-center">
            <?php echo $this->Form->control('id'); ?>
            <?php echo $this->Form->button(__('Save'), [
                    'class' => 'button',
                    'type' => 'submit',
                    'value' => 'save',
                    'name' => 'action'
                ]
            ) ?>
        </div>
    </div>
    <?= $this->Form->end() ?>
</div>
