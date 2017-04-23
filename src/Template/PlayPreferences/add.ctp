<?php
use App\Model\Entity\PlayPreference;
use App\View\AppView;

/* @var AppView $this */
/* @var PlayPreference $playPreference */

$this->set('title_for_layout', 'Add Play Preference');
?>
<div class="playPreferences form">
    <?php echo $this->Form->create($playPreference); ?>
        <?php
        echo $this->Form->control('name');
        echo $this->Form->control('description');
        ?>
    <button class="button" type="submit">Save</button>
</div>
