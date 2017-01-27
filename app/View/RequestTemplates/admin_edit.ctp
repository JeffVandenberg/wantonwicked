<?php /* @var View $this */ ?>
<?php $this->set('title_for_layout', 'Edit Request Template'); ?>

<div class="requestTemplates form">
<?php echo $this->Form->create('RequestTemplate'); ?>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('name');
		echo $this->Form->input('description');
        echo $this->Form->input('content', array(
            'class' => 'tinymce-input'
        ));
	?>
<?php echo $this->Form->end(__('Submit')); ?>
</div>

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
