<?php

use App\Model\Entity\Plot;
use App\Model\Entity\PlotCharacter;
use App\View\AppView;

/* @var Plot $plot
 * @var PlotCharacter $plotCharacter
 * @var AppView $this
 */

$this->set('title_for_layout', 'Add character to ' . $plot->name);
?>
<div class="plots form">
    <?= $this->Form->create($plotCharacter) ?>
    <div class="row align-middle">
        <div class="small-12 column">
            <?php echo $this->Form->control('character_name', [
                'label' => 'Character',
                'required' => true
            ]); ?>
            <?php echo $this->Form->control('character_id', [
                'type' => 'hidden',
                'label' => false,
                'required' => true
            ]); ?>
        </div>
        <div class="small-12 column">
            <?php echo $this->Form->control('note', [
                'class' => 'tinymce-textarea'
            ]); ?>
        </div>
        <div class="small-12 column">
            <?php echo $this->Form->control('plot_id', [
                'type' => 'hidden',
                'value' => $plot->id
            ]); ?>
            <button class="button" name="action" value="save">Save</button>
            <button class="button" name="action" value="cancel">Cancel</button>
        </div>
    </div>
    <?= $this->Form->end() ?>
</div>
<script>
    $(function () {
        $("#character-name").autocomplete({
            serviceUrl: '/character.php?action=search',
            minChars: 2,
            autoSelectFirst: true,
            preserveInput: true,
            params: {
                'city': 'portland',
                'only_sanctioned': 1
            },
            onSelect: function (item) {
                $("#character-id").val(item.data);
                $("#character-name").val(item.value);
                return false;
            }
        });
    });
</script>
