<?php /* @var View $this */ ?>
<?php $this->set('title_for_layout', 'Request Type:'.$requestType['RequestType']['name']); ?>

<div class="requestTypes view">
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($requestType['RequestType']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Name'); ?></dt>
		<dd>
			<?php echo h($requestType['RequestType']['name']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Form->postLink(__('Delete Request Type'), array('action' => 'delete', $requestType['RequestType']['id']), null, __('Are you sure you want to delete # {0}?', $requestType['RequestType']['id'])); ?> </li>
	</ul>
</div>

<div class="related">
	<h3><?php echo __('Related Groups'); ?></h3>
	<?php if (!empty($requestType['Group'])): ?>
	<table>
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Name'); ?></th>
		<th><?php echo __('Group Type Id'); ?></th>
		<th><?php echo __('Is Deleted'); ?></th>
		<th><?php echo __('Created By'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($requestType['Group'] as $group): ?>
		<tr>
			<td><?php echo $group['id']; ?></td>
			<td><?php echo $group['name']; ?></td>
			<td><?php echo $group['group_type_id']; ?></td>
			<td><?php echo $group['is_deleted']; ?></td>
			<td><?php echo $group['created_by']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'groups', 'action' => 'view', $group['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'groups', 'action' => 'edit', $group['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'groups', 'action' => 'delete', $group['id']), null, __('Are you sure you want to delete # {0}?', $group['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>
</div>
