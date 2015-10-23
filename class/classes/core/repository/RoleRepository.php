<?php
/**
 * Created by PhpStorm.
 * User: jvandenberg
 * Date: 10/22/15
 * Time: 5:27 PM
 */

namespace classes\core\repository;


class RoleRepository extends AbstractRepository
{
    /**
     * RoleRepository constructor.
     */
    public function __construct()
    {
        parent::__construct('classes\core\data\Role');
    }

    public function listRolesWithPermissions()
    {
        $sql = <<<EOQ
SELECT
    R.id,
    GROUP_CONCAT(PR.permission_id SEPARATOR ',') AS permissions
FROM
    roles AS R
    LEFT JOIN permissions_roles AS PR ON R.id = PR.role_id
GROUP BY
    R.id
EOQ;
        return $this->query($sql)->all();
    }
}