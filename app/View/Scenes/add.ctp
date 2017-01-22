<?php /* @var View $this */ ?>
<?php
$this->set('title_for_layout', 'Add Scene');
?>
<div class="scenes form">
    <?php echo $this->Form->create('Scene'); ?>
    <table>
        <tr>
            <td style="width:50%">
            <?php echo $this->Form->input('name'); ?>
            </td>
            <td style="width:50%">
                <?php echo $this->Form->input('run_by_name', array('label' => 'Run By', 'required' => 'required')); ?>
                <?php echo $this->Form->hidden('run_by_id'); ?>
            </td>
        </tr>
        <tr>
            <td style="width:50%">
                <?php echo $this->Form->input('summary', array('style' => 'width:100%;')); ?>
            </td>
            <td style="width:50%">
                <?php echo $this->Form->input('run_on_date', array('label' => 'Scheduled For')); ?>
            </td>
        </tr>
        <tr>
            <td colspan="2">
            <?php echo $this->Form->input('tags');; ?>
            </td>
        </tr>
        <tr>
            <td colspan="2">
            <?php echo $this->Form->input('description', array('class' => 'tinymce-textarea')); ?>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: center;">
                <?php echo $this->Form->submit('Create', array('name' => 'action', 'div' => false)); ?>
                <?php echo $this->Form->submit('Cancel', array('name' => 'action', 'div' => false)); ?>
            </td>
        </tr>
    </table>
    <?php echo $this->Form->end(); ?>
</div>
<script type="application/javascript">
    $(function() {
        $("#SceneRunByName").autocomplete({
            serviceUrl: '/users.php?action=search&email=0',
            minChars: 2,
            autoSelectFirst: true,
            onSelect: function(ui) {
                $("#SceneRunById").val(ui.data);
                $("#SceneRunByName").val(ui.value);
                return false;
            }
        });
    });
</script>

