<?php
use App\Model\Entity\Scene;
use App\View\AppView;

/* @var AppView $this */
/* @var Scene $scene */
?>

<p>The following scene has been cancelled by the organizer.</p>
<p>Scene: <?php echo $this->Html->link($scene->name,
        array(
            'full_base' => true,
            'controller' => 'scenes',
            'action' => 'view',
            $scene->name
        )
    ); ?></p>
