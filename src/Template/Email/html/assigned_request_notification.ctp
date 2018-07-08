<?php
/**
 * @var Request $request
 * @var AppView $this
 */

use App\Model\Entity\Request;
use App\View\AppView;

?>
<p>You have been assigned a request.</p>
<p>Request: <?= $request->title ?></p>
<p>By Storyteller: <?= $username ?></p>
<p><strong>Note</strong><br/>
    <?= $note ?>
</p>
<p>
    <?= $this->Html->link('View Request', ['controller' => 'requests', 'action' => 'view', $request->id]); ?>
</p>
