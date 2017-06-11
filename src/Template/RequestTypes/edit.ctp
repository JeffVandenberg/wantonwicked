<?php
use App\Model\Entity\Group;
use App\Model\Entity\RequestType;
use App\View\AppView;

/* @var AppView $this */
/* @var RequestType $requestType */
/* @var Group[] $groups */

$this->set('title_for_layout', 'Edit Request Type: ' . $requestType->name);
?>

<?= $this->Form->create($requestType) ?>
<div class="rows">
    <div class="small-12 columns">
        <?php echo $this->Html->link('<< Back', ['action' => 'view', $requestType->id], ['class' => 'button']); ?>
    </div>
    <div class="small-12 columns">
        <?php echo $this->Form->control('name'); ?>
    </div>
    <div class="small-12 columns">
        <?php echo $this->Form->control('groups._ids', [
            'options' => $groups,
            'size' => 10
        ]); ?>
    </div>
    <div class="small-12 columns text-center">
        <?= $this->Form->button(__('Submit'), [
            'class' => 'button',
            'type' => 'submit'
        ]) ?>
    </div>
</div>
<?= $this->Form->end() ?>
