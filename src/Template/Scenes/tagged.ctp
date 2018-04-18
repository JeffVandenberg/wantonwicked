<?php

use App\Model\Entity\Scene;
use App\View\AppView;
/**
 * @var AppView $this
 * @var Scene[] $scenes
 * @var string $tag
 */

$this->set('title_for_layout', 'Scenes tagged with ' . $tag);
?>

<table class="stack">
    <thead>
    <tr>
        <th>Scene</th>
        <th>Summary</th>
        <th>Scheduled For</th>
    </tr>
    </thead>
    <?php foreach($scenes as $scene): ?>
        <tr>
            <td>
                <?php echo $this->Html->link($scene->name, ['action' => 'view', $scene->slug]); ?>
            </td>
            <td><?php echo $scene->summary; ?></td>
            <td>
                <?php echo date('l, Y-m-d g:i A', strtotime($scene->run_on_date)); ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
