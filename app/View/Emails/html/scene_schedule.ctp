<?php /* @var View $this */ ?>

<p>The scheduled time for a scene you have a character participating in has changed. Here is the updated
    Information.</p>
<p>Scene: <?php echo $this->Html->link($newScene['Scene']['name'],
                                       array(
                                           'full_base' => true,
                                           'controller' => 'scenes',
                                           'action'     => 'view',
                                           $oldScene['Scene']['slug']
                                       )
    );
    ?></p>
<p>Old Run Time: <?php echo date('Y-m-d g:i:s A', strtotime($oldScene['Scene']['run_on_date'])); ?></p>
<p>New Run Time: <?php echo date('Y-m-d g:i:s A', strtotime($newScene['Scene']['run_on_date'])); ?></p>