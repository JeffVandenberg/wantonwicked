<?php

use App\Model\Entity\Bluebook;
use App\Model\Entity\Request;
use App\Model\Entity\RequestBluebook;
use App\View\AppView;

/**
 * @var AppView $this
 * @var Request $request
 * @var RequestBluebook $requestBluebook
 * @var Bluebook[] $unattachedBluebooks
 */

$this->set('title_for_layout', 'Attach Request to: ' . $request->title);
?>
<?php if ($unattachedBluebooks->count()): ?>
    <?= $this->Form->create($requestBluebook); ?>
    <div class="row">
        <div class="small-12 column">
            <?= $this->Form->control('bluebook_id', [
                'options' => $unattachedBluebooks,
                'type' => 'select',
                'label' => 'Bluebook to Attach'
            ]); ?>
        </div>
        <div class="small-12 column text-center">
            <button class="button" name="action" value="Attach Bluebook" type="submit">Attach Bluebook</button>
            <button class="button" name="action" value="Cancel" type="submit">Cancel</button>
            <?= $this->Form->hidden('request_id', ['value' => $request->id]); ?>
        </div>
    </div>
    <?= $this->Form->end(); ?>
<?php else: ?>
    <div class="row">
        <div class="small-12 columns">
            No bluebooks to attach. <br/>
            <?= $this->Html->link('Back', ['action' => 'view', $request->id], [
                'class' => 'button'
            ]); ?>
        </div>
    </div>
<?php endif; ?>
