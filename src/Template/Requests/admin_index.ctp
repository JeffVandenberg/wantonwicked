<?php
use App\View\AppView;

/* @var AppView $this */
$this->set('title_for_layout', 'Request System Administration');
?>

<ul>
    <li><?php echo $this->Html->link('Groups', array('controller' => 'groups')); ?></li>
    <li><?php echo $this->Html->link('Request Types', array('controller' => 'request_types')); ?></li>
    <li><?php echo $this->Html->link('Templates', array('controller' => 'request_templates')); ?></li>
</ul>
