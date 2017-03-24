<?php
use App\Model\Entity\Character;
use App\Model\Entity\Scene;
use App\View\AppView;

/* @var AppView $this */
/* @var Scene $scene */
/* @var Character[] $characters */

$this->set('title_for_layout', 'Join Scene: ' . $scene->name);
$menu['Actions']['submenu']['Back'] = array(
    'link' => array(
        'action' => 'index'
    )
);
$this->set('menu', $menu);
?>

<div>
</div>
<form method="post" action="<?php echo $this->Url->build(); ?>">
    <div style="">
        <?php echo $this->Form->hidden('scene_id', array('value' => $scene->id)); ?>
        Select which character is partipating in the scene:
        <?php echo $this->Form->select('character_id', $characters, array('required' => true)); ?>
    </div>
    <div>
        <b>Summary</b><br />
        <?php echo $scene->summary; ?>
    </div>
    <div>
        Extra notes on their participation:
        <?php echo $this->Form->textarea('note', array('class' => 'tinymce-textarea')); ?>
    </div>
    <div style="text-align: center;">
        <button class="button" type="submit" name="action" value="Join">Join</button>
        <button class="button" type="submit" name="action" value="Cancel">Cancel</button>
    </div>
</form>
<script type="application/javascript">
</script>
