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
    public function RemovePermissions($userId)
    {
        $sql = <<<EOQ
DELETE FROM
    gm_permissions
WHERE
    id = ?
EOQ;

        $this->Query($sql)->Execute(array($userId));

        $sql = <<<EOQ
DELETE FROM
    st_groups
WHERE
    user_id = ?
EOQ;

        return $this->Query($sql)->Execute(array($userId));
    }
}