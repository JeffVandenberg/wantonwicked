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
                    <?= $content; ?>
                </div>
            <?php endif; ?>
            <div class="small-12 medium-6 cell" style=";">
                <h3 class="float-left">Current Plots</h3>
                <?php if ($isPlotManager): ?>
                    <div class="button-group float-right">
                        <a class="button small" href="/plots/add">New</a>
                    </div>
                <?php endif; ?>
                <?php if (count($plotList)): ?>
                    <table class="stack">
                        <thead>
                        <tr>
                            <th>
                                Title
                            </th>
                            <th>
                                Run By
                            </th>
                        </tr>
                        </thead>
                        <?php foreach ($plotList as $plot): ?>
                            <tr>
                                <td>
                                    <?=
                                    $this->Html->link(
                                        $plot->name,
                                        [
                                            'controller' => 'plots',
                                            'action' => 'view',
                                            $plot->slug
                                        ]
                                    ); ?>
                                </td>
                                <td>
                                    <?= $plot->run_by->username; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                <?php else: ?>
                    No current plots. Staff is slacking!
                <?php endif; ?>
                <?php if ($isLoggedIn): ?>
                    <h3 class="float-left">
                        Characters
                    </h3>
                    <div class="button-group float-right">
                        <a class="button small" href="/characters/add">New</a>
                    </div>
                    <?php if (isset($characterList) && count($characterList) > 0): ?>
                        <table>
                            <tr>
                                <th>
                                    Name
                                </th>
                                <th>
                                    Actions
                                </th>
                            </tr>
                            <?php foreach ($characterList as $character): ?>
                                <?php $identifier = $character->slug ?: $character->id; ?>
                                <tr>
                                    <td>
                                        <?php if ($character->character_status_id == CharacterStatus::NEW_CHARACTER): ?>
                                            <div class="badge has-tip" data-tooltip title="New"><i class="fi-x"></i>
                                            </div>
                                        <?php elseif ($character->character_status_id == CharacterStatus::ACTIVE): ?>
                                            <div class="success badge has-tip" data-tooltip title="Sanctioned (Active)">
                                                <i
                                                        class="fi-check"></i></div>
                                        <?php elseif ($character->character_status_id == CharacterStatus::IDLE): ?>
                                            <div class="warning badge has-tip" data-tooltip title="Sanctioned (Idle)"><i
                                                        class="fi-check"></i></div>
                                        <?php elseif ($character->character_status_id == CharacterStatus::INACTIVE): ?>
                                            <div class="secondary badge has-tip" data-tooltip
                                                 title="Sanctioned (Inactive)"><i
                                                        class="fi-check"></i></div>
                                        <?php elseif ($character->character_status_id == CharacterStatus::UNSANCTIONED): ?>
                                            <div class="alert badge has-tip" data-tooltip title="Desanctioned"><i
                                                        class="fi-x"></i></div>
                                        <?php endif; ?>
                                        <?php echo htmlspecialchars($character->character_name); ?>
                                    </td>
                                    <td>
                                        <div class="button-group float-right">
                                            <a href="/character.php?action=interface&character_id=<?php echo $character->id; ?>"
                                               target="_blank" class="button float-right">Interface
                                            </a>
                                            <button class="dropdown button arrow-only" type="button"
                                                    data-toggle="<?php echo $character->id; ?>-dropdown">
                                                <span class="show-for-sr">Show menu</span>
                                            </button>
                                            <div class="dropdown-pane bottom right"
                                                 id="<?php echo $character->id; ?>-dropdown"
                                                 data-dropdown data-auto-focus="true">
                                                <ul class="vertical menu">
                                                    <li>
                                                        <a href="/characters/viewOwn/<?php echo $identifier; ?>">Sheet</a>
                                                    </li>
                                                    <?php if ($character->isSanctioned()): ?>
                                                        <li>
                                                            <a href="/characters/beats/<?php echo $character->slug; ?>">
                                                                Beats
                                                            </a>
                                                        </li>
                                                    <?php endif; ?>
                                                    <li>
                                                        <?= $this->Html->link('Requests',
                                                            [
                                                                'controller' => 'requests',
                                                                'action' => 'character',
                                                                $character->slug
                                                            ]
                                                        ); ?>
                                                    </li>
                                                    <li>
                                                        <a href="/dieroller.php?action=character&character_id=<?= $character->id ?>">Diceroller</a>
                                                    </li>
                                                    <li>
                                                        <a href="/chat?character_id=<?php echo $character->id; ?>"
                                                           target="_blank" class="">Chat</a>
                                                    </li>
                                                    <li>
                                                        <a href="/wiki/Players/<?php echo preg_replace('/[^A-Za-z0-9]/', '', $character->character_name); ?>">Profile
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </table>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
            <div class="small-12 medium-6 cell">
                <h3 class="float-left">Requests</h3>
                <?php if ($isLoggedIn): ?>
                    <div class="button-group float-right">
                        <a class="button small" href="/requests/add">New</a>
                    </div>
                <?php endif; ?>
                <?php if ((int)$this->request->getSession()->read('Auth.User.user_id') === 1): ?>
                    <div style="clear: both;">
                        You need to <a href="/forum/ucp.php?mode=login&redirect=/">Sign in</a> or <a
                                href="/forum/ucp.php?mode=register&redirect=/">Register</a>.
                    </div>
                <?php else: ?>
                    <table class="stack">
                        <thead>
                        <tr>
                            <th>Request</th>
                            <th>Status</th>
                        </tr>
                        </thead>
                        <?php foreach ($playerRequests as $request): ?>
                            <tr>
                                <td>
                                    <?= $this->Html->link($request->title, [
                                        'controller' => 'requests',
                                        'action' => 'view',
                                        $request->id
                                    ]); ?>
                                </td>
                                <td><?php echo $request->request_status->name; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                <?php endif; ?>
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
