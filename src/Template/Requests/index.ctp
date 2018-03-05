<?php

use App\Model\Entity\Request;
use App\View\AppView;

/**
 * @var AppView $this
 * @var Request[] $characterRequests
 * @var Request[] $userRequests
 * @var bool $isRequestManager
 */

$this->set('title_for_layout', 'Request Dashboard');
?>
<div class="clearfix">
    <div class="">
        <?php echo $this->Html->link('New Request', ['action' => 'add'], ['class' => 'button']); ?>
        <?php if($isRequestManager): ?>
            <?php echo $this->Html->link('Manage Requests', ['action' => 'st-dashboard'], ['class' => 'button']); ?>
        <?php endif; ?>
    </div>
</div>
<h2>Your Open Requests</h2>
<table id="page-content" class="stack">
    <thead>
    <tr>
        <th>
            Request Title
        </th>
        <th>
            Type
        </th>
        <th>
            Status
        </th>
        <th>
            Created On
        </th>
        <th>
            Updated By
        </th>
        <th>
            Updated On
        </th>
        <th>
            Character
        </th>
    </tr>
    </thead>
    <?php foreach ($userRequests as $request): ?>
        <tr>
            <td>
                <?php echo $this->Html->link($request->title, ['action' => 'view', $request->id]); ?>
            </td>
            <td>
                <?php echo $request->request_type->name; ?>
            </td>
            <td>
                <?php echo $request->request_status->name; ?>
            </td>
            <td>
                <?php echo $this->Time->format($request->created_on); ?>
            </td>
            <td>
                <?php echo $request->updated_by->username; ?>
            </td>
            <td>
                <?php echo $this->Time->format($request->updated_on); ?>
            </td>
            <td>
                <?php $characterList = []; ?>
                <?php foreach ($request->request_characters as $rc): ?>
                    <?php $characterList[] = $rc->character->character_name; ?>
                <?php endforeach; ?>
                <?php echo implode(', ', $characterList); ?>
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

<h2>Requests Your characters are linked to</h2>
<table>
    <thead>
    <tr>
        <th>
            Request Title
        </th>
        <th>
            Type
        </th>
        <th>
            Status
        </th>
        <th>
            Created On
        </th>
        <th>
            Updated By
        </th>
        <th>
            Updated On
        </th>
        <th>
            Character
        </th>
    </tr>
    </thead>
    <?php foreach ($characterRequests as $request): ?>
        <tr>
            <td>
                <?php echo $this->Html->link($request->title, ['action' => 'view', $request->id]); ?>
            </td>
            <td>
                <?php echo $request->request_type->name; ?>
            </td>
            <td>
                <?php echo $request->request_status->name; ?>
            </td>
            <td>
                <?php echo $this->Time->format($request->created_on); ?>
            </td>
            <td>
                <?php echo $request->updated_by->username; ?>
            </td>
            <td>
                <?php echo $this->Time->format($request->updated_on); ?>
            </td>
            <td>
                <?php $characterList = array(); ?>
                <?php foreach ($request->request_characters as $rc): ?>
                    <?php $characterList[] = $rc->character->character_name; ?>
                <?php endforeach; ?>
                <?php echo implode(', ', $characterList); ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
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
