<?php
use App\Model\Entity\User;
use App\View\AppView;

/* @var AppView $this */
/* @var array $userGroups */
/* @var array $groups */
/* @var User $user */

$this->set('title_for_layout', 'Assign User Groups'); ?>

<?php if(isset($user)): ?>
    <h3><?php echo $user->username; ?> Groups</h3>
    <?php echo $this->Html->link('Cancel', '', ['class' => 'button']); ?>
<?php else: ?>
    <form method="post">
        <?php echo $this->Form->control('username', ['id' => 'username']); ?>
        <?php echo $this->Form->hidden('user_id', ['id' => 'user_id']); ?>
        <button class="button" type="submit" id="load-user">Load User</button>
    </form>
<?php endif; ?>

<?php if(isset($groups)): ?>
<form method="post">
    <?php echo $this->Form->hidden('user_id', array('value' => $user->user_id)); ?>
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
    <tr>
        <td>
            All
        </td>
        <td>
            <input type="checkbox" id="member-all" />
        </td>
        <td>
            <input type="checkbox" id="moderator-all" />
        </td>
    </tr>
    <?php foreach ($groups as $groupId => $group): ?>
        <tr>
            <td>
                <?php echo $group; ?>
                <?php echo $this->Form->hidden('group_id[]', array(
                    'name' => 'group_id[]',
                    'value' => $groupId)); ?>
            </td>
            <td>
                <?php echo $this->Form->checkbox('is_member['. $groupId .']', array(
                    'name' => 'is_member[' . $groupId .']',
                    'value' => 1,
                    'checked' => $userGroups[$groupId]['is_member'],
                    'class' => 'member')
                ); ?>
            </td>
            <td>
                <?php echo $this->Form->checkbox('group_leader['. $groupId .']', array(
                    'value' => 1,
                    'name' => 'group_leader[' . $groupId .']',
                    'checked' => $userGroups[$groupId]['group_leader'],
                    'class' => 'moderator'
                )); ?>
            </td>
        </tr>
    <?php endforeach; ?>
    <tr>
        <th colspan="3" style="text-align: center;">
            <button class="button" name="action" value="Update Groups">Update Groups</button>
        </th>
    </tr>
</table>
</form>
<?php endif; ?>
<script type="text/javascript">
    $(function() {
        $("#load-user").prop('disabled', true);
        $("#username").autocomplete({
            serviceUrl: '/users.php?action=search&email=0',
            minChars: 2,
            autoSelectFirst: true,
            onSelect: function (ui) {
                $("#user_id").val(ui.data);
                $("#username").val(ui.value);
                $("#load-user").prop('disabled', false);
                return false;
            }
        });
        $("#member-all").click(function() {
            $(".member").prop("checked", $(this).prop('checked'));
        });
        $("#moderator-all").click(function() {
            $(".moderator").prop("checked", $(this).prop('checked'));
        });
    });
</script>
