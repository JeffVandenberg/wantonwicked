<?php /* @var View $this */ ?>
<?php
$this->set('title_for_layout', 'Scene: ' . $scene['Scene']['name']);
$menu['Actions']['submenu']['Back'] = array(
    'link' => array(
        'action' => 'index'
    )
);

if ($isLoggedIn && ($scene['Scene']['scene_status_id'] == SceneStatus::Open)) {
    $menu['Actions']['submenu']['Join Scene'] = array(
        'link' => array(
            'action' => 'join',
            $scene['Scene']['slug']
        )
    );
}
if ($mayEdit || ($scene['Scene']['created_by_id'] == AuthComponent::user('user'))) {
    $menu['Actions']['submenu']['Edit Scene'] = array(
        'link' => array(
            'action' => 'edit',
            $scene['Scene']['slug']
        )
    );
    $menu['Actions']['submenu']['Player Preferences'] = [
        'link' => [
            'action' => 'player_preferences',
            $scene['Scene']['slug']
        ]
    ];
    if ($scene['Scene']['scene_status_id'] == SceneStatus::Open) {
        $menu['Actions']['submenu']['Complete Scene'] = array(
            'link' => array(
                'action' => 'complete',
                $scene['Scene']['slug']
            ),
            'id' => 'complete-link'
        );
        $menu['Actions']['submenu']['Cancel Scene']   = array(
            'link' => array(
                'action' => 'cancel',
                $scene['Scene']['slug']
            ),
            'id' => 'cancel-link'
        );
    }
}
$this->set('menu', $menu);
?>

<?php if ($isLoggedIn && ($scene['Scene']['scene_status_id'] == SceneStatus::Open)): ?>
    <?php echo $this->Html->link('Join Scene', array('action' => 'join', $scene['Scene']['slug']), ['class' => 'button']); ?>
<?php endif; ?>

<table>
    <tr>
        <th colspan="2">
            This scene is: <?php echo $scene['SceneStatus']['name']; ?>
        </th>
    </tr>
    <tr>
        <td colspan="2">
            <b>Summary</b><br/>
            <?php echo h($scene['Scene']['summary']); ?>
        </td>
    </tr>
    <tr>
        <td>
            <b>Run By</b>
            <?php echo h($scene['RunBy']['username']); ?>
        </td>
        <td>
            <b>Scheduled For</b>
            <?php echo date('Y-m-d g:i A', strtotime($scene['Scene']['run_on_date'])); ?>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <b>Description</b>

            <div class="tinymce-content">
                <?php echo $scene['Scene']['description']; ?>
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
                    <?php echo $sceneCharacter['Character']['character_name']; ?>
                    <?php if(AuthComponent::user('user_id') == $sceneCharacter['Character']['user_id']): ?>
                    <span id="leave-scene" class="clickable link" scene-id="<?php echo $scene['Scene']['slug']; ?>"
                          character-id="<?php echo $sceneCharacter['SceneCharacter']['character_id']; ?>"
                        >
                        Leave
                    </span>
                    <?php endif; ?>
                    <br />
                </dt>
                <dd>
                    <span class="secondary-text">Joined: <?php echo date('Y-m-d g:i A', strtotime($sceneCharacter['SceneCharacter']['added_on'])); ?></span><br />
                    <div class="tinymce-content"><?php echo $sceneCharacter['SceneCharacter']['note']; ?></div>
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
