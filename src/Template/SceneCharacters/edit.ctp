<?php
/**
  * @var \App\View\AppView $this
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $sceneCharacter->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $sceneCharacter->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Scene Characters'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Scenes'), ['controller' => 'Scenes', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Scene'), ['controller' => 'Scenes', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Characters'), ['controller' => 'Characters', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Character'), ['controller' => 'Characters', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="sceneCharacters form large-9 medium-8 columns content">
    <?= $this->Form->create($sceneCharacter) ?>
    <fieldset>
        <legend><?= __('Edit Scene Character') ?></legend>
        <?php
            echo $this->Form->control('scene_id', ['options' => $scenes]);
            echo $this->Form->control('character_id', ['options' => $characters]);
            echo $this->Form->control('note');
            echo $this->Form->control('added_on');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
