<?php /* @var View $this */ ?>
<?php /* @var array $admins */ ?>
<?php /* @var array $sts */ ?>
<?php /* @var array $assts */ ?>
<?php /* @var array $wikis */ ?>
<?php $this->set('title_for_layout', "Wanton Wicked Staff"); ?>

<div style="clear:both;"></div>
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
                <?php echo $user['R']['role_name']; ?>
            </td>
            <td>
                <?php echo $user[0]['groups']; ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

