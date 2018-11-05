<?php

use App\Model\Entity\Scene;
use App\View\AppView;

/* @var AppView $this */
/* @var Scene $scene */
?>

<p>The following scene has been cancelled by the organizer.</p>
<p>Scene: <?php echo $this->Html->link($scene->name,
        [
            'controller' => 'scenes',
            'action' => 'view',
            $scene->name
        ],
        [
            'fullBase' => true
        ]
    ); ?></p>
