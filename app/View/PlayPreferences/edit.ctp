<div class="playPreferences form">
<?php echo $this->Form->create('PlayPreference'); ?>
	<fieldset>
		<legend><?php echo __('Edit Play Preference'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('name');
		echo $this->Form->input('created_by_id');
		echo $this->Form->input('created_on');
		echo $this->Form->input('updated_by_id');
		echo $this->Form->input('updated_on');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('PlayPreference.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('PlayPreference.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Play Preferences'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Users'), array('controller' => 'users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Created By'), array('controller' => 'users', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Play Preference Response Histories'), array('controller' => 'play_preference_response_histories', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Play Preference Response History'), array('controller' => 'play_preference_response_histories', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Play Preference Responses'), array('controller' => 'play_preference_responses', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Play Preference Response'), array('controller' => 'play_preference_responses', 'action' => 'add')); ?> </li>
	</ul>
</div>
