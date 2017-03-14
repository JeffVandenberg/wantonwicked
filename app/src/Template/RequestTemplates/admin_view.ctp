<?php /* @var View $this */ ?>
<?php $this->set('title_for_layout', 'Request Template: ' . $requestTemplate['RequestTemplate']['name']); ?>

<div class="requestTemplates view">
	<dl>
		<dt><?php echo __('Name'); ?></dt>
		<dd>
			<?php echo h($requestTemplate['RequestTemplate']['name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Description'); ?></dt>
		<dd>
			<?php echo h($requestTemplate['RequestTemplate']['description']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Content'); ?></dt>
		<dd>
            <div class="tinymce-content">
			<?php echo $requestTemplate['RequestTemplate']['content']; ?>
            </div>
            &nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Form->postLink(__('Delete Request Template'), array('action' => 'delete', $requestTemplate['RequestTemplate']['id']), null, __('Are you sure you want to delete # {0}?', $requestTemplate['RequestTemplate']['id'])); ?> </li>
	</ul>
</div>
