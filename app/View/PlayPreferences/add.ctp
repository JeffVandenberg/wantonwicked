<?php /* @var View $this */ ?>
<?php $this->set('title_for_layout', 'Add Play Preference'); ?>
<div class="playPreferences form">
    <?php echo $this->Form->create('PlayPreference'); ?>
        <?php
        echo $this->Form->input('name');
        ?>
    <?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
    <h3><?php echo __('Actions'); ?></h3>
    <ul>
        <li><?php echo $this->Html->link(__('List Play Preferences'), array('action' => 'index')); ?></li>
    </ul>
</div>
