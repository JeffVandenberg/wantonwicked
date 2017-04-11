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

$menu['Actions']['submenu']['Upcoming Scenes'] = array(
    'link' => array(
        'action' => 'index'
    )
);

$this->set('menu', $menu);
$this->set('title_for_layout', 'My Scenes');
$this->Paginator->options(array(
                              'update'      => '#page-content',
                              'evalScripts' => true,
                          ));
?>
<div id="page-content" class="scenes index">
    <table cellpadding="0" cellspacing="0">
        <tr>
            <th><?php echo $this->Paginator->sort('name'); ?></th>
            <th>Role</th>
            <th><?php echo $this->Paginator->sort('summary'); ?></th>
            <th><?php echo $this->Paginator->sort('SceneStatus.name', 'Status'); ?></th>
            <th><?php echo $this->Paginator->sort('run_on_date', 'Scheduled For'); ?></th>
            <th class="actions"><?php echo __('Actions'); ?></th>
        </tr>
        <?php foreach ($scenes as $scene): ?>
            <tr>
                <td><?php echo h($scene['Scene']['name']); ?>&nbsp;</td>
                <td>
                    <?php if ($scene['Scene']['run_by_id'] == AuthComponent::user('user_id')): ?>
                        Running
                    <?php else: ?>
                        Playing
                    <?php endif; ?>
                </td>
                <td><?php echo h($scene['Scene']['summary']); ?>&nbsp;</td>
                <td><?php echo h($scene['SceneStatus']['name']); ?>&nbsp;</td>
                <td><?php echo date('Y-m-d g:i A', strtotime($scene['Scene']['run_on_date'])); ?>&nbsp;</td>
                <td class="actions">
                    <?php echo $this->Html->link(__('View'), array('action' => 'view', $scene['Scene']['slug'])); ?>
                    <?php if (($mayEdit || ($scene['Scene']['run_by_id'] == AuthComponent::user('user_id'))) && SceneStatus::Cancelled != $scene['SceneStatus']['id']): ?>
                        <?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $scene['Scene']['slug'])); ?>
                        <?php echo $this->Html->link(__('Cancel'),
                                                     array('action' => 'cancel', $scene['Scene']['slug'])); ?>
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

