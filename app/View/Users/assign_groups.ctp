<?php /* @var View $this */ ?>
<?php /* @var array $userGroups */ ?>
<?php $this->set('title_for_layout', 'Assign User Groups'); ?>
<?php if($user): ?>
    <h3><?php echo $user['User']['username']; ?> Groups</h3>
    <?php echo $this->Html->link('Cancel', ''); ?>
<?php else: ?>
    <form method="post">
        <?php echo $this->Form->input('username'); ?>
        <?php echo $this->Form->hidden('user_id'); ?>
        <?php echo $this->Form->button('Load User', array('id' => 'load-user')); ?>
    </form>
<?php endif; ?>

<?php if($groups): ?>
<form method="post">
    <?php echo $this->Form->hidden('user_id', array('value' => $user['User']['user_id'])); ?>
<table>
    <thead>
    <tr>
        <th>
            Group Name
        </th>
        <th>
            Is Member
        </th>
        <th>
            Is Moderator
        </th>
    </tr>
    </thead>
    <?php foreach ($groups as $groupId => $group): ?>
        <tr>
            <td>
                <?php echo $group; ?>
                <?php echo $this->Form->hidden('group_id[]', array(
                    'name' => 'data[group_id][]',
                    'value' => $groupId)); ?>
            </td>
            <td>
                <?php echo $this->Form->checkbox('is_member['. $groupId .']', array(
                    'name' => 'data[is_member][' . $groupId .']',
                    'value' => 1,
                    'checked' => $userGroups[$groupId][0]['is_member'])); ?>
            </td>
            <td>
                <?php echo $this->Form->checkbox('group_leader['. $groupId .']', array(
                    'value' => 1,
                    'name' => 'data[group_leader][' . $groupId .']',
                    'checked' => $userGroups[$groupId]['UserGroup']['group_leader'])); ?>
            </td>
        </tr>
    <?php endforeach; ?>
    <tr>
        <th colspan="3" style="text-align: center;">
            <?php echo $this->Form->button('Update Groups', array('name' => 'action')); ?>
        </th>
    </tr>
</table>
</form>
<?php endif; ?>
<script type="text/javascript">
    $(function() {
        $("#load-user").prop('disabled', true);
        $("#username").autocomplete({
            source: '/users.php?action=search',
            minLength: 2,

            focus: function () {
                return false;
            },
            select: function (e, ui) {
                $("#user_id").val(ui.item.value);
                $("#username").val(ui.item.label);
                $("#load-user").prop('disabled', false);
                return false;
            }
        });
    });
</script>
