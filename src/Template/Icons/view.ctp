<?php
/**
  * @var \App\View\AppView $this
  * @var \App\Model\Entity\Icon $icon
  */
$this->set('title_for_layout', 'Icon: ' . $icon->icon_name);

?>
<div>
    <?= $this->Html->link('<< Back', ['action' => 'index'], ['class' => 'button']); ?>
    <?= $this->Html->link('Edit', ['action' => 'edit', $icon->id], ['class' => 'button']); ?>
</div>
<div class="icons view content">
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Icon Name') ?></th>
            <td><?= h($icon->icon_name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Icon Id') ?></th>
            <td><?= h($icon->icon_id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Player Viewable') ?></th>
            <td><?= h($icon->player_viewable) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Staff Viewable') ?></th>
            <td><?= h($icon->staff_viewable) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Admin Viewable') ?></th>
            <td><?= h($icon->admin_viewable) ?></td>
        </tr>
    </table>
</div>
