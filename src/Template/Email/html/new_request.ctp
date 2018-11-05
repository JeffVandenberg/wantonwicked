<?php
use App\Model\Entity\Request;
use App\Model\Entity\RequestNote;
use App\View\AppView;

/**
 * @var AppView $this
 * @var Request $request
 * @var RequestNote $lastNote
 */

?>
<p>A New request has been submitted</p>
<p>Request: <?= $request->title; ?></p>
<p>From User: <?= $request->updated_by->username; ?></p>
<p><strong>Request:</strong><br />
    <?= $request->body; ?>
</p>
<p>
    <?= $this->Html->link('View Request', ['controller' => 'requests', 'action' => 'view', $request->id], [
            'fullBase' => true
    ]); ?>
</p>
