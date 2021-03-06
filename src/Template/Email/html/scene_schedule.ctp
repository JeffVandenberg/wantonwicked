<?php

use App\Model\Entity\Scene;
use App\View\AppView;

/* @var AppView $this */
/* @var Scene $newScene */
/* @var Scene $oldScene */
?>

<p>The scheduled time for a scene you have a character participating in has changed. Here is the updated
    Information.</p>
<p>Scene: <?php echo $this->Html->link($newScene->name,
        [
            'controller' => 'scenes',
            'action' => 'view',
            $oldScene->slug
        ],
        [
            'fullBase' => true
        ]
    );
    ?></p>
<p>Old Run Time: <?php echo date('Y-m-d g:i:s A', strtotime($oldScene->run_on_date)); ?></p>
<p>New Run Time: <?php echo date('Y-m-d g:i:s A', strtotime($newScene->run_on_date)); ?></p>
