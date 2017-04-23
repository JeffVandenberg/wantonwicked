<?php
/**
  * @var \App\View\AppView $this
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Scene Character'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Scenes'), ['controller' => 'Scenes', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Scene'), ['controller' => 'Scenes', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Characters'), ['controller' => 'Characters', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Character'), ['controller' => 'Characters', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="sceneCharacters index large-9 medium-8 columns content">
    <h3><?= __('Scene Characters') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('scene_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('character_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('added_on') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($sceneCharacters as $sceneCharacter): ?>
            <tr>
                <td><?= $this->Number->format($sceneCharacter->id) ?></td>
                <td><?= $sceneCharacter->has('scene') ? $this->Html->link($sceneCharacter->scene->name, ['controller' => 'Scenes', 'action' => 'view', $sceneCharacter->scene->id]) : '' ?></td>
                <td><?= $sceneCharacter->has('character') ? $this->Html->link($sceneCharacter->character->id, ['controller' => 'Characters', 'action' => 'view', $sceneCharacter->character->id]) : '' ?></td>
                <td><?= h($sceneCharacter->added_on) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $sceneCharacter->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $sceneCharacter->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $sceneCharacter->id], ['confirm' => __('Are you sure you want to delete # {0}?', $sceneCharacter->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]) ?></p>
    </div>
</div>
