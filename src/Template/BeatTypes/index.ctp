<?php
use App\Model\Entity\BeatType;
use App\View\AppView;

/* @var AppView $this */
/* @var BeatType[] $beatTypes */

$this->set('title_for_layout', 'Beat Types');
$menu['Actions']['submenu']['New Beat Type'] = [
    'link' => [
        'controller' => 'beatTypes',
        'action' => 'add'
    ]
];
$this->set('menu', $menu);
?>
<div class="beatTypes index" id="page-content">
    <div>
        <?php echo $this->Html->link('New Beat Type', ['action' => 'add'], ['class' => 'button']); ?>
    </div>
    <table id="content-table">
        <thead>
        <tr>
            <th><?php echo $this->Paginator->sort('name'); ?></th>
            <th><?php echo $this->Paginator->sort('number_of_beats'); ?></th>
            <th><?php echo $this->Paginator->sort('admin_only', 'Staff Only'); ?></th>
            <th><?php echo $this->Paginator->sort('CreatedBy.username', 'Created By'); ?></th>
            <th><?php echo $this->Paginator->sort('created', 'Created On'); ?></th>
            <th><?php echo $this->Paginator->sort('UpdatedBy.username', 'Updated By'); ?></th>
            <th><?php echo $this->Paginator->sort('updated', 'Updated On'); ?></th>
            <th class="actions"><?php echo __('Actions'); ?></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($beatTypes as $beatType): ?>
            <tr>
                <td><?php echo h($beatType->name); ?>&nbsp;</td>
                <td><?php echo $this->Number->format($beatType->number_of_beats); ?>&nbsp;</td>
                <td><?php echo $beatType->admin_only ? 'Yes' : 'No'; ?>&nbsp;</td>
                <td>
                    <?php echo $beatType->created_by->username; ?>
                </td>
                <td><?php echo h($beatType->created); ?>&nbsp;</td>
                <td>
                    <?php echo $beatType->updated_by->username; ?>
                </td>
                <td><?php echo h($beatType->updated); ?>&nbsp;</td>
                <td class="actions">
                    <?php echo $this->Html->link(__('View'), array('action' => 'view', $beatType->id)); ?>
                    <?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $beatType->id)); ?>
                    <?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $beatType->id), array('confirm' => __('Are you sure you want to delete # {0}?', $beatType->id))); ?>
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
<script>
    $(function () {
        $(document).on('change', "#character_type", function () {
            document.location = '/characters/cast/' + $(this).val().toLowerCase();
        });

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
