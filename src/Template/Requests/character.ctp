<?php

use App\Model\Entity\Character;
use App\Model\Entity\Request;
use App\Model\Entity\RequestStatus;
use App\View\AppView;

/**
 * @var AppView $this
 * @var Character $character
 * @var Request[] $characterRequests
 * @var Request[] $linkedRequests
 */

$this->set('title_for_layout', 'Requests for ' . $character->character_name)
?>
<div id="page-content">
    <div class="">
        <?= $this->Html->link(
            'New Request',
            [
                'action' => 'add',
                '?' => [
                    'character_id' => $character->id
                ]
            ],
            ['class' => 'button']
        ); ?>
    </div>
    <div class="row">
        <div class="text-center small-12 columns">
            <strong>Your current Open Request Status</strong>
        </div>
        <?php foreach ($requestSummary as $summary): ?>
            <?php $textClass = ($summary['total'] > 2) ? 'alert' : ''; ?>
            <div class="small-12 medium-4 large-3 columns">
                <?php echo $summary['request_type_name']; ?>:
                <span class="badge <?php echo $textClass; ?>">
                    <?= $this->Html->link(
                        $summary['total'],
                        [
                            $character->id,
                            '?' => [
                                'request_type_id' => $summary['request_type_id']
                            ]
                        ]
                    ); ?>
                </span>
            </div>
        <?php endforeach; ?>
    </div>
    <?= $this->Form->create(false, ['valueSources' => 'query', 'type' => 'get']); ?>
    <div class="row align-middle">
        <div class="small-12 medium-6 large-3 columns">
            <?= $this->Form->control('title'); ?>
        </div>
        <div class="small-12 medium-6 large-3 columns">
            <?= $this->Form->control('request_status_id', [
                'empty' => 'All'
            ]); ?>
        </div>
        <div class="small-12 medium-6 large-3 columns">
            <?= $this->Form->control('request_type_id', [
                'empty' => 'All'
            ]); ?>
        </div>
        <div class="small-12 medium-6 large-3 columns">
            <button type="submit" class="button" value="Filter">Filter</button>
        </div>
    </div>
    <?= $this->Form->end(); ?>
    <table id="content-table">
        <thead>
        <tr>
            <th><?php echo $this->Paginator->sort('title'); ?></th>
            <th><?php echo $this->Paginator->sort('RequestTypes.name', 'Type'); ?></th>
            <th><?php echo $this->Paginator->sort('RequestStatuses.name', 'Status'); ?></th>
            <th><?php echo $this->Paginator->sort('created_on'); ?></th>
            <th><?php echo $this->Paginator->sort('UpdatedBy.username', 'Updated By'); ?></th>
            <th><?php echo $this->Paginator->sort('updated_on'); ?></th>
            <th></th>
        </tr>
        </thead>
        <?php foreach ($characterRequests as $request): ?>
            <tr>
                <td><?= $this->Html->link($request->title, ['action' => 'view', $request->id]); ?></td>
                <td><?= $request->request_type->name; ?></td>
                <td><?= $request->request_status->name; ?></td>
                <td><?= $this->Time->format($request->created_on); ?></td>
                <td><?= $request->updated_by->username; ?></td>
                <td><?= $this->Time->format($request->updated_on); ?></td>
                <td>
                    <?= $this->Html->link('Add Note', ['action' => 'add-note', $request->id]); ?>
                    <?php if ($request->request_status_id == RequestStatus::NEW_REQUEST): ?>
                        <?= $this->Html->link('Edit', ['action' => 'edit', $request->id]); ?>
                        <?= $this->Html->link('Delete', ['action' => 'delete', $request->id]); ?>
                    <?php endif; ?>
                    <?php if ($request->request_status_id != RequestStatus::CLOSED): ?>
                        <?= $this->Html->link('Close', ['action' => 'close', $request->id]); ?>
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
<?php if (count($linkedRequests)): ?>
    <h3>Requests <?= $character->character_name; ?> is linked to</h3>
    <div class="row">
        <?php foreach ($linkedRequests as $request): ?>
            <div class="small-12 medium-6 large-4 columns">
                <?= $this->Html->link($request->title, ['action' => 'view', $request->id]); ?>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
<script>
    $(function () {
        $(document).on('click', '.pagination a, #content-table thead a', function () {
            var target = $(this).attr('href');

            $.get(target, function (data) {
                $('#page-content').html($(data).filter("#page-content"));
                var state = {html: 'doTo'};
                window.history.pushState(state, 'Character Requests', target);

            }, 'html');

            return false;
        });
    });
</script>
