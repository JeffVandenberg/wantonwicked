<?php /* @var View $this */ ?>

<p>A new player has joined your scene.</p>
<p>Scene: <?php echo $this->Html->link($scene['Scene']['name'], array('controller' => 'scenes', 'action' => 'view', $scene['Scene']['slug'])); ?></p>
<p>Character: <?php echo $character['Character']['name']; ?></p>
<p>Note: <?php echo $sceneCharacter['SceneCharacter']['note']; ?></p>