<div class="requestTemplates form">
<?php echo $this->Form->create('RequestTemplate'); ?>
	<fieldset>
		<legend><?php echo __('Add Request Template'); ?></legend>
	<?php
		echo $this->Form->input('name');
		echo $this->Form->input('description');
		echo $this->Form->input('content');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Request Templates'), array('action' => 'index')); ?></li>
	</ul>
</div>
