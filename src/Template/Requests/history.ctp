<?php

use App\Model\Entity\Request;
use App\Model\Entity\RequestStatusHistory;
use App\View\AppView;

/**
 * @var AppView $this
 * @var Request $request
 * @var RequestStatusHistory[] $history
 */

$this->set('title_for_layout', $request->title . ' History');
?>
<div>
    <?= $this->Html->link('<< Back', ['action' => 'view', $request->id], ['class' => 'button']); ?>
</div>
<table class="stack">
    <tr>
        <th>Request Status</th>
        <th>Created By</th>
        <th>Created On</th>
    </tr>
    <?php foreach ($history as $record): ?>
        <tr>
            <td><?= $record->request_status->name; ?></td>
            <td><?= $record->created_by->username; ?></td>
            <td><?= $this->Time->format($record->created_on); ?></td>
        </tr>
    <?php endforeach; ?>
</table>
