<?php

use App\Model\Entity\Bluebook;
use App\Model\Entity\Character;
use App\View\AppView;

/**
 * @var AppView $this
 * @var Bluebook $bluebook
 */

$this->set('title_for_layout', 'Edit Bluebook Entry:' . $bluebook->title);
?>
<?= $this->Form->create($bluebook); ?>
<div class="row">
    <div class="small-12 columns">
        <?= $this->Form->control('title'); ?>
    </div>
    <div class="small-12 columns">
        <?= $this->Form->control('body', ['class' => 'tinymce-request', 'required' => false]); ?>
    </div>
    <div class="small-12 columns text-center">
        <?= $this->Form->button(
            'Save',
            [
                'class' => 'button',
                'name' => 'action',
                'type' => 'submit',
                'value' => 'submit'
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
