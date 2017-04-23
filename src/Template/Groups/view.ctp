<?php
use App\Model\Entity\Group;
use App\View\AppView;

/* @var AppView $this */
/* @var Group $group */
$this->set('title_for_layout', 'View Group: ' . $group->name); 
?>

<div class="groups view">
    <a href="/groups" class="button">&lt;&lt; Back</a>
    <dl>
        <dt><?php echo __('Name'); ?></dt>
        <dd>
            <?php echo h($group->name); ?>
            &nbsp;
        </dd>
        <dt><?php echo __('Group Type'); ?></dt>
        <dd>
            <?php echo $this->Html->link($group->group_type->name,
                array('controller' => 'group_types', 'action' => 'view', $group->group_type_id)); ?>
            &nbsp;
        </dd>
        <dt><?php echo __('Is Deleted'); ?></dt>
        <dd>
            <?php echo h($group->is_deleted); ?>
            &nbsp;
        </dd>
        <dt><?php echo __('Created By'); ?></dt>
        <dd>
            <?php echo h($group->created_by); ?>
            &nbsp;
        </dd>
    </dl>
</div>
