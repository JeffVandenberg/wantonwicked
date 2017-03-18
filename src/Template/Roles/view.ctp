<?php /* @var View $this */;
$this->set('title_for_layout', $role['Role']['name']);
$menu['Actions']['submenu']['Back'] = [
    'link' => [
        'action' => 'index'
    ]
];
if ($mayEdit) {
    $menu['Actions']['submenu']['Edit'] = [
        'link' => [
            'action' => 'edit',
            $role['Role']['id']
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

<div class="roles view">
    <dl>
        <dt><?php echo __('Name'); ?></dt>
        <dd>
            <?php echo h($role['Role']['name']); ?>
            &nbsp;
        </dd>
        <dt><?php echo __('Description'); ?></dt>
        <dd>
            <?php echo $role['Role']['description']; ?>
            &nbsp;
        </dd>
    </dl>
    <?php if ($mayEdit): ?>
        <h3><?php echo __('Permissions'); ?></h3>
        <?php if (!empty($role['Permission'])): ?>
            <ul>
                <?php foreach ($role['Permission'] as $permission): ?>
                    <li>
                        <?php echo $permission['permission_name']; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    <?php endif; ?>
</div>
