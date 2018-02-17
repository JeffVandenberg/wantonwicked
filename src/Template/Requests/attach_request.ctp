<?php

use App\Model\Entity\Request;
use App\Model\Entity\RequestRequest;
use App\View\AppView;

/**
 * @var AppView $this
 * @var Request $request
 * @var RequestRequest $requestRequest
 * @var Request[] $unattachedRequests
 */

$this->set('title_for_layout', 'Attach Request to: ' . $request->title);
?>
<?php if (count($unattachedRequests)): ?>
    <?= $this->Form->create($requestRequest); ?>
    <div class="row">
        <div class="small-12 column">
            <?= $this->Form->control('from_request_id', [
                'options' => $unattachedRequests,
                'type' => 'select',
                'label' => 'Request to Attach'
            ]); ?>
        </div>
        <div class="small-12 column text-center">
            <button class="button" name="action" value="Attach Request" type="submit">Attach Request</button>
            <button class="button" name="action" value="Cancel" type="submit">Cancel</button>
            <?= $this->Form->hidden('request_id', ['value' => $request->id]); ?>
        </div>
    </div>
    <?= $this->Form->end(); ?>
<?php else: ?>
    <div class="row">
        <div class="small-12 columns">
            No requests to attach. <br/>
            <?= $this->Html->link('Back', ['action' => 'view', $request->id], [
                'class' => 'button'
            ]); ?>
        </div>
    </div>
<?php endif; ?>
