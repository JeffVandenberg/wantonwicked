<?php
use App\View\AppView;
/* @var AppView $this */
/* @var array $admins */
/* @var array $sts */
/* @var array $assts */
/* @var array $wikis */
$this->set('title_for_layout', "Wanton Wicked Staff");
?>

<div>
    <?php echo $this->Html->link('All site roles', ['controller' => 'roles', 'action' => 'index'], ['class' => 'button']); ?>
</div>
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
                <?php echo $user['username']; ?>
            </td>
            <td>
                <?php echo $this->Html->link(
                    $user['role_name'],
                    [
                        'controller' => 'roles',
                        'action' => 'view',
                        $user['role_id']
                    ])
                ; ?>
            </td>
            <td>
                <?php echo $user['groups']; ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

