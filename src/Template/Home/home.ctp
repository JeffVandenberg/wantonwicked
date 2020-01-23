<?php

use App\Model\Entity\Character;
use App\Model\Entity\CharacterStatus;
use App\Model\Entity\Plot;
use App\Model\Entity\Request;
use App\Model\Entity\Scene;
use App\View\AppView;

/**
 * @var AppView $this
 * @var Scene[] $sceneList
 * @var Plot[] $plotList
 * @var Character[] $characterList
 * @var Request[] $playerRequests
 * @var string $plots
 * @var bool $isPlotManager
 */
$this->set('title_for_layout', 'Wanton Wicked an Online World of Darkness Roleplaying Game');
if ($isLoggedIn) {
    $this->set('header_for_layout', 'Dashboard');
}
?>

<div class="grid-x">
    <div class="small-12 medium-8 large-10 cell">
        <div class="grid-x grid-padding-x grid-padding-y grid-margin-x">
            <?php if ($isLoggedIn): ?>
                <div class="small-12 cell">
                    <?= $this->Html->link('Login OOC', '/chat/', ['class' => 'button']); ?>
                </div>
            <?php endif; ?>
            <?php if (isset($content) && $content): ?>
                <div class="small-12 cell">
                    <?= $content ?>
                </div>
            <?php endif; ?>
            <div class="small-12 medium-6 cell" style=";">
                <plots-summary :show-new="<?= (!$isPlotManager) ? 'true' : 'false' ?>"></plots-summary>

                <character-summary :is-logged-in="<?= ((int)$this->request->getSession()->read('Auth.User.user_id') > 1) ? 'true' : 'false' ?>"></character-summary>
            </div>
            <div class="small-12 medium-6 cell">
                <requests-summary :is-logged-in="<?= ((int)$this->request->getSession()->read('Auth.User.user_id') > 1) ? 'true' : 'false' ?>"></requests-summary>

                <h3 class="float-left" style="clear: both;">Scenes</h3>
                <?php if ($isLoggedIn): ?>
                    <div class="button-group float-right">
                        <a class="button small" href="/scenes/add">New</a>
                    </div>
                <?php endif; ?>
                <?php if (count($sceneList)): ?>
                    <table class="stack">
                        <tr>
                            <th>Scene</th>
                            <th>Date</th>
                        </tr>
                        <?php foreach ($sceneList as $scene): ?>
                            <tr>
                                <td>
                                    <?php echo $this->Html->link(
                                        $scene->name,
                                        [
                                            'controller' => 'scenes',
                                            'action' => 'view',
                                            $scene->slug
                                        ]
                                    ); ?>
                                </td>
                                <td>
                                    <?php echo $scene->run_on_date; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                <?php else: ?>
                    <div style="clear:both;">
                        No upcoming scenes
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="small-12 medium-4 large-2 cell">
        <iframe src="https://discordapp.com/widget?id=454034748344631297&theme=dark" width="350" height="500"
                allowtransparency="true" frameborder="0"></iframe>
    </div>
    <!--    <div class="small-12 medium-8 cell" style="">-->
    <!--        Your ST Requests needing attention (staff)-->
    <!--    </div>-->
</div>
