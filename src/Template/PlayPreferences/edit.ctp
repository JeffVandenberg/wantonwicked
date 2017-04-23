<?php
use App\Model\Entity\PlayPreference;
use App\View\AppView;

/* @var AppView $this */
/* @var PlayPreference $playPreference */
$this->set('title_for_layout', 'Edit Player Preference: ' . $playPreference->name);
?>

<?php echo $this->Form->create($playPreference); ?>
<?php
echo $this->Form->control('id');
echo $this->Form->control('name');
echo $this->Form->control('description');
?>
<button class="button" type="submit">Save</button>
<?php echo $this->Form->end(); ?>
