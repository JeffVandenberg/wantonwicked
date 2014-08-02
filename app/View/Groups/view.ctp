<div class="groups view">
<h2><?php echo __('Group'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($group['Group']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Name'); ?></dt>
		<dd>
			<?php echo h($group['Group']['name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Group Type'); ?></dt>
		<dd>
			<?php echo $this->Html->link($group['GroupType']['name'], array('controller' => 'group_types', 'action' => 'view', $group['GroupType']['id'])); ?>
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
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Group'), array('action' => 'edit', $group['Group']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Group'), array('action' => 'delete', $group['Group']['id']), null, __('Are you sure you want to delete # %s?', $group['Group']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Groups'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Group'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Group Types'), array('controller' => 'group_types', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Group Type'), array('controller' => 'group_types', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Group Icons'), array('controller' => 'group_icons', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Group Icon'), array('controller' => 'group_icons', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Requests'), array('controller' => 'requests', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Request'), array('controller' => 'requests', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Request Types'), array('controller' => 'request_types', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Request Type'), array('controller' => 'request_types', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php echo __('Related Group Icons'); ?></h3>
	<?php if (!empty($group['GroupIcon'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Group Id'); ?></th>
		<th><?php echo __('Icon Id'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($group['GroupIcon'] as $groupIcon): ?>
		<tr>
			<td><?php echo $groupIcon['id']; ?></td>
			<td><?php echo $groupIcon['group_id']; ?></td>
			<td><?php echo $groupIcon['icon_id']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'group_icons', 'action' => 'view', $groupIcon['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'group_icons', 'action' => 'edit', $groupIcon['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'group_icons', 'action' => 'delete', $groupIcon['id']), null, __('Are you sure you want to delete # %s?', $groupIcon['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Group Icon'), array('controller' => 'group_icons', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
<div class="related">
	<h3><?php echo __('Related Requests'); ?></h3>
	<?php if (!empty($group['Request'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Group Id'); ?></th>
		<th><?php echo __('Character Id'); ?></th>
		<th><?php echo __('Title'); ?></th>
		<th><?php echo __('Body'); ?></th>
		<th><?php echo __('Request Type Id'); ?></th>
		<th><?php echo __('Request Status Id'); ?></th>
		<th><?php echo __('Created By Id'); ?></th>
		<th><?php echo __('Created On'); ?></th>
		<th><?php echo __('Updated By Id'); ?></th>
		<th><?php echo __('Updated On'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($group['Request'] as $request): ?>
		<tr>
			<td><?php echo $request['id']; ?></td>
			<td><?php echo $request['group_id']; ?></td>
			<td><?php echo $request['character_id']; ?></td>
			<td><?php echo $request['title']; ?></td>
			<td><?php echo $request['body']; ?></td>
			<td><?php echo $request['request_type_id']; ?></td>
			<td><?php echo $request['request_status_id']; ?></td>
			<td><?php echo $request['created_by_id']; ?></td>
			<td><?php echo $request['created_on']; ?></td>
			<td><?php echo $request['updated_by_id']; ?></td>
			<td><?php echo $request['updated_on']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'requests', 'action' => 'view', $request['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'requests', 'action' => 'edit', $request['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'requests', 'action' => 'delete', $request['id']), null, __('Are you sure you want to delete # %s?', $request['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Request'), array('controller' => 'requests', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
<div class="related">
	<h3><?php echo __('Related Request Types'); ?></h3>
	<?php if (!empty($group['RequestType'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Name'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($group['RequestType'] as $requestType): ?>
		<tr>
			<td><?php echo $requestType['id']; ?></td>
			<td><?php echo $requestType['name']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'request_types', 'action' => 'view', $requestType['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'request_types', 'action' => 'edit', $requestType['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'request_types', 'action' => 'delete', $requestType['id']), null, __('Are you sure you want to delete # %s?', $requestType['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Request Type'), array('controller' => 'request_types', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
