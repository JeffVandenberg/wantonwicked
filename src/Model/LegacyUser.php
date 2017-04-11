<?php
/**
 * Created by PhpStorm.
 * User: jvandenberg
 * Date: 12/27/13
 * Time: 4:00 PM
 */namespace app\Model;


use App\Model\AppModel;

class LegacyUser extends AppModel
{
    public $useTable = false;

    public function listAdmins()
    {
        return $this->listUsersWithPermission('1,2');
    }

    public function listSts()
    {
        return $this->listUsersWithPermission(3);
    }

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
        return $this->query($sql);
    }
    public function listUsersWithPermission($permissionId) {
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
    U.username
FROM
    phpbb_users AS U
    INNER JOIN permissions_users AS PU ON U.user_Id = PU.user_id
WHERE
    PU.permission_id IN ($permissionId)
GROUP BY
    U.user_id
ORDER BY
    U.username
EOQ;
        return $this->query($sql);
    }

    public function listAssts()
    {
        return $this->listUsersWithPermission(4);
    }

    public function listWikiManagers()
    {
        return $this->listUsersWithPermission(5);
    }
}
