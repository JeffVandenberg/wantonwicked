<?php
use App\View\AppView;

/* @var AppView $this */
$this->set('title_for_layout', 'Add Group');
?>

<div class="groups form">
    <?php echo $this->Form->create('Group'); ?>
    <?php
    echo $this->Form->control('name');
    echo $this->Form->control('group_type_id');
    echo $this->Form->control('RequestType', array(
        'size' => 6
    ));
    ?>
    <?php echo $this->Form->end(__('Submit')); ?>
</div>
