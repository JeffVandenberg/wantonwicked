<?php /* @var View $this */ ?>
<?php $this->set('title_for_layout', 'Player Preference: ' . $playPreference['PlayPreference']['name']); ?>

<div class="playPreferences view">
<h2><?php echo __('Play Preference'); ?></h2>
	<dl>
		<dt><?php echo __('Name'); ?></dt>
		<dd>
			<?php echo h($playPreference['PlayPreference']['name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Description'); ?></dt>
		<dd>
			<?php echo h($playPreference['PlayPreference']['description']); ?>
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
