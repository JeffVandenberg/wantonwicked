<?php /* @var View $this */ ?>
<?php $this->set('title_for_layout', 'Add Request Template'); ?>

<div class="requestTemplates form">
<?php echo $this->Form->create('RequestTemplate'); ?>
	<?php
		echo $this->Form->input('name');
		echo $this->Form->input('description');
		echo $this->Form->input('content', ['class' => 'tinymce-textarea']);
	?>
<?php echo $this->Form->end(__('Submit')); ?>
</div>

<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('List Request Templates'), array('action' => 'index')); ?></li>
	</ul>
</div>
