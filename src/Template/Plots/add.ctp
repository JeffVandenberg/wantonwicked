<?php
/**
 * @var \App\View\AppView $this
 */

use App\Model\Entity\PlotStatus;
use App\Model\Entity\PlotVisibility;

$this->set('title_for_layout', 'Add Plot');
?>
<div class="plots form">
    <?= $this->Form->create($plot) ?>
    <div class="row align-middle">
        <div class="small-12 column">
            <?php echo $this->Form->control('name'); ?>
        </div>
        <div class="small-12 column">
            <?php echo $this->Form->control('description', [
                'class' => 'tinymce-textarea'
            ]); ?>
        </div>
        <div class="small-12 medium-4 column">
            <?php echo $this->Form->control('run_by_name', [
                'label' => 'Run By',
                'required' => true
            ]); ?>
            <?php echo $this->Form->control('run_by_id', [
                'type' => 'hidden',
                'label' => false,
                'required' => true
            ]); ?>
        </div>
        <div class="small-12 medium-4 column">
            <?php echo $this->Form->control('plot_status_id', [
                'value' => PlotStatus::Pending
            ]); ?>
        </div>
        <div class="small-12 medium-4 column">
            <?php echo $this->Form->control('plot_visibility_id', [
                'value' => PlotVisibility::Public
            ]); ?>
        </div>
        <div class="small-12 column">
            <button class="button" name="action" value="save">Save</button>
            <button class="button" name="action" value="cancel">Cancel</button>
        </div>
    </div>
    <?= $this->Form->end() ?>
</div>
<script type="application/javascript">
    $(function () {
        $("#run-by-name").autocomplete({
            serviceUrl: '/users.php?action=search&email=0',
            minChars: 2,
            autoSelectFirst: true,
            onSelect: function (ui) {
                $("#run-by-id").val(ui.data);
                $("#run-by-name").val(ui.value);
                return false;
            }
        });
    });
</script>
