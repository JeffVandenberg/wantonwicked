<?php

use App\Model\Entity\Scene;
use App\Model\Entity\SceneStatus;
use App\View\AppView;

/* @var AppView $this */
/* @var bool $mayAdd */
/* @var Scene[] $scenes */

if ($mayAdd) {
    $menu['Actions'] = array(
        'link'    => '#',
        'submenu' => array(
            'New Scene' => array(
                'link' => array(
                    'action' => 'add'
                )
            )
        )
    );
}

$menu['Actions']['submenu']['Upcoming Scenes'] = array(
    'link' => array(
        'action' => 'index'
    )
);

$this->set('menu', $menu);
$this->set('title_for_layout', 'My Scenes');
$this->Paginator->options(array(
                              'update'      => '#page-content',
                              'evalScripts' => true,
                          ));
?>
<div id="page-content" class="scenes index">
    <table cellpadding="0" cellspacing="0">
        <tr>
            <th><?php echo $this->Paginator->sort('name'); ?></th>
            <th>Role</th>
            <th><?php echo $this->Paginator->sort('summary'); ?></th>
            <th><?php echo $this->Paginator->sort('SceneStatuses.name', 'Status'); ?></th>
            <th><?php echo $this->Paginator->sort('run_on_date', 'Scheduled For'); ?></th>
            <th class="actions"><?php echo __('Actions'); ?></th>
        </tr>
        <?php foreach ($scenes as $scene): ?>
            <tr>
                <td><?php echo h($scene->name); ?>&nbsp;</td>
                <td>
                    <?php if ($scene->run_by_id == $this->request->getSession()->read('user_id')): ?>
                        Running
                    <?php else: ?>
                        Playing
                    <?php endif; ?>
                </td>
                <td><?php echo h($scene->summary); ?>&nbsp;</td>
                <td><?php echo h($scene->scene_status->name); ?>&nbsp;</td>
                <td><?php echo date('Y-m-d g:i A', strtotime($scene->run_on_date)); ?>&nbsp;</td>
                <td class="actions">
                    <?php echo $this->Html->link(__('View'), array('action' => 'view', $scene->slug)); ?>
                    <?php if (($mayEdit || ($scene->run_by_id == $this->request->getSession()->read('user_id'))) && SceneStatus::Cancelled != $scene['SceneStatus']['id']): ?>
                        <?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $scene->slug)); ?>
                        <?php echo $this->Html->link(__('Cancel'),
                                                     array('action' => 'cancel', $scene->slug)); ?>
                    <?php endif; ?>
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
<script>
    $(function() {
        $(document).on('click', '.pagination a, #content-table thead a', function () {
            var target = $(this).attr('href');

            $.get(target, function (data) {
                $('#page-content').html($(data).filter("#page-content"));
                var state = {html: 'doTo'};
                window.history.pushState(state, 'Beat Types', target);

            }, 'html');

            return false;
        });
    });
</script>
