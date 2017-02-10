<?php
use classes\character\data\Character;
/* @var View $this */
/* @var Character $character */
$this->set('title_for_layout', 'Beats for ' . $character->CharacterName);
?>
<div class="row align-top">
    <div class="small-12 column subheader">
        New Beat
    </div>
    <div class="small-12 medium-3 column">
        <label>
            Type
            <?php echo $this->Form->select('beat_type_id', $beatTypes, [
                'empty' => false
            ]); ?>
        </label>
    </div>
    <div class="small-12 medium-5 column end">
        <label>
            Note
            <?php echo $this->Form->textarea('beat_note'); ?>
        </label>
    </div>
</div>
<div class="row">
    <div class="small-12 column subheader">
        Past Beats
    </div>
</div>
