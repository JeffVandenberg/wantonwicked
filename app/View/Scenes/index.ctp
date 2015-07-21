<?php /* @var View $this */ ?>
<?php
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

if(AuthComponent::user('user_id') != 1) {
    $menu['Actions']['submenu']['My Scenes'] = array(
        'link' => array(
            'action' => 'my_scenes'
        )
    );
}

if ($includePast) {
    $menu['Actions']['submenu']['View Upcoming Scenes'] = array(
        'link' => array(
            0
        )
    );
    $this->set('title_for_layout', 'All Scenes');
}
else {
    $menu['Actions']['submenu']['View All Scenes'] = array(
        'link' => array(
            1
        )
    );
    $this->set('title_for_layout', 'Upcoming Scenes');
}
$this->set('menu', $menu);
$this->Paginator->options(array(
    'update'      => '#page-content',
    'evalScripts' => true,
));
?>
<div id="page-content" class="scenes index">
    <table cellpadding="0" cellspacing="0">
        <tr>
            <th><?php echo $this->Paginator->sort('name'); ?></th>
            <th><?php echo $this->Paginator->sort('summary'); ?></th>
            <th><?php echo $this->Paginator->sort('RunBy.username', 'Run By'); ?></th>
            <th><?php echo $this->Paginator->sort('run_on_date', 'Scheduled For'); ?></th>
            <th class="actions"><?php echo __('Actions'); ?></th>
        </tr>
        <?php foreach ($scenes as $scene): ?>
            <tr>
                <td><?php echo h($scene['Scene']['name']); ?>&nbsp;</td>
                <td><?php echo h($scene['Scene']['summary']); ?>&nbsp;</td>
                <td>
                    <?php echo $scene['RunBy']['username']; ?>
                </td>
                <td class="server-time"><?php echo date('Y-m-d g:i A', strtotime($scene['Scene']['run_on_date'])); ?>&nbsp;</td>
                <td class="actions">
                    <?php echo $this->Html->link(__('View'), array('action' => 'view', $scene['Scene']['slug'])); ?>
                    <?php if(AuthComponent::user('user_id') != 1): ?>
                        <?php echo $this->Html->link(__('Join'), array('action' => 'join', $scene['Scene']['slug'])); ?>
                    <?php endif; ?>
                    <?php if ($mayEdit || AuthComponent::user('user_id') == $scene['Scene']['created_by_id']): ?>
                        <?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $scene['Scene']['slug'])); ?>
<!--                        --><?php //echo $this->Form->postLink(__('Delete'),
//                                                         array('action' => 'delete', $scene['Scene']['id']),
//                                                         null, __('Are you sure you want to delete # %s?',
//                                                                  $scene['Scene']['id'])); ?>
                    <?php endif; ?>
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
    <?php echo $this->Js->writeBuffer(); ?>
</div>
