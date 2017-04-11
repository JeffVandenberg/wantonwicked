<?php
use App\Model\Entity\PlayPreference;
use App\View\AppView;

/* @var AppView $this */
/* @var PlayPreference $playPreference */

$this->set('title_for_layout', 'Player Preference: ' . $playPreference->name); ?>

<div class="playPreferences view">
<h2><?php echo __('Play Preference'); ?></h2>
	<dl>
		<dt><?php echo __('Name'); ?></dt>
		<dd>
			<?php echo h($playPreference->name); ?>
		</dd>
		<dt><?php echo __('Description'); ?></dt>
		<dd>
			<?php echo h($playPreference->description); ?>
		</dd>
		<dt><?php echo __('Created By'); ?></dt>
		<dd>
			<?php echo $playPreference->created_by->username; ?>
		</dd>
		<dt><?php echo __('Created On'); ?></dt>
		<dd>
			<?php echo h($playPreference->created_on); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Updated By'); ?></dt>
		<dd>
			<?php echo $playPreference->updated_by->username; ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Updated On'); ?></dt>
		<dd>
			<?php echo h($playPreference->updated_on); ?>
		</dd>
	</dl>
</div>
