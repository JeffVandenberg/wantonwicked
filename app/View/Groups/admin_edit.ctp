<?php /* @var View $this */ ?>
<?php $this->set('title_for_layout', 'Edit Group: '.$this->request->data['Group']['name']); ?>

<div class="groups form">
<?php echo $this->Form->create('Group'); ?>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('name');
		echo $this->Form->input('group_type_id');
		echo $this->Form->input('is_deleted');
		echo $this->Form->input('RequestType');
	?>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
