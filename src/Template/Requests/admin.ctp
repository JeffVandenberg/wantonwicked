<?php
use App\View\AppView;

/* @var AppView $this */
$this->set('title_for_layout', 'Request System Administration');
?>

<p><?php echo $this->Html->link('Manage Groups', array('controller' => 'groups')); ?></p>
<p><?php echo $this->Html->link('Manage Request Types', array('controller' => 'request_types')); ?></p>
<p><?php echo $this->Html->link('Manage Templates', array('controller' => 'request_templates')); ?></p>
