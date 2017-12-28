<?php
/**
  * @var \App\View\AppView $this
  * @var bool $isPlotManager
  * @var bool $isPlotViewer
  * @var bool $viewAll
  * @var \App\Model\Entity\Plot[]|\Cake\Collection\CollectionInterface $plots
  */
$this->set('title_for_layout', 'Plots');
?>
<div class="plots index" id="page-content">
    <div>
        <?php if($isPlotManager): ?>
            <?php echo $this->Html->link('New Plot', ['action' => 'add'], ['class' => 'button']); ?>
        <?php endif; ?>
        <?php echo $this->Html->link('Toggle List', ['?' => ['view_all' => !$viewAll]], ['class' => 'button']); ?>
    </div>
    <table cellpadding="0" cellspacing="0" class="stacked">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('name') ?></th>
                <th scope="col"><?= $this->Paginator->sort('PlotStatuses.name', 'Status') ?></th>
                <?php if($isPlotManager || $isPlotViewer): ?>
                    <th scope="col"><?= $this->Paginator->sort('PlotVisibilities.name', 'Visibility') ?></th>
                <?php endif; ?>
                <th scope="col"><?= $this->Paginator->sort('RunBy.username', 'Run By') ?></th>
                <th scope="col"><?= $this->Paginator->sort('CreatedBy.username', 'Created By') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created') ?></th>
                <th scope="col"><?= $this->Paginator->sort('UpdatedBy.username', 'Updated By') ?></th>
                <th scope="col"><?= $this->Paginator->sort('updated') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($plots as $plot): ?>
            <tr>
                <td><?= $this->Html->link($plot->name, ['action' => 'view', $plot->slug]) ?></td>
                <td><?= $plot->has('plot_status') ? $plot->plot_status->name : '' ?></td>
                <?php if($isPlotManager || $isPlotViewer): ?>
                    <td><?= $plot->has('plot_visibility') ? $plot->plot_visibility->name : '' ?></td>
                <?php endif; ?>
                <td><?= $plot->run_by->username ?></td>
                <td><?= $plot->created_by->username ?></td>
                <td><?= $this->Time->format($plot->created) ?></td>
                <td><?= $plot->updated_by->username ?></td>
                <td><?= $this->Time->format($plot->updated) ?></td>
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
<script>
    "use strict";

    window.onpopstate = function(e) {
        $("#page-content").html(e.state.html);
    };

    $(function () {
        $(document).on('click', '.pagination a, #content-table thead a', function () {
            var target = $(this).attr('href');

            $.get(target, function (data) {
                var contentArea = $("#page-content"),
                    state = {html: contentArea.html()};

                contentArea.html($(data).filter("#page-content"));
                window.history.pushState(state, null, target);
            }, 'html');

            return false;
        });
    });
</script>
