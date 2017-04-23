<?php
/* @var array $characters ; */
/* @var string $type ; */
/* @var View $this */

$this->set('title_for_layout', 'Admin XP Edit');
?>

<form method="post">
    <?php echo $this->Form->input('character_id', ['type' => 'number']); ?>
    <?php echo $this->Form->input('xp_amount'); ?>
    <?php echo $this->Form->input('xp_note'); ?>
    <button class="button" type="submit">Update XP</button>
</form>
