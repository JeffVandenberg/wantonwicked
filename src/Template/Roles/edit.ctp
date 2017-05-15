<?php
use App\Model\Entity\Permission;
use App\Model\Entity\Role;
use App\View\AppView;

/* @var AppView $this */
/* @var Role $role */
/* @var array $permissions */
$this->set('title_for_layout', 'Edit Role');
?>

<?php echo $this->Form->create($role); ?>
<div class="roles form row">
    <div class="small-12 columns">
        <?php echo $this->Form->control('name'); ?>
    </div>
    <div class="small-12 medium-9 columns">
        <?php echo $this->Form->control('description', [
            'class' => 'tinymce-textarea'
        ]); ?>
    </div>
    <div class="small-12 medium-3 columns">
        <?php
        $selected = array_map(function (Permission $item) {
            return $item->id;
        }, $role->permissions);
        echo $this->Form->label('permissions', 'Permissions');
        echo $this->Form->select('permissions', $permissions, [
            'value' => $selected,
            'multiple' => 'checkbox',
        ]); ?>
        <?php echo $this->Form->control('id'); ?>
    </div>
    <div class="small-12 columns text-center">
        <button type="submit" name="action" value="Create" class="button">Create</button>
        <button type="submit" name="action" value="Cancel" class="button">Cancel</button>
    </div>
</div>
<?php echo $this->Form->end(); ?>
