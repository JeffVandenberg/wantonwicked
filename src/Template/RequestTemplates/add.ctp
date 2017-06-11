<?php
use App\View\AppView;

/* @var AppView $this */
$this->set('title_for_layout', 'Add Request Template');
?>

<?php echo $this->Form->create('RequestTemplate'); ?>
    <div class="rows">
        <div class="small-12 columns">
            <?php echo $this->Form->control('name'); ?>
        </div>
        <div class="small-12 columns">
            <?php echo $this->Form->control('description'); ?>
        </div>
        <div class="small-12 columns">
            <?php echo $this->Form->control('content', [
                'class' => 'tinymce-textarea',
                'type' => 'textarea'
            ]); ?>
        </div>
        <div class="small-12 columns text-center" style="margin-top:15px;">
            <?php echo $this->Form->button('Save', [
                'name' => 'action',
                'class' => 'button',
                'type' => 'submit',
                'value' => 'save'
            ]); ?>
            <?php echo $this->Form->button('Cancel', [
                'name' => 'action',
                'class' => 'button',
                'type' => 'submit',
                'value' => 'cancel'
            ]); ?>
        </div>
    </div>
<?php echo $this->Form->end(); ?>
