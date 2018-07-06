<?php

use App\Model\Entity\Bluebook;
use App\Model\Entity\Request;
use App\Model\Entity\RequestBluebook;
use App\Model\Entity\SceneRequest;
use App\View\AppView;

/**
 * @var AppView $this
 * @var Request $request
 * @var array $staff
 */

$this->set('title_for_layout', 'Assign Request: ' . $request->title);
?>
<?= $this->Form->create($request); ?>
<div class="row">
    <div class="small-12 column">
        <?= $this->Form->control('assigned_user_id', [
            'options' => $staff,
            'type' => 'select',
            'label' => 'Staff Name',
            'empty' => 'Unassigned'
        ]); ?>
    </div>
    <div class="small-12 column">
        <?= $this->Form->control('note', ['class' => 'tinymce-textarea', 'type' => 'textarea']); ?>
    </div>
    <div class="small-12 column text-center">
        <button class="button" name="action" value="Assign Request" type="submit">Assign Request</button>
        <button class="button" name="action" value="Cancel" type="submit">Cancel</button>
        <?= $this->Form->hidden('request_id', ['value' => $request->id]); ?>
    </div>
</div>
<?= $this->Form->end(); ?>
