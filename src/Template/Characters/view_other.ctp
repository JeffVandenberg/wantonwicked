<?php

use App\View\AppView;
use classes\character\data\Character;

/* @var AppView $this */

/* @var Character $character */
if (isset($character)) {
    $this->set('title_for_layout', 'View Character: ' . $character->CharacterName);
} else {
    $this->set('title_for_layout', 'View Another Character');
}
$this->start('script');
?>
<script defer>
    $(function() {
        $('.remove-character-row').hide();
    })
</script>
<?php
$this->end();
?>
<form method="post" class="row align-middle">
    <div class="small-12 medium-4 columns">
        <?php echo $this->Form->control(
            'view_character_name',
            [
                'label' => 'Character Name',
                'value' => $viewCharacterName ?? ''
            ]);
        ?>
        <?php echo $this->Form->control(
            'view_character_id',
            [
                'type' => 'hidden', 'label' => 'View Password',
                'value' => $viewCharacterId ?? 0
            ]
        ); ?>
    </div>
    <div class="small-12 medium-7 columns">
        <?php echo $this->Form->control(
            'password',
            [
                'type' => 'password'
            ]);
        ?>
    </div>
    <div class="small-12 medium-1 columns bottom">
        <button type="submit" class="button" value="View">View</button>
    </div>

</form>

<?php if (isset($character)): ?>
    <?php echo $this->Character->render($character, [], $options); ?>
    <div class="row">
        <div class="small-12 columns text-center">
            <?php echo $this->Form->button('Save', [
                'class' => 'button',
                'id' => 'save',
                'name' => 'action'
            ]); ?>
        </div>
    </div>
<?php endif; ?>
<script>
    $(function () {
        $("#view-character-name").autocomplete({
            serviceUrl: '/character.php?action=search',
            minChars: 2,
            autoSelectFirst: true,
            preserveInput: true,
            params: {},
            onSearchStart: function (query) {
                query.city = 'portland';
                query.only_sanctioned = 0;
            },
            onSelect: function (item) {
                $("#view-character-id").val(item.data);
                $("#view-character-name").val(item.value);
                return false;
            }
        });
    })
</script>
