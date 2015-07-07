<?php /* @var View $this */ ?>

<p>The following scene has been cancelled by the organizer.</p>
<p>Scene: <?php echo $this->Html->link($scene['Scene']['name'],
                                       array(
                                           'full_base'  => true,
                                           'controller' => 'scenes',
                                           'action'     => 'view',
                                           $scene['Scene']['slug']
                                       )
    ); ?></p>