<?php
use App\Model\Entity\Scene;
use App\View\AppView;

/* @var AppView $this */
/* @var Scene $scene */
$this->set('title_for_layout', 'Edit: ' . $scene->name);
?>
<div class="scenes form">
    <?php echo $this->Form->create($scene); ?>
    <table>
        <tr>
            <td style="width:50%">
                <?php echo $this->Form->control('name'); ?>
            </td>
            <td style="width:50%">
                <?php echo $this->Form->control('run_by_name', array(
                    'label' => 'Run By',
                    'value' => $scene->run_by->username,
                    'required' => 'required'
                )); ?>
                <?php echo $this->Form->hidden('run_by_id'); ?>
            </td>
        </tr>
        <tr>
            <td style="width:50%">
                <?php echo $this->Form->control('summary', array('style' => 'width:100%;')); ?>
            </td>
            <td style="width:50%">
                <label>
                    Scheduled For<br/>
                    <?php echo $this->Form->dateTime('run_on_date', [
                        'minYear' => date('Y'),
                        'monthNames' => false,
                        'empty' => false,
                        'interval' => 15,
                        'timeFormat' => 12,
                        'year' => [
                            'style' => 'width: 100px;'
                        ],
                        'month' => [
                            'style' => 'width:60px;'
                        ],
                        'day' => [
                            'style' => 'width:60px;'
                        ],
                        'hour' => [
                            'style' => 'width:60px;',
                        ],
                        'minute' => [
                            'style' => 'width:60px;',
                        ],
                        'meridian' => [
                            'style' => 'width:60px;'
                        ]
                    ]); ?>
                </label>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <?php echo $this->Form->control('tags'); ?>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <?php echo $this->Form->textarea('description', array('class' => 'tinymce-textarea')); ?>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: center;">
                <?php echo $this->Form->control('id'); ?>
                <button class="button" name="action" value="Update">Update</button>
                <button class="button" name="action" value="Cancel">Cancel</button>
            </td>
        </tr>
    </table>
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
