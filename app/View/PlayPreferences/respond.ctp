<?php /* @var View $this */ ?>
<?php $this->set('title_for_layout', 'Update your Preference'); ?>
<?php /* @var array $preferences */ ?>
<?php /* @var array $userPrefs*/ ?>
<style>
    fieldset label {
        display: inline;
        padding: 2px 10px 2px 5px;
    }
</style>
<?php echo $this->Form->create(); ?>
<?php foreach ($preferences as $preference): ?>
    <div style="float:left;width:250px;">
        <?php echo $this->Form->radio(
            $preference['PlayPreference']['name'],
            [
                1 => 'Yes',
                0 => 'No'
            ],
            [
                'name' => 'user_preference['.$preference['PlayPreference']['id'].']',
                'value' => $userPrefs[$preference['PlayPreference']['id']]
            ]
        );
        ?>
    </div>
<?php endforeach; ?>
<div style="clear:both;">
    <?php echo $this->Form->submit('Update'); ?>
    <?php echo $this->Form->submit('Cancel'); ?>
</div>
<?php echo $this->Form->end(); ?>
