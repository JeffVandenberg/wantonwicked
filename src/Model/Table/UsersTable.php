<?php

namespace App\Model\Table;

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
//        $rules->add($rules->existsIn(['user_id'], 'Users'));
//        $rules->add($rules->existsIn(['group_id'], 'Groups'));
//        $rules->add($rules->existsIn(['role_id'], 'Roles'));

        return $rules;
    }

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
}
