<?php /* @var View $this */ ?>
<?php $this->set('title_for_layout', 'Edit Player Preference: ' . $this->request->data['PlayPreference']['name']); ?>

<?php echo $this->Form->create('PlayPreference'); ?>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('name');
		echo $this->Form->input('description');
	?>
<?php echo $this->Form->end(__('Submit')); ?>
