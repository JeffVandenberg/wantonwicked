<?php
/* @var View $this */
/* @var array $icons */
/* @var array $cities */
use classes\character\data\Character;

if (isset($character) && $character->Id) {
    /* @var Character $character */
    $this->set('title_for_layout', 'View Character: ' . $character->CharacterName);
} else {
    $this->set('title_for_layout', 'ST Character Lookup');
}
?>

<form method="get">
    <div class="row">
        <div class="small-12 medium-4 column">
            <label for="view_character_name">Character</label>
            <input type="text" name="view_character_name" id="view_character_name" />
            <input type="hidden" name="view_character_id" id="view_character_id" />
        </div>
        <div class="small-12 medium-4 column">
            <label>City
                <?php echo $this->Form->select('city', $cities, [
                    'label' => 'City',
                    'empty' => false
                ]); ?>
            </label>
        </div>
        <div class="small-6 medium-2 column">
            <label>Only Sanctioned
                <?php echo $this->Form->checkbox('only_sanctioned', ['label' => 'Sanctioned']); ?>
            </label>
        </div>
        <div class="small-6 medium-2 column bottom">
            <?php echo $this->Form->button('View', [
                'type' => 'submit',
                'value' => 'View',
                'class' => 'button',
                'name' => 'action',
                'label' => false
            ]); ?>
        </div>
    </div>
</form>

<?php if (isset($character) && $character->Id): ?>
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
    <?php $this->Html->script('create_character_nwod2', ['inline' => false]); ?>
<?php endif; ?>
<script>
    $(function () {
        $("#view_character_name").autocomplete({
            serviceUrl: '/character.php?action=search',
            minChars: 2,
            autoSelectFirst: true,
            preserveInput: true,
            params: {
            },
            onSearchStart: function (query) {
                query.city = $('#city').val();
                query.only_sanctioned = $("#only_sanctioned").prop('checked') ? 1 : 0;
            },
            onSelect: function(item) {
                $("#view_character_id").val(item.data);
                $("#view_character_name").val(item.value);
                return false;
            }
        });
    });
</script>
