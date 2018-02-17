<?php

use App\Model\Entity\Request;
use App\Model\Entity\RequestCharacter;
use App\View\AppView;

/**
 * @var AppView $this
 * @var Request $request
 * @var RequestCharacter $requestCharacter
 * @var bool $hasPrimary
 */

$this->set('title_for_layout', 'Add character to: ' . $request->title);
?>
<?= $this->Form->create($requestCharacter); ?>
<div class="row align-middle">
    <div class="small-12 medium-5 column">
        <?= $this->Form->control('character_name', ['type' => 'text']); ?>
        <?= $this->Form->hidden('character_id', [
            'id' => 'character-id'
        ]); ?>
    </div>
    <div class="small-5 medium-2 column">
        <label>Sanctioned</label>
        <?= $this->Form->checkbox('only_sanctioned', [
            'label' => 'Only Sanctioned',
            'id' => 'only-sanctioned'
        ]); ?>
    </div>
    <div class="small-5 medium-2 column">
        <label>Primary</label>
        <?= $this->Form->checkbox('is_primary', [
            'disabled' => $hasPrimary,
            'id' => 'is-primary'
        ]); ?>
    </div>
    <div class="small-12 column">
        <?= $this->Form->textarea('note', ['class' => 'tinymce-textarea']); ?>
    </div>
    <div class="small-12 column text-center">
        <?= $this->Form->hidden('request_id', [
            'id' => 'request-id'
        ]); ?>
        <button class="button" type="submit" id="save-button" name="action" value="Add">Add</button>
        <button class="button" type="submit" id="cancel-button" name="action" value="Cancel">Cancel</button>
    </div>
</div>
<?= $this->Form->end(); ?>
<?php $this->start('script'); ?>
<script>
    $(function () {
        $("#save-button").click(function (e) {
            if ($("#character-id").val() == '') {
                alert('Please select a character.');
                e.preventDefault();
            }
            if ($("#note").val() == '') {
                alert('Please enter a note to indicate the character\'s involvement.');
                e.preventDefault();
            }
        });
        $("#character-name").autocomplete({
            serviceUrl: '/request.php?action=character_search',
            minChars: 2,
            autoSelectFirst: true,
            preserveInput: true,
            params: {},
            onSearchStart: function (query) {
                query.request_id = $("#request-id").val();
                query.only_sanctioned = $("#only-sanctioned").prop('checked');
            },
            onSelect: function (item) {
                if (item.data > 0) {
                    $("#character-id").val(item.data);
                    $("#character-name").val(item.value);
                } else {
                    $("#character-id").val('');
                    $("#character-name").val('');
                }
                return false;
            }
        });
    });
</script>
<?php $this->end(); ?>
