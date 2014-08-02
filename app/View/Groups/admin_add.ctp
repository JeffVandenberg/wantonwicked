<?php /* @var View $this */ ?>
<?php $this->set('title_for_layout', 'Add Group'); ?>

<div class="groups form">
<?php echo $this->Form->create('Group'); ?>
	<?php
		echo $this->Form->input('name');
		echo $this->Form->input('group_type_id');
        echo $this->Form->input('RequestType', array(
            'size' => 6
        ));
	?>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
