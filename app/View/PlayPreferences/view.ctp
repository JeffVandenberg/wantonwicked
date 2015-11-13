<div class="playPreferences view">
<h2><?php echo __('Play Preference'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($playPreference['PlayPreference']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Name'); ?></dt>
		<dd>
			<?php echo h($playPreference['PlayPreference']['name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created By'); ?></dt>
		<dd>
			<?php echo $this->Html->link($playPreference['CreatedBy']['username'], array('controller' => 'users', 'action' => 'view', $playPreference['CreatedBy']['user_id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created On'); ?></dt>
		<dd>
			<?php echo h($playPreference['PlayPreference']['created_on']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Updated By'); ?></dt>
		<dd>
			<?php echo $this->Html->link($playPreference['UpdatedBy']['username'], array('controller' => 'users', 'action' => 'view', $playPreference['UpdatedBy']['user_id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Updated On'); ?></dt>
		<dd>
			<?php echo h($playPreference['PlayPreference']['updated_on']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Play Preference'), array('action' => 'edit', $playPreference['PlayPreference']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Play Preference'), array('action' => 'delete', $playPreference['PlayPreference']['id']), null, __('Are you sure you want to delete # %s?', $playPreference['PlayPreference']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Play Preferences'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Play Preference'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Users'), array('controller' => 'users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Created By'), array('controller' => 'users', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Play Preference Response Histories'), array('controller' => 'play_preference_response_histories', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Play Preference Response History'), array('controller' => 'play_preference_response_histories', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Play Preference Responses'), array('controller' => 'play_preference_responses', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Play Preference Response'), array('controller' => 'play_preference_responses', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php echo __('Related Play Preference Response Histories'); ?></h3>
	<?php if (!empty($playPreference['PlayPreferenceResponseHistory'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('User Id'); ?></th>
		<th><?php echo __('Play Preference Id'); ?></th>
		<th><?php echo __('Rating'); ?></th>
		<th><?php echo __('Created On'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($playPreference['PlayPreferenceResponseHistory'] as $playPreferenceResponseHistory): ?>
		<tr>
			<td><?php echo $playPreferenceResponseHistory['id']; ?></td>
			<td><?php echo $playPreferenceResponseHistory['user_id']; ?></td>
			<td><?php echo $playPreferenceResponseHistory['play_preference_id']; ?></td>
			<td><?php echo $playPreferenceResponseHistory['rating']; ?></td>
			<td><?php echo $playPreferenceResponseHistory['created_on']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'play_preference_response_histories', 'action' => 'view', $playPreferenceResponseHistory['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'play_preference_response_histories', 'action' => 'edit', $playPreferenceResponseHistory['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'play_preference_response_histories', 'action' => 'delete', $playPreferenceResponseHistory['id']), null, __('Are you sure you want to delete # %s?', $playPreferenceResponseHistory['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Play Preference Response History'), array('controller' => 'play_preference_response_histories', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
<div class="related">
	<h3><?php echo __('Related Play Preference Responses'); ?></h3>
	<?php if (!empty($playPreference['PlayPreferenceResponse'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('User Id'); ?></th>
		<th><?php echo __('Play Preference Id'); ?></th>
		<th><?php echo __('Rating'); ?></th>
		<th><?php echo __('Note'); ?></th>
		<th><?php echo __('Created On'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($playPreference['PlayPreferenceResponse'] as $playPreferenceResponse): ?>
		<tr>
			<td><?php echo $playPreferenceResponse['id']; ?></td>
			<td><?php echo $playPreferenceResponse['user_id']; ?></td>
			<td><?php echo $playPreferenceResponse['play_preference_id']; ?></td>
			<td><?php echo $playPreferenceResponse['rating']; ?></td>
			<td><?php echo $playPreferenceResponse['note']; ?></td>
			<td><?php echo $playPreferenceResponse['created_on']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'play_preference_responses', 'action' => 'view', $playPreferenceResponse['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'play_preference_responses', 'action' => 'edit', $playPreferenceResponse['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'play_preference_responses', 'action' => 'delete', $playPreferenceResponse['id']), null, __('Are you sure you want to delete # %s?', $playPreferenceResponse['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Play Preference Response'), array('controller' => 'play_preference_responses', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
