<?php
use App\View\AppView;
use classes\character\data\Character;

/* @var AppView $this */
/* @var array $icons */
/* @var array $cities */

if (isset($character) && $character->Id) {
    /* @var Character $character */
    $this->set('title_for_layout', 'View Character: ' . $character->CharacterName);
} else {
    $this->set('title_for_layout', 'ST Character Lookup');
}
?>

<form>
    <div class="row align-middle">
        <div class="small-12 medium-4 column">
            <label for="view_character_name">Character</label>
            <input type="text" name="view_character_name" id="view_character_name"/>
            <input type="hidden" name="view_character_id" id="view_character_id"/>
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
            <label>Sanctioned
                <?php echo $this->Form->checkbox(
                    'only_sanctioned',
                    [
                        'label' => 'Sanctioned',
                        'id' => 'only_sanctioned'
                    ]
                ); ?>
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
    <div class="row">
        <div class="small-12 column subheader">
            Information
        </div>
        <div class="small-3 column">
            Player: <?php echo $character->User->Username; ?>
        </div>
        <div class="small-9 column">
            <div class="button-group">
                <a class="button"
                   href="/storyteller_index.php?action=profile_lookup&profile_name=<?php echo $character->User->Username; ?>">View
                    Their Characters</a>
                <a class="button" href="/character.php?action=log&character_id=<?php echo $character->Id; ?>">View
                    Character Log</a>
                <?php if ($character->inSanctionedStatus()): ?>
                    <a class="button" href="/characters/beats/<?php echo $character->Slug; ?>">Beat Tracker</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <form method="post" data-abide novalidate id="character-form">
        <div data-abide-error class="alert callout" style="display: none;">
            <p><i class="fi-alert"></i> There are some errors in your character.</p>
        </div>
        <?php echo $this->Character->render($character, $icons, $options); ?>
        <div class="row">
            <div class="small-12 columns text-center">
                <?php echo $this->Form->button('Save', [
                    'class' => 'button',
                    'id' => 'save-character-button',
                    'name' => 'action'
                ]); ?>
            </div>
        </div>
    </form>
    <?php echo $this->Html->script('create_character_nwod2'); ?>
<?php endif; ?>
<script>
    $(function () {
        const key = 'sanctioned.only_sanctioned_checked';
        $("#only_sanctioned")
            .click(function () {
                window.localStorage.setItem(key, $(this).prop('checked'));
            })
            .prop('checked', window.localStorage.getItem(key));

        const characterStatus = $("#character_status_id");
        characterStatus
            .focus(function () {
                $(this).find('option[value=4]').hide();
                $(this).find('option[value=6]').hide();
                if($(this).val() === '4') {
                    $(this).find('option[value=4]').show();
                }
                if($(this).val() === '6') {
                    $(this).find('option[value=6]').show();
                }
            });
        $("#view_character_name").autocomplete({
            serviceUrl: '/character.php?action=search',
            minChars: 2,
            autoSelectFirst: true,
            preserveInput: true,
            params: {},
            onSearchStart: function (query) {
                query.city = $('#city').val();
                query.only_sanctioned = $("#only_sanctioned").prop('checked') ? 1 : 0;
            },
            onSelect: function (item) {
                $("#view_character_id").val(item.data);
                $("#view_character_name").val(item.value);
                return false;
            }
        });
    });
</script>
