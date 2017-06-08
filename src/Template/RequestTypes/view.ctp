<?php
use App\Model\Entity\RequestType;
use App\View\AppView;

/* @var AppView $this */
/* @var RequestType $requestType ; */

$this->set('title_for_layout', 'Request Type: ' . $requestType->name);
?>

<div class="rows">
    <div class="small-12 columns">
        <?php echo $this->Html->link('<< Back', ['action' => 'index'], ['class' => 'button']); ?>
        <?php echo $this->Html->link('Edit', ['action' => 'edit', $requestType->id], ['class' => 'button']); ?>
    </div>
    <div class="requestTypes view small-12 columns content">
        <table class="vertical-table">
            <tr>
                <th scope="row"><?= __('Name') ?></th>
                <td><?= h($requestType->name) ?></td>
            </tr>
        </table>
        <div class="related">
            <h4><?= __('Related Groups') ?></h4>
            <?php if (!empty($requestType->groups)): ?>
                <table cellpadding="0" cellspacing="0">
                    <tr>
                        <th scope="col"><?= __('Name') ?></th>
                        <th scope="col" class="actions"><?= __('Actions') ?></th>
                    </tr>
                    <?php foreach ($requestType->groups as $groups): ?>
                        <tr>
                            <td><?= h($groups->name) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'Groups', 'action' => 'view', $groups->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'Groups', 'action' => 'edit', $groups->id]) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php endif; ?>
        </div>
    </div>
</div>
