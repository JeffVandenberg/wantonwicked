<?php
use App\View\AppView;

/* @var AppView $this */

$this->set('title_for_layout', 'Add Scene');
?>
<div class="scenes form">
    <?php echo $this->Form->create('Scene'); ?>
    <table>
        <tr>
            <td style="width:50%">
                <?php echo $this->Form->control('name'); ?>
            </td>
            <td style="width:50%">
                <?php echo $this->Form->control('run_by_name', ['label' => 'Run By', 'required' => 'required']); ?>
                <?php echo $this->Form->control('run_by_id', ['type' => 'hidden']); ?>
            </td>
        </tr>
        <tr>
            <td style="width:50%">
                <?php echo $this->Form->control('summary', ['style' => 'width:100%;']); ?>
            </td>
            <td style="width:50%">
                <label>
                    Scheduled For<br/>
                    <?php echo $this->Form->control('run_on_date', [
                        'class' => 'datepicker-input',
                        'type' => 'text',
                        'label' => false,
                        'required' => 'required'
                    ]); ?>
                </label>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <?php echo $this->Form->control('tag_list'); ?>
                <small>Comma separated list (tag1, tag2, etc.)</small>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <?php echo $this->Form->textarea('description', ['class' => 'tinymce-textarea']); ?>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: center;">
                <button class="button" name="action" value="Create">Create</button>
                <button class="button" name="action" value="Cancel">Cancel</button>
            </td>
        </tr>
    </table>
    <?php echo $this->Form->end(); ?>
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

