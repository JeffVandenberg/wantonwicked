<?php
/**
  * @var \App\View\AppView $this
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Scene Character'), ['action' => 'edit', $sceneCharacter->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Scene Character'), ['action' => 'delete', $sceneCharacter->id], ['confirm' => __('Are you sure you want to delete # {0}?', $sceneCharacter->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Scene Characters'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Scene Character'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Scenes'), ['controller' => 'Scenes', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Scene'), ['controller' => 'Scenes', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Characters'), ['controller' => 'Characters', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Character'), ['controller' => 'Characters', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="sceneCharacters view large-9 medium-8 columns content">
    <h3><?= h($sceneCharacter->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Scene') ?></th>
            <td><?= $sceneCharacter->has('scene') ? $this->Html->link($sceneCharacter->scene->name, ['controller' => 'Scenes', 'action' => 'view', $sceneCharacter->scene->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Character') ?></th>
            <td><?= $sceneCharacter->has('character') ? $this->Html->link($sceneCharacter->character->id, ['controller' => 'Characters', 'action' => 'view', $sceneCharacter->character->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($sceneCharacter->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Added On') ?></th>
            <td><?= h($sceneCharacter->added_on) ?></td>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('Note') ?></h4>
        <?= $this->Text->autoParagraph(h($sceneCharacter->note)); ?>
    </div>
</div>
