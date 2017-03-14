<?php /* @var View $this */ ?>
<?php /* @var array $admins */ ?>
<?php /* @var array $sts */ ?>
<?php /* @var array $assts */ ?>
<?php /* @var array $wikis */ ?>
<?php $this->set('title_for_layout', "Wanton Wicked Staff"); ?>

<div style="text-align: center;">
    <?php echo $this->Html->link('Master list of roles', ['controller' => 'roles', 'action' => 'index']); ?>
</div>
<h2>
    Our Staff
</h2>
<table>
    <tr>
        <th>
            Name
        </th>
        <th>
            Role
        </th>
        <th>
            Groups
        </th>
    </tr>
    <?php foreach($staff as $user): ?>
        <tr>
            <td>
                <?php echo $user['U']['username']; ?>
            </td>
            <td>
                <?php echo $this->Html->link(
                    $user['R']['role_name'],
                    [
                        'controller' => 'roles',
                        'action' => 'view',
                        $user['U']['role_id']
                    ])
                ; ?>
            </td>
            <td>
                <?php echo $user[0]['groups']; ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

