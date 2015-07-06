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
            <?php echo h($scene['Scene']['run_on_date']); ?>
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
<div style="width: 50%;">
    <h3>Participating Characters</h3>
    <?php if (count($sceneCharacters)): ?>
        <dl>
            <?php foreach ($sceneCharacters as $sceneCharacter): ?>
                <dt><?php echo $sceneCharacter['Character']['character_name']; ?></dt>
                <dd><?php echo $sceneCharacter['SceneCharacter']['note']; ?></dd>
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
        $("#complete-link").click(function() {
            return confirm('Are you sure you want to COMPLETE this scene?');
        });
        $("#cancel-link").click(function() {
            return confirm('Are you sure you want to CANCEL this scene?');
        });
    })
</script>
