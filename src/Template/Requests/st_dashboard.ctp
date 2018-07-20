<?php

use App\Model\Entity\Group;
use App\Model\Entity\Request;
use App\View\AppView;

/**
 * @var AppView $this
 * @var Group[] $groups
 * @var Request[] $requests
 */

$this->set('title_for_layout', 'Pending Requests');
?>
<div id="page-content">
    <h2>Filters</h2>
    <?= $this->Form->create(false, ['valueSources' => 'query', 'type' => 'get']); ?>
    <div class="row align-middle">
        <div class="small-12 medium-6 column">
            <?= $this->Form->control('title', ['label' => 'Request Name']); ?>
        </div>
        <div class="small-12 medium-6 column">
            <?= $this->Form->control('username', ['label' => 'User']); ?>
        </div>
        <div class="small-12 medium-4 column">
            <?= $this->Form->control('request_type_id', [
                'empty' => 'All'
            ]); ?>
        </div>
        <div class="small-12 medium-3 column">
            <?= $this->Form->control('group_id', [
                'empty' => 'All Groups'
            ]); ?>
        </div>
        <div class="small-12 medium-3 column">
            <?= $this->Form->control('request_status_id', [
                'empty' => 'Open'
            ]); ?>
        </div>
        <div class="small-12 medium-2 column bottom">
            <button class="button" type="submit" name="page_action">Update Filters</button>
        </div>
    </div>
    <?= $this->Form->end(); ?>
    <table class="stack" id="content-table">
        <thead>
        <tr>
            <th>
                <?= $this->Paginator->sort('Requests.title', 'Request'); ?>
            </th>
            <th>
                <?= $this->Paginator->sort('CreatedBy.username_clean', 'User'); ?>
            </th>
            <th>
                <?= $this->Paginator->sort('Groups.name', 'Group'); ?>
            </th>
            <th>
                <?= $this->Paginator->sort('RequestTypes.name', 'Type'); ?>
            </th>
            <th>
                <?= $this->Paginator->sort('RequestStatuses.name', 'Status'); ?>
            </th>
            <th>
                <?= $this->Paginator->sort('AssignedUser.username_clean', 'Assigned To'); ?>
            </th>
            <th>
                <?= $this->Paginator->sort('UpdateBy.username_clean', 'Updated By'); ?>
            </th>
            <th>
                <?= $this->Paginator->sort('Requests.updated_on', 'Updated'); ?>
            </th>
        </tr>
        </thead>
        <?php if (count($requests)): ?>
            <?php foreach ($requests as $request): ?>
                <tr>
                    <td><?= $this->Html->link($request->title, ['action' => 'st-view', $request->id], [
                            'title' => strip_tags($request->body)
                        ]); ?></td>
                    <td><?= $this->Html->link($request->created_by->username,
                            [
                                '?' => [
                                        'username' => $request->created_by->username
                                    ] + $this->request->getQueryParams()
                            ],
                            [
                                'title' => 'Click to filter on username',
                                'class' => 'ajax-link'
                            ]); ?>
                    </td>
                    <td><?= $this->Html->link(
                            $request->group->name,
                            [
                                '?' => [
                                        'group_id' => $request->group_id
                                    ] + $this->request->getQueryParams()
                            ],
                            [
                                'title' => 'Click to filter on group',
                                'class' => 'ajax-link'
                            ]); ?>
                    </td>
                    <td><?= $this->Html->link(
                            $request->request_type->name,
                            [
                                '?' => [
                                        'request_type_id' => $request->request_type_id
                                    ] + $this->request->getQueryParams()
                            ],
                            [
                                'title' => 'Click to filter on request type',
                                'class' => 'ajax-link'
                            ]); ?>
                    </td>
                    <td><?= $this->Html->link(
                            $request->request_status->name,
                            [
                                '?' => [
                                        'request_status_id' => $request->request_status_id
                                    ] + $this->request->getQueryParams()
                            ],
                            [
                                'title' => 'Click to filter on request status',
                                'class' => 'ajax-link'
                            ]); ?>
                    </td>
                    <td><?= $request->assigned_user->username; ?></td>
                    <td><?= $request->updated_by->username; ?></td>
                    <td><?= $this->Time->format($request->updated_on, 'MM/dd/yyyy'); ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="8" class="text-center">
                    No Requests
                </td>
            </tr>
        <?php endif; ?>
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
    $(function () {
        $(document).on('click', '.pagination a, #content-table thead a, #content-table .ajax-link', function () {
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
