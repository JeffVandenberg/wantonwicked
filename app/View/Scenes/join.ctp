<?php /* @var View $this */ ?>
<?php
$this->set('title_for_layout', 'Join Scene: ' . $scene['Scene']['name']);
$menu['Actions']['submenu']['Back'] = array(
    'link' => array(
        'action' => 'index'
    )
);
$this->set('menu', $menu);
?>

<div>
</div>
<form method="post" action="<?php echo $this->Html->url(); ?>">
    <div style="">
        <?php echo $this->Form->hidden('scene_id', array('value' => $scene['Scene']['id'])); ?>
        Select which character is partipating in the scene:
        <?php echo $this->Form->select('character_id', $characters, array('required' => true)); ?>
    </div>
    <div>
        Extra notes on their participation:
        <?php echo $this->Form->textarea('note', array('class' => 'tinymce-textarea')); ?>
    </div>
    <div style="text-align: center;">
        <?php echo $this->Form->submit('Join', array('name' => 'action', 'div' => false)); ?>
        <?php echo $this->Form->submit('Cancel', array('name' => 'action', 'div' => false)); ?>
    </div>
</form>
<script type="application/javascript">
</script>