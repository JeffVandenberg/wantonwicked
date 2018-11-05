<?php

use App\Model\Entity\Character;
use App\Model\Entity\Scene;
use App\Model\Entity\SceneCharacter;
use App\View\AppView;

/* @var AppView $this */
/* @var Scene $scene */
/* @var SceneCharacter $sceneCharacter */
/* @var Character $character */

?>

<p>A new player has joined your scene.</p>
<p>Scene: <?php echo $this->Html->link($scene->name,
        [
            'controller' => 'scenes',
            'action' => 'view',
            $scene->slug
        ],
        [
            'fullBase' => true
        ]
    ); ?></p>
<p>Character: <?php echo $character->character_name; ?></p>
<p>Note: <?php echo $sceneCharacter->note; ?></p>
