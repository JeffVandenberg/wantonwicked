<?php
/* @var View $this */
$this->set('title_for_layout', 'Beat Types');
$menu['Actions']['submenu']['New Beat Type'] = [
    'link' => [
        'controller' => 'beatTypes',
        'action' => 'add'
    ]
];
$this->set('menu', $menu);
?>
<div class="beatTypes index">
    <div>
        <?php echo $this->Html->link('New Beat Type', ['action' => 'add'], ['class' => 'button']); ?>
    </div>
    <table cellpadding="0" cellspacing="0">
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
                <td><?php echo h($beatType['BeatType']['name']); ?>&nbsp;</td>
                <td><?php echo h($beatType['BeatType']['number_of_beats']); ?>&nbsp;</td>
                <td><?php echo $beatType['BeatType']['admin_only'] ? 'Yes' : 'No'; ?>&nbsp;</td>
                <td>
                    <?php echo $beatType['CreatedBy']['username']; ?>
                </td>
                <td><?php echo h($beatType['BeatType']['created']); ?>&nbsp;</td>
                <td>
                    <?php echo $beatType['UpdatedBy']['username']; ?>
                </td>
                <td><?php echo h($beatType['BeatType']['updated']); ?>&nbsp;</td>
                <td class="actions">
                    <?php echo $this->Html->link(__('View'), array('action' => 'view', $beatType['BeatType']['id'])); ?>
                    <?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $beatType['BeatType']['id'])); ?>
                    <?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $beatType['BeatType']['id']), array('confirm' => __('Are you sure you want to delete # {0}?', $beatType['BeatType']['id']))); ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <div class="row">
        <div class="small-12 column callout small">
            <ul class="pagination">
                <?php
                echo $this->Paginator->numbers([
                    'first' => 1,
                    'last' => 1,
                    'tag' => 'li',
                    'separator' => ''
                ]);
                ?>
            </ul>
        </div>
    </div>
</div>
