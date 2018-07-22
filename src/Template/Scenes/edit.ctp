<?php
use App\Model\Entity\Scene;
use App\View\AppView;

/* @var AppView $this */
/* @var Scene $scene */
$this->set('title_for_layout', 'Edit: ' . $scene->name);
?>
<div class="scenes form">
    <?php echo $this->Form->create($scene); ?>
    <div class="row">
        <div class="small-12 medium-6 columns">
            <?php echo $this->Form->control('name'); ?>
        </div>
        <div class="small-12 medium-6 columns">
            <?php echo $this->Form->control('run_by_name', array(
                'label' => 'Run By',
                'value' => $scene->run_by->username,
                'required' => 'required'
            )); ?>
            <?php echo $this->Form->control('run_by_id', ['type' => 'hidden']); ?>
        </div>
        <div class="small-12 medium-6 columns">
            <?php echo $this->Form->control('summary', ['style' => 'width:100%;']); ?>
        </div>
        <div class="small-12 medium-6 columns">
            <?php echo $this->Form->control('run_on_date', [
                'class' => 'datepicker-input',
                'type' => 'text',
                'label' => 'Scheduled For',
                'required' => 'required'
            ]); ?>
        </div>
        <div class="small-12 medium-6 columns">
            <?php echo $this->Form->control('tag_list'); ?>
            <small>Comma separated list (tag1, tag2, etc.)</small>
        </div>
        <div class="small-12 medium-6 columns">
            <?php echo $this->Form->control('signup_limit', [
                'style' => 'width:100%;',
                'placeholder' => '0 for unlimited',
            ]); ?>
        </div>
        <div class="small-12 columns">
            <?php echo $this->Form->textarea('description', ['class' => 'tinymce-textarea']); ?>
        </div>
        <div class="small-12 columns text-center">
            <button class="button" name="action" value="Create">Update</button>
            <button class="button" name="action" value="Cancel">Cancel</button>
        </div>
    </div>
    <?php echo $this->Form->end(); ?>
</div>
<script type="application/javascript">
    $(function() {
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
