<?php
/**
  * @var \App\View\AppView $this
  * @var \App\Model\Entity\Icon[]|\Cake\Collection\CollectionInterface $icons
  */

$this->set('title_for_layout', 'Icons');
?>
<div class="row" id="page-content">
    <div class="small-12 column">
        <?= $this->Html->link('Add', ['action' => 'add'], ['class' => 'button']); ?>
    </div>
    <div class="small-12 column">
        <div class="icons index content">
            <table class="stack" id="content-table">
                <thead>
                <tr>
                    <th scope="col"><?= $this->Paginator->sort('icon_name') ?></th>
                    <th scope="col"><?= $this->Paginator->sort('icon_id') ?></th>
                    <th scope="col"><?= $this->Paginator->sort('player_viewable') ?></th>
                    <th scope="col"><?= $this->Paginator->sort('staff_viewable') ?></th>
                    <th scope="col"><?= $this->Paginator->sort('admin_viewable') ?></th>
                    <th scope="col" class="actions"><?= __('Actions') ?></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($icons as $icon): ?>
                    <tr>
                        <td><?= $this->Html->link($icon->icon_name, ['action' => 'view', $icon->id]) ?></td>
                        <td><?= h($icon->icon_id) ?></td>
                        <td><?= h($icon->player_viewable) ?></td>
                        <td><?= h($icon->staff_viewable) ?></td>
                        <td><?= h($icon->admin_viewable) ?></td>
                        <td class="actions">
                            <?= $this->Html->link(__('Edit'), ['action' => 'edit', $icon->id]) ?>
                            <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $icon->id], ['confirm' => __('Are you sure you want to delete # {0}?', $icon->icon_name)]) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
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
    </div>
</div>
<script>
    $(function () {
        $(document).on('click', '.pagination a, #content-table thead a', function () {
            var target = $(this).attr('href');

            $.get(target, function (data) {
                $('#page-content').html($(data).filter("#page-content"));
                var state = {html: 'doTo'};
                window.history.pushState(state, 'Cast', target);

            }, 'html');

            return false;
        });
    });
</script>
