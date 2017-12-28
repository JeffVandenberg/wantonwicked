<?php

use App\Model\Entity\Plot;
use App\Model\Entity\PlotScene;
use App\View\AppView;

/* @var Plot $plot
 * @var PlotScene $plotScene
 * @var AppView $this
 */

$this->set('title_for_layout', 'Add scene to ' . $plot->name);
?>
<div class="plots form">
    <?= $this->Form->create($plotScene) ?>
    <div class="row align-middle">
        <div class="small-12 column">
            <?php echo $this->Form->control('scene_name', [
                'label' => 'Scene',
                'required' => true
            ]); ?>
            <?php echo $this->Form->control('scene_id', [
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
        $("#scene-name").autocomplete({
            serviceUrl: '/scenes/search',
            minChars: 2,
            autoSelectFirst: true,
            preserveInput: true,
            params: {
            },
            dataType: 'json',
            headers: {
                'Accept': 'application/json'
            },
            onSelect: function (item) {
                $("#scene-id").val(item.data);
                $("#scene-name").val(item.value);
                return false;
            }
        });
    });
</script>
