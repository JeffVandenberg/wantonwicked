<?php /* @var View $this */ ?>
<?php $this->set('title_for_layout', 'Manage Player Preference Options'); ?>

<div class="playPreferences index">
    <h2><?php echo __('Play Preferences'); ?></h2>
    <table cellpadding="0" cellspacing="0">
        <tr>
            <th><?php echo $this->Paginator->sort('id'); ?></th>
            <th><?php echo $this->Paginator->sort('name'); ?></th>
            <th><?php echo $this->Paginator->sort('created_by_id'); ?></th>
            <th><?php echo $this->Paginator->sort('created_on'); ?></th>
            <th><?php echo $this->Paginator->sort('updated_by_id'); ?></th>
            <th><?php echo $this->Paginator->sort('updated_on'); ?></th>
            <th class="actions"><?php echo __('Actions'); ?></th>
        </tr>
        <?php foreach ($playPreferences as $playPreference): ?>
            <tr>
                <td><?php echo h($playPreference['PlayPreference']['id']); ?>&nbsp;</td>
                <td><?php echo h($playPreference['PlayPreference']['name']); ?>&nbsp;</td>
                <td>
                    <?php echo $this->Html->link($playPreference['CreatedBy']['username'],
                        array('controller' => 'users', 'action' => 'view', $playPreference['CreatedBy']['user_id'])); ?>
                </td>
                <td><?php echo h($playPreference['PlayPreference']['created_on']); ?>&nbsp;</td>
                <td>
                    <?php echo $this->Html->link($playPreference['UpdatedBy']['username'],
                        array('controller' => 'users', 'action' => 'view', $playPreference['UpdatedBy']['user_id'])); ?>
                </td>
                <td><?php echo h($playPreference['PlayPreference']['updated_on']); ?>&nbsp;</td>
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
<div class="actions">
    <h3><?php echo __('Actions'); ?></h3>
    <ul>
        <li><?php echo $this->Html->link(__('New Play Preference'), array('action' => 'add')); ?></li>
        <li><?php echo $this->Html->link(__('List Users'),
                array('controller' => 'users', 'action' => 'index')); ?> </li>
        <li><?php echo $this->Html->link(__('New Created By'),
                array('controller' => 'users', 'action' => 'add')); ?> </li>
        <li><?php echo $this->Html->link(__('List Play Preference Response Histories'),
                array('controller' => 'play_preference_response_histories', 'action' => 'index')); ?> </li>
        <li><?php echo $this->Html->link(__('New Play Preference Response History'),
                array('controller' => 'play_preference_response_histories', 'action' => 'add')); ?> </li>
        <li><?php echo $this->Html->link(__('List Play Preference Responses'),
                array('controller' => 'play_preference_responses', 'action' => 'index')); ?> </li>
        <li><?php echo $this->Html->link(__('New Play Preference Response'),
                array('controller' => 'play_preference_responses', 'action' => 'add')); ?> </li>
    </ul>
</div>
