<?php /* @var View $this */ ?>
<?php $this->set('title_for_layout', 'Add Request Template'); ?>

<div class="requestTemplates form">
<?php echo $this->Form->create('RequestTemplate'); ?>
	<?php
		echo $this->Form->input('name');
		echo $this->Form->input('description');
		echo $this->Form->input('content', array(
            'class' => 'tinymce-input'
        ));
	?>
<?php echo $this->Form->end(__('Submit')); ?>
</div>

<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('List Request Templates'), array('action' => 'index')); ?></li>
	</ul>
</div>
<?php $this->start('script'); ?>
<script type="text/javascript" src="/js/tinymce/tinymce.min.js"></script>
<?php $this->end(); ?>

<script type="text/javascript">
    tinymce.init({
        selector: "textarea.tinymce-input",
        menubar: false,
        height: 200,
        plugins: [
            "advlist autolink lists link image charmap print preview anchor",
            "searchreplace wordcount visualblocks code fullscreen",
            "insertdatetime media table contextmenu paste textcolor template"
        ],
        toolbar1: "undo redo | bold italic | forecolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | copy paste"
    });
</script>