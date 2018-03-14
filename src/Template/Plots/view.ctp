<?php
/**
 * @var \App\View\AppView $this
 * @var bool $isPlotManager
 * @var bool $isPlotViewer
 * @var \App\Model\Entity\Plot $plot
 */
$this->set('title_for_layout', $plot->name);
?>
<div class="plots form">
    <div>
        <?php echo $this->Html->link('<< Back', ['action' => 'index'], ['class' => 'button']); ?>
        <?php if($isPlotManager): ?>
            <?php echo $this->Html->link('Edit', ['action' => 'edit', $plot->slug], ['class' => 'button']); ?>
            <?php echo $this->Html->link('Add Character', ['action' => 'add-character', $plot->slug], ['class' => 'button']); ?>
            <?php echo $this->Html->link('Add Scene', ['action' => 'add-scene', $plot->slug], ['class' => 'button']); ?>
        <?php endif; ?>
    </div>
    <div class="row align-top">
        <div class="small-12 column tinymce-content">
            <h4>Description</h4>
            <?php echo $plot->description; ?>
        </div>
        <?php if($isPlotManager || $isPlotViewer): ?>
            <div class="small-12 column tinymce-content">
                <h4>Admin Notes</h4>
                <?php echo $plot->admin_notes; ?>
            </div>
        <?php endif; ?>
        <div class="small-12 column">
            <h4>Details</h4>
        </div>
        <div class="small-4 medium-1 column">
            <label>Run By</label>
        </div>
        <div class="small-8 medium-2 column">
            <?php echo $plot->run_by->username; ?>
        </div>
        <div class="small-4 medium-1 column">
            <label>Status</label>
        </div>
        <div class="small-8 medium-2 column">
            <?php echo $plot->plot_status->name; ?>
        </div>
        <div class="small-4 medium-2 column">
            <label>Visibility</label>
        </div>
        <div class="small-8 medium-1 column">
            <?php echo $plot->plot_visibility->name; ?>
        </div>
        <div class="small-12 medium-6 column">
            <h4>Participating Characters</h4>
            <?php if (count($plot->plot_characters)): ?>
                <table>
                    <?php foreach ($plot->plot_characters as $character): ?>
                        <tr>
                            <td>
                                <strong>
                                    <?php echo $this->Html->link(
                                        $character->character->character_name,
                                        [
                                            'controller' => 'characters',
                                            'action' => 'view',
                                            $character->character->slug
                                        ]
                                    ); ?>
                                </strong>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <?php echo $character->note; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php else: ?>
                No Characters
            <?php endif; ?>
        </div>
        <div class="small-12 medium-6 column">
            <h4>Participating Scenes</h4>
            <?php if (count($plot->plot_scenes)): ?>
                <table>
                    <?php foreach ($plot->plot_scenes as $scene): ?>
                        <tr>
                            <td>
                                <strong>
                                    <?php echo $this->Html->link(
                                            $scene->scene->name,
                                            [
                                                'controller' => 'scenes',
                                                'action' => 'view',
                                                $scene->scene->slug
                                            ]
                                    ); ?>
                                </strong>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <?php echo $scene->note; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php else: ?>
                No Scenes
            <?php endif; ?>
        </div>
    </div>
</div>
