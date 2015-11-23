<?php /* @var View $this */ ?>
<?php $this->set('title_for_layout', 'Manage Player Preference Options'); ?>
<?php
$menu['Actions']['submenu']['New Play Preference'] = [
    'link' => [
        'action' => 'add'
    ]
];
$this->set('menu', $menu);
?>
<div class="playPreferences index">
    <table cellpadding="0" cellspacing="0">
        <tr>
            <th>Name</th>
            <th>Description</th>
            <th class="actions"><?php echo __('Actions'); ?></th>
        </tr>
        <?php foreach ($playPreferences as $playPreference): ?>
            <tr>
                <td><?php echo h($playPreference['PlayPreference']['name']); ?>&nbsp;</td>
                <td><?php echo h($playPreference['PlayPreference']['description']); ?>&nbsp;</td>
                <td class="actions">
                    <?php echo $this->Html->link(__('View'),
                        array('action' => 'view', $playPreference['PlayPreference']['id'])); ?>
                    <?php echo $this->Html->link(__('Edit'),
                        array('action' => 'edit', $playPreference['PlayPreference']['id'])); ?>
                    <?php echo $this->Form->postLink(__('Delete'),
                        array('action' => 'delete', $playPreference['PlayPreference']['id']), null,
                        __('Are you sure you want to delete # %s?', $playPreference['PlayPreference']['id'])); ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <p>
        <?php
        echo $this->Paginator->counter(array(
            'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
        ));
        ?>    </p>

    <div class="paging">
        <?php
        echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
        echo $this->Paginator->numbers(array('separator' => ''));
        echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
        ?>
    </div>
</div>
