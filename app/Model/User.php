<?php
App::uses('AppModel', 'Model');
/**
 * User Model
 *
 */
class User extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'username';
    public $primaryKey = 'user_id';
    public $useTable = 'phpbb_users';

    public function CheckUserPermission($userId, $permissionId)
    {
        return false;
    }

    public function listUserGroups($userId) {
        $userId = (int) $userId;

        $sql = <<<EOQ
SELECT
    User.user_id,
    UserGroup.group_id,
    1 as is_member,
    UserGroup.group_leader
FROM
    phpbb_users AS User
    LEFT JOIN phpbb_user_group AS UserGroup ON User.user_id = UserGroup.user_id
WHERE
    User.user_id = $userId
EOQ;

        return $this->query($sql);
    }

    public function saveUserGroups($data)
    {
        foreach($data['group_id'] as $row => $groupId)
        {
            if($data['is_member'][$row]) {
                // set member data
                $this->addUserGroupRole($data['user_id'], $groupId,
                    $data['is_member'][$row], $data['group_leader'][$row]);
            }
            else {
                // attempt to delete row
                $this->deleteUserGroup($data['user_id'], $groupId);
            }
        }

        return true;
    }

    private function addUserGroupRole($userId, $groupId, $isMember, $isGroupLeader) {
        $userId = (int) $userId;
        $groupId = (int) $groupId;
        $isMember = (bool) $isMember;
        $isGroupLeader = (bool) $isGroupLeader;

        $sql = <<<EOQ
SELECT
    count(*) as access_rows
FROM
    phpbb_user_group
WHERE
    user_id = $userId
    AND group_id = $groupId;
EOQ;

        $result = $this->query($sql);

        if($result[0]['access_rows'] > 0) {
            $sql = <<<EOQ
UPDATE
    phpbb_user_group
SET
    group_leader = $isGroupLeader
WHERE
    group_id = $groupId
    AND user_id = $userId;
EOQ;

        }
        else {
            $sql = <<<EOQ
INSERT INTO
    phpbb_user_group
    (group_id, user_id, group_leader, user_pending)
VALUES
  ($groupId, $userId, $isGroupLeader, 0);
EOQ;

        }

        $this->query($sql);
        return true;
    }

    private function deleteUserGroup($userId, $groupId)
    {
        $userId = (int) $userId;
        $groupId = (int) $groupId;

        $sql = <<<EOQ
DELETE FROM
  phpbb_user_group
WHERE
  user_id = $userId
  AND group_id = $groupId
EOQ;

        $this->query($sql);
        return true;
    }
}
