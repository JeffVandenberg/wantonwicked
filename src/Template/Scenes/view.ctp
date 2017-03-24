<?php
use App\Model\Entity\Scene;
use App\Model\Entity\SceneCharacter;
use App\Model\Entity\SceneStatus;
use Cake\View\View;

/* @var View $this */
/* @var Scene $scene */
/* @var SceneCharacter[] $sceneCharacters */

$this->set('title_for_layout', 'Scene: ' . $scene->name);
$menu['Actions']['submenu']['Back'] = array(
    'link' => array(
        'action' => 'index'
    )
);

if ($isLoggedIn && ($scene->scene_status_id == SceneStatus::Open)) {
    $menu['Actions']['submenu']['Join Scene'] = array(
        'link' => array(
            'action' => 'join',
            $scene->slug
        )
    );
}
if ($mayEdit || ($scene->created_by_id == $this->request->session()->read('Auth.User.user_id'))) {
    $menu['Actions']['submenu']['Edit Scene'] = array(
        'link' => array(
            'action' => 'edit',
            $scene->slug
        )
    );
    $menu['Actions']['submenu']['Player Preferences'] = [
        'link' => [
            'action' => 'player_preferences',
            $scene->slug
        ]
    ];
    if ($scene['Scene']['scene_status_id'] == SceneStatus::Open) {
        $menu['Actions']['submenu']['Complete Scene'] = [
            'link' => [
                'action' => 'complete',
                $scene->slug
            ],
            'id' => 'complete-link'
        ];
        $menu['Actions']['submenu']['Cancel Scene']   = [
            'link' => [
                'action' => 'cancel',
                $scene->slug
            ],
            'id' => 'cancel-link'
        ];
    }
}
$this->set('menu', $menu);
?>

<?php if ($isLoggedIn && ($scene->scene_status_id == SceneStatus::Open)): ?>
    <?php echo $this->Html->link('Join Scene', array('action' => 'join', $scene->slug), ['class' => 'button']); ?>
<?php endif; ?>

<table>
    <tr>
        <th colspan="2">
            This scene is: <?php echo $scene->scene_status->name; ?>
        </th>
    </tr>
    <tr>
        <td colspan="2">
            <b>Summary</b><br/>
            <?php echo h($scene->summary); ?>
        </td>
    </tr>
    <tr>
        <td>
            <b>Run By</b>
            <?php echo h($scene->run_by->username); ?>
        </td>
        <td>
            <b>Scheduled For</b>
            <?php echo date('l, Y-m-d g:i A', strtotime($scene->run_on_date)); ?>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <b>Tags</b>
            <ul class="tags">
<!--                --><?php //foreach($scene['Tag'] as $tag): ?>
<!--                    <li>--><?php //echo $this->Html->link($tag['name'], ['controller' => 'scenes', 'action' => 'tag', $tag['name']]); ?><!--</li>-->
<!--                --><?php //endforeach; ?>
            </ul>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <b>Description</b>
            <div class="tinymce-content">
                <?php echo $scene->description; ?>
            </div>
        </td>
    </tr>
</table>
<div style="width: 50%;margin-top: 10px;">
    <h3>Participating Characters</h3>
    <?php if (count($sceneCharacters)): ?>
        <dl>
            <?php foreach ($sceneCharacters as $sceneCharacter): ?>
                <dt>
                    <?php echo $sceneCharacter->character->character_name; ?>
                    <?php if($this->request->session()->read('Auth.User.user_id') == $sceneCharacter->character->user_id): ?>
                    <span id="leave-scene" class="clickable link" scene-id="<?php echo $scene['Scene']['slug']; ?>"
                          character-id="<?php echo $sceneCharacter['SceneCharacter']['character_id']; ?>"
                        >
                        Leave
                    </span>
                    <?php endif; ?>
                    <br />
                </dt>
                <dd>
                    <span class="secondary-text">Joined: <?php echo date('Y-m-d g:i A', strtotime($sceneCharacter->added_on)); ?></span><br />
                    <div class="tinymce-content"><?php echo $sceneCharacter->note; ?></div>
                </dd>
            <?php endforeach; ?>
        </dl>
    <?php else: ?>
        <div>
            None
        </div>
    <?php endif; ?>
</div>
<script type="application/javascript">
    $(function() {
        $("#leave-scene").click(function(e) {
           if(confirm('Are you sure you want to leave the scene?')) {
               window.document.location.href = '/scenes/leave/' + $(this).attr('scene-id') + '/' + $(this).attr('character-id');
           }
        });
        $("#complete-link").click(function() {
            return confirm('Are you sure you want to COMPLETE this scene?');
        });
        $("#cancel-link").click(function() {
            return confirm('Are you sure you want to CANCEL this scene?');
        });
    })
</script>
