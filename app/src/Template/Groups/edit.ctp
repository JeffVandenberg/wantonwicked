<div class="groups form">
<?php echo $this->Form->create('Group'); ?>
	<fieldset>
		<legend><?php echo __('Edit Group'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('name');
		echo $this->Form->input('group_type_id');
		echo $this->Form->input('is_deleted');
		echo $this->Form->input('created_by');
		echo $this->Form->input('RequestType');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Group.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('Group.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Groups'), array('action' => 'index')); ?></li>
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
