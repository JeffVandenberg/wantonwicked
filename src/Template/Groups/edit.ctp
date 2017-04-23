<?php
use App\Model\Entity\Group;
use App\View\AppView;

/* @var AppView $this */
/* @var Group $group */

$this->set('title_for_layout', 'Edit Group: ' . $group->name);
?>
<div class="groups form">
    <?= $this->Form->create($group) ?>
    <fieldset>
        <legend><?= __('Edit Group') ?></legend>
        <?php
        echo $this->Form->control('name');
        echo $this->Form->control('group_type_id');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit', ['class' => 'button'])) ?>
    <?= $this->Form->end() ?>
</div>
