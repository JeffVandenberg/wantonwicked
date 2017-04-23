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
                    <?php echo $this->Form->dateTime('run_on_date', [
                        'minYear' => date('Y'),
                        'value' => date('Y-m-d H:i:s'),
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
                <?php echo $this->Form->control('tags');; ?>
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

