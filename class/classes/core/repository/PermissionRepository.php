<?php
/**
 * Created by JetBrains PhpStorm.
 * User: JeffVandenberg
 * Date: 11/10/13
 * Time: 6:27 PM
 * To change this template use File | Settings | File Templates.
 */

namespace classes\core\repository;


class PermissionRepository extends AbstractRepository
{

    /**
     * PermissionRepository constructor.
     */
    public function __construct()
    {
        parent::__construct('classes\core\data\Permission');
    }

    public function RemovePermissions($userId)
    {
        $sql = <<<EOQ
DELETE FROM
    permissions_users
WHERE
    user_id = ?
EOQ;

        $this->query($sql)->execute(array($userId));

        $sql = <<<EOQ
DELETE FROM
    st_groups
WHERE
    user_id = ?
EOQ;

        return $this->query($sql)->execute(array($userId));
    }

    public function ListPermissionsForUser($userId)
    {
        $sql = <<<EOQ
SELECT
    permission_id
FROM
    permissions_users
WHERE
    user_id = ?
EOQ;
        $params = array($userId);

        $list = array();
        foreach($this->query($sql)->all($params) as $item) {
            $list[] = $item['permission_id'];
        }
        return $list;
    }

    public function SavePermissionsForUser($userId, $permissions)
    {
        $sql = <<<EOQ
DELETE FROM permissions_users WHERE user_id = ?
EOQ;
        $params = array($userId);
        $this->query($sql)->execute($params);


        foreach ($permissions as $permission) {
            $query = <<<EOQ
INSERT INTO
    permissions_users
    (
        permission_id,
        user_id
    )
VALUES
    ( ?, ? )
EOQ;
            $params = array(
                $permission,
                $userId
            );

            Database::getInstance()->query($query)->execute($params);
        }

    }
}