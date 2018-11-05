<?php
/**
 * @var Request $request
 * @var AppView $this
 */

use App\Model\Entity\Request;
use App\View\AppView;

?>
<p>Your request has been updated! Here are the details.</p>
<p>Your Request: <?= $request->title ?></p>
<p>New Status: <?= $status ?></p>
<p>By Storyteller: <?= $username ?></p>
<p><strong>Note</strong><br/>
    <?= $note ?>
</p>
<p>
    <?= $this->Html->link('View Request', ['controller' => 'requests', 'action' => 'view', $request->id], [
            'fullBase' => true
    ]); ?>
</p>
