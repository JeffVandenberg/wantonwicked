<?php
use App\Model\Entity\Role;
use App\View\AppView;

/* @var AppView $this */
/* @var Role $role */
/* @var bool $mayEdit */

$this->set('title_for_layout', $role->name);
$menu['Actions']['submenu']['Back'] = [
    'link' => [
        'action' => 'index'
    ]
];
if ($mayEdit) {
    $menu['Actions']['submenu']['Edit'] = [
        'link' => [
            'action' => 'edit',
            $role->id
        ]
    ];
    $menu['Actions']['submenu']['New Role'] = [
        'link' => [
            'action' => 'add'
        ]
    ];
}

$this->set('menu', $menu);
?>

<?php echo $this->Html->link('<< Back', ['action' => 'index'], ['class' => 'button']); ?>
<?php if($mayEdit): ?>
    <?php echo $this->Html->link('Edit', ['action' => 'edit', $role->id] , ['class' => 'button']); ?>
<?php endif; ?>
<div class="roles view">
    <dl>
        <dt><?php echo __('Name'); ?></dt>
        <dd>
            <?php echo h($role->name); ?>
            &nbsp;
        </dd>
        <dt><?php echo __('Description'); ?></dt>
        <dd>
            <?php echo $role->description; ?>
            &nbsp;
        </dd>
    </dl>
    <?php if ($mayEdit): ?>
        <h3><?php echo __('Permissions'); ?></h3>
        <?php if (!empty($role->permissions)): ?>
            <ul>
                <?php foreach ($role->permissions as $permission): ?>
                    <li>
                        <?php echo $permission->permission_name; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    <?php endif; ?>
</div>
