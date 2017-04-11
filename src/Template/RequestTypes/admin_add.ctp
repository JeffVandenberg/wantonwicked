<?php /* @var View $this */ ?>
<?php $this->set('title_for_layout', 'Add Request Type'); ?>

<div class="requestTypes form">
<?php echo $this->Form->create('RequestType'); ?>
	<?php
		echo $this->Form->input('name');
		echo $this->Form->input('Group', array('size' => 6));
	?>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
