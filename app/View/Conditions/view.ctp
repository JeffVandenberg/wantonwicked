<div class="conditions view">
<h2><?php echo __('Condition'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($condition['Condition']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Name'); ?></dt>
		<dd>
			<?php echo h($condition['Condition']['name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Source'); ?></dt>
		<dd>
			<?php echo h($condition['Condition']['source']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Is Persistent'); ?></dt>
		<dd>
			<?php echo h($condition['Condition']['is_persistent']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Description'); ?></dt>
		<dd>
			<?php echo h($condition['Condition']['description']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Resolution'); ?></dt>
		<dd>
			<?php echo h($condition['Condition']['resolution']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Beat'); ?></dt>
		<dd>
			<?php echo h($condition['Condition']['beat']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created By'); ?></dt>
		<dd>
			<?php echo $this->Html->link($condition['CreatedBy']['username'], array('controller' => 'users', 'action' => 'view', $condition['CreatedBy']['user_id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($condition['Condition']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Updated By'); ?></dt>
		<dd>
			<?php echo $this->Html->link($condition['UpdatedBy']['username'], array('controller' => 'users', 'action' => 'view', $condition['UpdatedBy']['user_id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Updated'); ?></dt>
		<dd>
			<?php echo h($condition['Condition']['updated']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Condition'), array('action' => 'edit', $condition['Condition']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Condition'), array('action' => 'delete', $condition['Condition']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $condition['Condition']['id']))); ?> </li>
		<li><?php echo $this->Html->link(__('List Conditions'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Condition'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Users'), array('controller' => 'users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Created By'), array('controller' => 'users', 'action' => 'add')); ?> </li>
	</ul>
</div>
