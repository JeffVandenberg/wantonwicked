<?php

use App\Model\Entity\Scene;
use App\View\AppView;
use classes\request\data\Request;

/* @var AppView $this */
/* @var Scene[] $sceneList ; */
/* @var Request[] $playerRequests */
/* @var string $plots */
$this->set('title_for_layout', "Wanton Wicked an Online World of Darkness Roleplaying Game");
?>

<div class="grid-x grid-padding-x grid-padding-y grid-margin-x">
    <div class="small-12 medium-8 cell" style=";">
        <h3>Current Plots</h3>
        <div class="tinymce-content">
            <?php if ($plots): ?>
                <?php echo $plots; ?>
            <?php else: ?>
                No plot information at this time.
            <?php endif; ?>
        </div>
        <h3>News</h3>
        <div class="tinymce-content">
            <?php echo $content; ?>
        </div>
    </div>
    <div class="small-12 medium-4 cell">
        <h3>Requests</h3>
        <?php if ($this->request->session()->read('Auth.User.user_id') == 1): ?>
            You need to <a href="/forum/ucp.php?mode=login&redirect=/">Sign in</a> or <a href="/forum/ucp.php?mode=register&redirect=/">Register</a>.
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
                        <td><a href="/request.php?action=view&request_id=<?php echo $request->Id; ?>"><?php echo $request->Title; ?></a></td>
                        <td><?php echo $request->RequestStatus->Name; ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
        <h3>Scenes</h3>
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
            No upcoming Scenes
        <?php endif; ?>
    </div>
    <!--    <div class="small-12 medium-8 cell" style="">-->
    <!--        Your ST Requests needing attention (staff)-->
    <!--    </div>-->
    <!--    <div class="medium-4 cell hide-for-small-only">-->
    <!--        Character Dashboard Links (registered users)-->
    <!--    </div>-->
</div>
<div class="row">
    <div class="small-12 medium-4 column">
        <h2>Log In OOC</h2>
        <form method="post" action="/chat/index.php">
            Name: <input type="text" name="username"/>
            <button type="submit" class="button">Log in</button>
        </form>
    </div>
</div>

