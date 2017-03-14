<?php /* @var View $this */ ?>
<?php $this->set('title_for_layout', 'Request Types'); ?>
<div class="requestTypes index">
    <table>
        <tr>
            <th><?php echo $this->Paginator->sort('id'); ?></th>
            <th><?php echo $this->Paginator->sort('name'); ?></th>
            <th class="actions"><?php echo __('Actions'); ?></th>
        </tr>
        <?php foreach ($requestTypes as $requestType): ?>
            <tr>
                <td><?php echo h($requestType['RequestType']['id']); ?>&nbsp;</td>
                <td><?php echo h($requestType['RequestType']['name']); ?>&nbsp;</td>
                <td class="actions">
                    <?php echo $this->Html->link(__('View'),
                                                 array('action' => 'view', $requestType['RequestType']['id'])); ?>
                    <?php echo $this->Html->link(__('Edit'),
                                                 array('action' => 'edit', $requestType['RequestType']['id'])); ?>
                    <?php echo $this->Form->postLink(__('Delete'),
                                                     array('action' => 'delete', $requestType['RequestType']['id']),
                                                     null, __('Are you sure you want to delete # %s?',
                                                              $requestType['RequestType']['id'])); ?>
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
