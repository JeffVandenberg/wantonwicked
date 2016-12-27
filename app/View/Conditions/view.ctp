<?php
$this->set('title_for_layout', $condition['Condition']['name']);
if ($mayEdit) {
	$menu['Actions']['submenu']['Edit'] = [
		'link' => [
			'action' => 'edit',
			$condition['Condition']['slug']
		]
	];
}
$this->set('menu', $menu);
?>
<div class="row">
	<div class="small-12 columns">
		<div class="callout-navigation">
			<?php echo $this->Html->link('<< Back', ['action' => 'index'], ['class' => 'button']); ?>
		</div>
	</div>
</div>
<div class="conditions">
	<div class="row">
		<div class="column small-12 medium-4">
			<label>Condition Name</label>
			<?php echo h($condition['Condition']['name']); ?>
		</div>
		<div class="column small-12 medium-4">
			<label>Source (Book Name or Custom)</label>
			<?php echo h($condition['Condition']['source']); ?>
		</div>
		<div class="column small-12 medium-2">
			<label>Type</label>
			<?php echo $condition['ConditionType']['name']; ?>
		</div>
		<div class="column small-12 medium-2">
			<label>Persistent</label>
			<?php echo ($condition['Condition']['is_persistent']) ? 'Yes' : 'No'; ?>
		</div>
	</div>
	<div class="row">
		<div class="column small-12">
			<label>Description</label>
			<div class="tinymce-content"><?php echo $condition['Condition']['description']; ?></div>
		</div>
	</div>
	<div class="row">
		<div class="column small-12">
			<label>Resolution</label>
			<?php echo h($condition['Condition']['resolution'] ? $condition['Condition']['resolution'] : 'N/A'); ?>
		</div>
	</div>
	<div class="row">
		<div class="column small-12">
			<label>Beat</label>
			<?php echo h($condition['Condition']['beat'] ? $condition['Condition']['beat'] : 'N/A'); ?>
		</div>
	</div>
	<div class="row">
		<div class="small-12 medium-6 column">
			<label>Created By</label>
			<?php echo $condition['CreatedBy']['username']; ?><br />
			@ <?php echo $condition['Condition']['created']; ?>
		</div>
		<div class="small-12 medium-6 column">
			<label>Updated By</label>
			<?php echo $condition['UpdatedBy']['username']; ?><br />
			@ <?php echo $condition['Condition']['updated']; ?>
		</div>
	</div>
</div>
