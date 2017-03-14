<div class="requestTemplates view">
<h2><?php echo __('Request Template'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($requestTemplate['RequestTemplate']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Name'); ?></dt>
		<dd>
			<?php echo h($requestTemplate['RequestTemplate']['name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Description'); ?></dt>
		<dd>
			<?php echo h($requestTemplate['RequestTemplate']['description']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Content'); ?></dt>
		<dd>
			<?php echo h($requestTemplate['RequestTemplate']['content']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Request Template'), array('action' => 'edit', $requestTemplate['RequestTemplate']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Request Template'), array('action' => 'delete', $requestTemplate['RequestTemplate']['id']), null, __('Are you sure you want to delete # %s?', $requestTemplate['RequestTemplate']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Request Templates'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Request Template'), array('action' => 'add')); ?> </li>
	</ul>
</div>
