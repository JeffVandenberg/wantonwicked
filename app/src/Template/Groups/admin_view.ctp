<?php /* @var View $this */ ?>
<?php $this->set('title_for_layout', 'View Group: ' . $group['Group']['name']); ?>

<div class="groups view">
    <h2><?php echo __('Group'); ?></h2>
    <dl>
        <dt><?php echo __('Name'); ?></dt>
        <dd>
            <?php echo h($group['Group']['name']); ?>
            &nbsp;
        </dd>
        <dt><?php echo __('Group Type'); ?></dt>
        <dd>
            <?php echo $this->Html->link($group['GroupType']['name'],
                                         array('controller' => 'group_types', 'action' => 'view', $group['GroupType']['id'])); ?>
            &nbsp;
        </dd>
        <dt><?php echo __('Is Deleted'); ?></dt>
        <dd>
            <?php echo h($group['Group']['is_deleted']); ?>
            &nbsp;
        </dd>
        <dt><?php echo __('Created By'); ?></dt>
        <dd>
            <?php echo h($group['Group']['created_by']); ?>
            &nbsp;
        </dd>
    </dl>
</div>
<div class="related">
    <h3><?php echo __('Related Request Types'); ?></h3>
    <?php if (!empty($group['RequestType'])): ?>
        <table>
            <tr>
                <th><?php echo __('Name'); ?></th>
                <th class="actions"><?php echo __('Actions'); ?></th>
            </tr>
            <?php foreach ($group['RequestType'] as $requestType): ?>
                <tr>
                    <td><?php echo $requestType['name']; ?></td>
                    <td class="actions">
                        <?php echo $this->Html->link(__('View'),
                                                     array('controller' => 'request_types', 'action' => 'view', $requestType['id'])); ?>
                        <?php echo $this->Html->link(__('Edit'),
                                                     array('controller' => 'request_types', 'action' => 'edit', $requestType['id'])); ?>
                        <?php echo $this->Form->postLink(__('Delete'),
                                                         array('controller' => 'request_types', 'action' => 'delete', $requestType['id']),
                                                         null, __('Are you sure you want to delete # {0}?',
                                                                  $requestType['id'])); ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</div>
