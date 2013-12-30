<?php
/**
 * Created by PhpStorm.
 * User: jvandenberg
 * Date: 12/27/13
 * Time: 4:00 PM
 */
App::uses('AppModel', 'Model');

class LegacyUser extends AppModel
{
    public $useTable = false;

    public function listAdmins()
    {
        $sql = <<<EOQ
select
    GmPermissions.*,
    User.username as Name
from
    gm_permissions AS GmPermissions
    inner join phpbb_users as User
        on GmPermissions.id = User.user_id
where
    GmPermissions.position != 'Hidden'
    and GmPermissions.is_head='Y'
order by
    User.username;
EOQ;
        return $this->query($sql);
    }

    public function listSts()
    {
        $sql = <<<EOQ
SELECT
    group_concat(G.name separator ', ') as groups,
    GP.*,
    U.username as Name
FROM
    gm_permissions AS GP
    inner join phpbb_users AS U ON GP.id = U.user_id
    LEFT JOIN st_groups AS SG ON GP.ID = SG.user_id
    LEFT JOIN groups AS G ON SG.group_id = G.id
WHERE
    GP.position != 'Hidden'
    and GP.is_head='N'
    and GP.is_gm = 'Y'
    AND GP.side_game = 'N'
    AND GP.position != 'Venerable Ancestor'
GROUP BY
    U.user_id
ORDER BY
    U.username;
EOQ;
        return $this->query($sql);
    }

    public function listAssts()
    {
        $sql = <<<EOQ
SELECT
    group_concat(G.name separator ', ') as groups,
    GP.*,
    U.username AS Name
FROM
    gm_permissions AS GP
    inner join phpbb_users AS U ON GP.id = U.user_id
    LEFT JOIN st_groups AS SG ON GP.ID = SG.user_id
    LEFT JOIN groups AS G ON SG.group_id = G.id
WHERE
    GP.position != 'Hidden'
    and GP.is_head='N'
    and GP.is_gm = 'N'
    AND GP.side_game = 'N'
    AND GP.is_asst = 'Y'
    AND GP.position != 'Venerable Ancestor'
GROUP BY
    U.user_id
ORDER BY
    U.username;
EOQ;
        return $this->query($sql);
    }

    public function listWikiManagers()
    {
        $sql = <<<EOQ
SELECT
    gm_permissions.*,
    U.username as Name
FROM
    gm_permissions
    INNER JOIN phpbb_users AS U
        ON gm_permissions.id = U.user_id
WHERE
    gm_permissions.position != 'Hidden'
    AND gm_permissions.wiki_manager = 'Y'
ORDER BY
    U.username;
EOQ;
        return $this->query($sql);
    }
}