<?php

namespace App\Model\Table;

use App\Model\Entity\User;
use Cake\Database\Connection;
use Cake\Datasource\ConnectionManager;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * PhpbbUsers Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Users
 * @property \Cake\ORM\Association\BelongsTo $Groups
 * @property \Cake\ORM\Association\BelongsTo $Roles
 *
 * @method \App\Model\Entity\User get($primaryKey, $options = [])
 * @method \App\Model\Entity\User newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\User[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\User|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\User[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\User findOrCreate($search, callable $callback = null, $options = [])
 */
class UsersTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('phpbb_users');
        $this->setDisplayField('username');
        $this->setPrimaryKey('user_id');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Groups', [
            'foreignKey' => 'group_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Roles', [
            'foreignKey' => 'role_id'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->isUnique(['username']));
        $rules->add($rules->isUnique(['username_clean']));

        return $rules;
    }

    /**
     * @param $userId
     * @param $permissionIds
     * @return bool
     */
    public function checkUserPermission($userId, $permissionIds)
    {
        if (!$userId || !$permissionIds) {
            return false;
        }
        if (!is_array($permissionIds)) {
            $permissionIds = array($permissionIds);
        }
        $permissionsPlaceholder = implode(',', array_fill(0, count($permissionIds), '?'));

        $sql = <<<SQL
SELECT 
  count(*) AS Count 
FROM 
  permissions_users 
WHERE
  user_id = ?
  AND permission_id IN ($permissionsPlaceholder);
SQL;
        $params = array_merge([$userId], $permissionIds);

        $connection = ConnectionManager::get('default');
        /* @var Connection $connection */

        $count = $connection->execute($sql, $params)->fetchAll('assoc');
        return $count[0]['Count'] > 0;
    }

    /**
     * @param $userId
     * @return array
     */
    public function listUserGroups($userId)
    {
        $userId = (int)$userId;

        $sql = <<<EOQ
SELECT
    User.user_id,
    UserGroup.group_id,
    1 AS is_member,
    UserGroup.group_leader
FROM
    phpbb_users AS User
    LEFT JOIN phpbb_user_group AS UserGroup ON User.user_id = UserGroup.user_id
WHERE
    User.user_id = ?
EOQ;

        $params = [
            $userId
        ];
        $conn = $this->getConnection();
        /* @var Connection $conn */
        return $conn->execute($sql, $params)->fetchAll('assoc');

    }

    /**
     * @param $data
     * @return bool
     */
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

    /**
     * @param $userId
     * @param $groupId
     * @param $isGroupLeader
     * @return bool
     */
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
    user_id = ?
    AND group_id = ?;
EOQ;
        $params = [
            $userId,
            $groupId
        ];

        $result = $this->getConnection()->execute($sql, $params)->fetch('assoc');

        if ($result['access_rows'] > 0) {
            $sql = <<<EOQ
UPDATE
    phpbb_user_group
SET
    group_leader = ?
WHERE
    group_id = ?
    AND user_id = ?;
EOQ;
            $params = [
                $isGroupLeader,
                $groupId,
                $userId
            ];
        } else {
            $sql = <<<EOQ
INSERT INTO
    phpbb_user_group
    (group_id, user_id, group_leader, user_pending)
VALUES
  (?, ?, ?, 0);
EOQ;
            $params = [
                $groupId,
                $userId,
                $isGroupLeader
            ];
        }

        $this->getConnection()->execute($sql, $params);
        return true;
    }

    /**
     * @param $userId
     * @param $groupId
     * @return bool
     */
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

        $params = [
            $userId,
            $groupId
        ];

        $this->getConnection()->execute($sql, $params);
        return true;
    }

    /**
     * @param $userId
     */
    private function updateUserAclPermissions($userId)
    {
        $sql = <<<EOQ
UPDATE
  phpbb_users
SET
  user_permissions = '',
  user_perm_from = 0
WHERE
  user_id = ?
EOQ;
        $params = [
            $userId
        ];
        $this->getConnection()->execute($sql, $params);
    }

    /**
     * @return mixed
     */
    public function listUsersWithGroups()
    {
        $sql = <<<EOQ
SELECT
    (
      SELECT group_concat(
        G.name SEPARATOR ', '
      )
      FROM
        st_groups AS SG
        LEFT JOIN groups AS G ON SG.group_id = G.id
      WHERE
        SG.user_id = U.user_id
      ORDER BY
        G.name
    ) AS groups,
    U.user_id,
    U.username,
    U.role_id,
    R.name as role_name
FROM
    phpbb_users AS U
    INNER JOIN roles AS R ON U.role_id = R.id
    LEFT JOIN permissions_users AS PU ON U.user_Id = PU.user_id
WHERE
    U.role_Id > 0
GROUP BY
    U.user_id
ORDER BY
    U.username
EOQ;
        return $this->getConnection()->execute($sql);

    }

    /**
     * @param $user
     * @return bool
     * @throws \Exception
     */
    public function addUserToSite($user)
    {
        // check if the username is already in use on the site
        $userNameClean = trim(strtolower($user['username']));
        $connection = $this->getConnection();
        /* @var Connection $connection */

        $sql = <<<EOQ
SELECT
    count(*) AS count
FROM
    phpbb_users
WHERE
    username_clean = ?
EOQ;
        $params = [$userNameClean];

        $data = $connection->execute($sql, $params);
        if($data[0][0]['count'] > 0) {
            throw new \Exception('Username is already in use.');
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

        $data = $connection->execute($sql);
        if(!$data) {
            throw new \Exception('Error finding Registered Group');
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
        $data = $connection->execute($sql);
        if(!$data) {
            throw new \Exception('Error finding default style');
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
            . implode(',', array_fill(0, count($paramsToMigrate), '?'))
            . "')";

        $connection->begin();
        $statement = $connection->insert($sql, array_values($paramsToMigrate));

        if(!$statement->rowCount()) {
            $connection->rollback();
            throw new \Exception('Error adding to the site: ' . $sql);
        }

        $userId = $statement->lastInsertId();

        // add them to the registered user group
        $sql = "INSERT INTO phpbb_user_group (group_id, user_id, group_leader, user_pending) VALUES ($groupId, $userId, 0, 0,);";
        $result = $connection->execute($sql);

        if(!$result) {
            $connection->rollback();
            throw new \Exception('Error adding to group: ' . $sql);
        }

        // return happy state
        $connection->commit();
        return true;
    }

    /**
     * @param $groupId
     * @return User[]
     */
    public function listUsersInGroup($groupId)
    {
        return $this->find()
            ->contain([
                'Groups'
            ])
            ->where([
                'Groups.id' => $groupId
            ]);
    }
}
