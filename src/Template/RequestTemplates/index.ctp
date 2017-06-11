<?php
use App\Model\Entity\RequestTemplate;
use App\View\AppView;

/* @var AppView $this */
/* @var RequestTemplate[] $requestTemplates */

$this->set('title_for_layout', 'Request Templates');
?>
<div class="requestTemplates index">
    <div>
        <?php echo $this->Html->link('New Template', ['action' => 'add'], ['class' => 'button']); ?>
    </div>
    <table>
        <tr>
            <th><?php echo $this->Paginator->sort('name'); ?></th>
            <th><?php echo $this->Paginator->sort('description'); ?></th>
            <th class="actions"><?php echo __('Actions'); ?></th>
        </tr>
        <?php foreach ($requestTemplates as $requestTemplate): ?>
            <tr>
                <td><?php echo h($requestTemplate->name); ?>&nbsp;</td>
                <td><?php echo h($requestTemplate->description); ?>&nbsp;</td>
                <td class="actions">
                    <?php echo $this->Html->link(__('View'), array('action' => 'view', $requestTemplate->id)); ?>
                    <?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $requestTemplate->id)); ?>
<!--                    --><?php //echo $this->Form->postLink(
//                        __('Delete'),
//                        array('action' => 'delete', $requestTemplate['RequestTemplate']['id']),
//                        ['confirm' => __('Are you sure you want to delete # {0}?', $requestTemplate->id)]
//                    ); ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <div class="paginator small callout">
        <ul class="pagination">
            <?php if ($this->Paginator->hasPrev()): ?>
                <?= $this->Paginator->first('<< ' . __('First')) ?>
                <?= $this->Paginator->prev('< ' . __('Previous')) ?>
            <?php endif; ?>
            <?= $this->Paginator->numbers() ?>
            <?php if ($this->Paginator->hasNext()): ?>
                <?= $this->Paginator->next(__('Next') . ' >') ?>
                <?= $this->Paginator->last(__('Last') . ' >>') ?>
            <?php endif; ?>
        </ul>
        <p><?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]) ?></p>
    </div>
</div>
