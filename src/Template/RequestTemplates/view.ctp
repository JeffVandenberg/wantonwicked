<?php
use App\Model\Entity\RequestTemplate;
use App\View\AppView;
/* @var AppView $this */
/* @var RequestTemplate $requestTemplate */

$this->set('title_for_layout', 'Request Template: ' . $requestTemplate->name);
?>

<div class="row">
    <div class="small-12 columns">
        <?php echo $this->Html->link('<< Back', ['action' => 'index'], ['class' => 'button']); ?>
        <?php echo $this->Html->link('Edit', ['action' => 'edit', $requestTemplate->id], ['class' => 'button']); ?>
    </div>
    <div class="small-12 medium-2 columns">
        <?php echo __('Name'); ?>
    </div>
    <div class="small-12 medium-9 column">
        <?php echo h($requestTemplate->name); ?>
    </div>
    <div class="small-12 medium-2 columns">
        <?php echo __('Description'); ?>
    </div>
    <div class="small-12 medium-9 column">
        <?php echo h($requestTemplate->description); ?>
    </div>
    <div class="small-12 medium-2 columns">
        <?php echo __('Content'); ?>
    </div>
    <div class="small-12 medium-9 column tinymce-content">
        <?php echo $requestTemplate->content; ?>
    </div>
</div>
