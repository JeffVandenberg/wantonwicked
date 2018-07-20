<?php

use App\Model\Entity\Request;
use App\View\AppView;

/**
 * @var AppView $this
 * @var Request $request
 */

$this->set('title_for_layout', 'Create Request');
?>
<?= $this->Form->create($request); ?>
<div class="row">
    <div class="small-12 columns">
        <?= $this->Form->control('title'); ?>
    </div>
    <div class="small-12 medium-6 columns">
        <?= $this->Form->control('group_id'); ?>
    </div>
    <div class="small-12 medium-6 columns">
        <?= $this->Form->control('request_type_id'); ?>
    </div>
    <div class="small-12 columns">
        <?= $this->Form->control('body', ['class' => 'tinymce-request', 'required' => false]); ?>
    </div>
    <div class="small-12 columns text-center">
        <?= $this->Form->button(
            'Submit Request',
            [
                'class' => 'button',
                'name' => 'action',
                'type' => 'submit',
                'value' => 'submit'
            ]); ?>
        <?= $this->Form->button(
            'Add Attachments',
            [
                'class' => 'button',
                'name' => 'action',
                'type' => 'submit',
                'value' => 'add'
            ]); ?>
        <?= $this->Form->button(
            'Cancel',
            [
                'class' => 'button',
                'name' => 'action',
                'type' => 'submit',
                'value' => 'cancel'
            ]); ?>
    </div>
</div>
<?= $this->Form->end(); ?>
<?php $this->start('script'); ?>
<script defer="defer">
    $(function () {
        $("#group-id").change(function () {
            $.get(
                '/groups/listRequestTypes/' + $(this).val() + '.json',
                function (data) {
                    var list = $("#request-type-id");
                    list.empty();
                    for (var i = 0; i < data.list.length; i++) {
                        var item = data.list[i];
                        list.append(
                            $('<option>')
                                .text(item.name)
                                .val(item.id)
                        );
                    }
                });
        });
    });
</script>
<?php $this->end(); ?>
