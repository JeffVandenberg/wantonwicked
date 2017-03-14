<?php
namespace app\Model;

use App\Model\AppModel;

/**
 * User Model
 *
 */
class User extends AppModel
{

    /**
     * Display field
     *
     * @var string
     */
    public $displayField = 'username';
    public $primaryKey = 'user_id';
    public $useTable = 'phpbb_users';
    /**
     * hasAndBelongsToMany associations
     *
     * @var array
     */
    public $hasAndBelongsToMany = array(
        'Permission' => array(
            'className' => 'SitePermission',
            'joinTable' => 'permissions_users',
            'foreignKey' => 'user_id',
            'associationForeignKey' => 'permission_id',
            'unique' => 'keepExisting',
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'finderQuery' => '',
            'deleteQuery' => '',
            'insertQuery' => ''
        )
    );

    public function CheckUserPermission($userId, $permissionIds)
    {
        if (!$userId || !$permissionIds) {
            return false;
        }
        if (!is_array($permissionIds)) {
            $permissionIds = array($permissionIds);
        }
        $permissions = implode(',', $permissionIds);
        $count = $this->query("SELECT count(*) AS Count FROM permissions_users where user_id = $userId AND permission_id IN ($permissions);");
        return $count[0][0]['Count'] > 0;
    }

    public function CheckUserSupporterStatus($userId)
    {
        if (!$userId) {
            return false;
        }

        $date = date('Y-m-d H:i:s');
        $sql = <<<EOQ
SELECT
    count(user_id) AS Count
FROM
    supporters
WHERE
    user_id = $userId
    AND expires_on > '$date'
EOQ;
        $count = $this->query($sql);
        return $count[0][0]['Count'] > 0;
    }

    public function listUserGroups($userId)
    {
        $userId = (int)$userId;

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
        foreach ($data['group_id'] as $row => $groupId) {
            if ($data['is_member'][$groupId]) {
                // set member data
                $this->addUserGroupRole($data['user_id'], $groupId, $data['group_leader'][$groupId]);
            } else {
                // attempt to delete row
                $this->deleteUserGroup($data['user_id'], $groupId);
            }
        }
        $this->updateUserAclPermissions($data['user_id']);
        return true;
    }

    private function addUserGroupRole($userId, $groupId, $isGroupLeader)
    {
        $userId = (int)$userId;
        $groupId = (int)$groupId;
        $isGroupLeader = (int)$isGroupLeader;

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
        if ($result[0][0]['access_rows'] > 0) {
            $sql = <<<EOQ
UPDATE
    phpbb_user_group
SET
    group_leader = $isGroupLeader
WHERE
    group_id = $groupId
    AND user_id = $userId;
EOQ;

        } else {
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
        $userId = (int)$userId;
        $groupId = (int)$groupId;

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

    private function updateUserAclPermissions($userId)
    {
        $sql = <<<EOQ
UPDATE
  phpbb_users
SET
  user_permissions = '',
  user_perm_from = 0
WHERE
  user_id = $userId
EOQ;
        $this->query($sql);
    }

    public function addUserToSite($user)
    {
        // check if the username is already in use on the site
        $userNameClean = trim(strtolower($user['username']));

        $sql = <<<EOQ
SELECT
    count(*) AS count
FROM
    phpbb_users
WHERE
    username_clean = '$userNameClean'
EOQ;
        $data = $this->query($sql);
        if($data[0][0]['count'] > 0) {
            throw new Exception('Username is already in use.');
        }

        // find the registered user group
        $sql = <<<EOQ
SELECT
    G.group_id
FROM
    phpbb_groups AS G
WHERE
    group_name = 'REGISTERED'
EOQ;

        $data = $this->query($sql);
        if(!$data) {
            throw new Exception('Error finding Registered Group');
        }
        $groupId = $data[0]['G']['group_id'];

        // get default style for the site
        $sql = <<<EOQ
select
  config_value
from
  phpbb_config AS C
where
  config_name = 'default_style';
EOQ;
        $data = $this->query($sql);
        if(!$data) {
            throw new Exception('Error finding default style');
        }
        $styleId = $data[0]['C']['config_value'];

        $paramsToMigrate = [
            'user_type' => 0,
            'group_id' => $groupId,
            'user_permissions' => '',
            'user_sig' => '',
            'user_perm_from' => 0,
            'user_regdate' => $user['user_regdate'],
            'username' => $user['username'],
            'username_clean' => $user['username_clean'],
            'user_password' => $user['user_password'],
            'user_email' => $user['user_email'],
            'user_email_hash' => $user['user_email_hash'],
            'user_birthday' => $user['user_birthday'],
            'user_lang' => $user['user_lang'],
            'user_timezone' => $user['user_timezone'],
            'user_dst' => $user['user_dst'],
            'user_dateformat' => $user['user_dateformat'],
            'user_style' => $styleId,
            'user_topic_sortby_dir' => $user['user_topic_sortby_dir'],
            'user_post_show_days' => $user['user_post_show_days'],
            'user_post_sortby_type' => $user['user_post_sortby_type'],
            'user_post_sortby_dir' => $user['user_post_sortby_dir'],
            'user_notify' => $user['user_notify'],
            'user_notify_pm' => $user['user_notify_pm'],
            'user_notify_type' => $user['user_notify_type'],
            'user_allow_pm' => $user['user_allow_pm'],
            'user_allow_viewonline' => $user['user_allow_viewonline'],
            'user_allow_massemail' => $user['user_allow_massemail'],
            'user_options' => $user['user_options'],
            'user_from' => $user['user_from'],
            'user_icq' => $user['user_icq'],
            'user_aim' => $user['user_aim'],
            'user_yim' => $user['user_yim'],
            'user_msnm' => $user['user_msnm'],
            'user_jabber' => $user['user_jabber'],
            'user_website' => $user['user_website'],
            'user_occ' => $user['user_occ'],
            'user_form_salt' => $user['user_form_salt'],
            'user_interests' => '',
            'user_actkey' => '',
            'user_newpasswd' => '',
            'user_new' => 0,
            'user_reminded' => 0,
            'user_reminded_time' => 0,
        ];

        // find the what?
        // add user to site as a basic registered user
        $sql = 'INSERT INTO phpbb_users ('
            . implode(',', array_keys($paramsToMigrate))
            . ") VALUES ('"
            . implode("','", array_values($paramsToMigrate))
            . "')";

        $this->getDataSource()->begin();
        $result = $this->query($sql);

        if(!$result) {
            $this->getDataSource()->rollback();
            throw new Exception('Error adding to the site: ' . $sql);
        }

        $userId = $this->getDataSource()->lastInsertId();

        // add them to the registered user group
        $sql = "INSERT INTO phpbb_user_group (group_id, user_id, group_leader, user_pending) VALUES ($groupId, $userId, 0, 0,);";
        $result = $this->query($sql);

        if(!$result) {
            $this->getDataSource()->rollback();
            throw new Exception('Error adding to group: ' . $sql);
        }

        // return happy state
        $this->getDataSource()->commit();
        return true;
    }
}
