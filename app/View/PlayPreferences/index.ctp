<?php /* @var View $this */ ?>
<?php $this->set('title_for_layout', 'Your Player Preference'); ?>
<?php /* @var array $preferences */ ?>

<?php if (count($preferences)): ?>
<div class="playPreferences index">
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
<?php else: ?>
    You haven't filled in your player preference Survey. Perhaps you should?
    <?php echo $this->Html->link('Fill in Player Preference Survey', [
        'action' => 'respond'
    ]); ?>
<?php endif; ?>
