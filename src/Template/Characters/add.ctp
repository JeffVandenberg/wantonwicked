<?php
use App\View\AppView;
use classes\character\data\Character;

/* @var AppView $this */
/* @var Character $character */
/* @var array $icons */
/* @var array $options */

$this->set('title_for_layout', 'Create Character');

?>
<form method="post" data-abide novalidate id="character-form">
    <div data-abide-error class="alert callout" style="display: none;">
        <p><i class="fi-alert"></i> There are some errors in your character.</p>
    </div>
    <?php echo $this->Character->render($character, $icons, $options); ?>
    <div class="row">
        <div class="small-12 columns text-center">
            <?php echo $this->Form->button('Save', [
                'class' => 'button',
                'id' => 'save',
                'name' => 'action'
            ]); ?>
        </div>
    </div>
</form>
<?php echo $this->Html->script(['create_character_nwod2'], ['block' => true]);
