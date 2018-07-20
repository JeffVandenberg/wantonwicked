<?php

use App\View\AppView;

/**
 * @var AppView $this
 * @var string $startDate
 * @var string $endDate
 * @var array $data
 */

$this->set('title_for_layout', 'Request Activity Report');
?>
<?= $this->Form->create(false, ['type' => 'get', 'class' => 'row align-middle']); ?>
<div class="small-12 medium-5 columns">
    <?= $this->Form->control('start_date', ['class' => 'date-input', 'value' => $startDate]); ?>
</div>
<div class="small-12 medium-5 columns">
    <?= $this->Form->control('end_date', ['class' => 'date-input', 'value' => $endDate]); ?>
</div>
<div class="small-12 medium-2 columns">
    <?= $this->Form->button('Update', ['value' => 'update', 'type' => 'submit', 'class' => 'button']); ?>
</div>
<?= $this->Form->end(); ?>
<table class="stack">
    <thead>
    <tr>
        <th>Request Admin</th>
        <th>Status Name</th>
        <th>Total</th>
    </tr>
    </thead>
    <?php $lastSt = ''; ?>
    <?php if (is_array($data)): ?>
        <?php foreach ($data as $row): ?>
            <tr>
                <td>
                    <?php if ($row['created_by']['username'] != $lastSt): ?>
                        <?php echo $lastSt = $row['created_by']['username']; ?>
                    <?php endif; ?>
                </td>
                <td><?php echo $row['request_status']['name']; ?></td>
                <td><?php echo $row['total']; ?></td>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>
</table>
