<?php
/**
 * Created by JetBrains PhpStorm.
 * User: JeffVandenberg
 * Date: 8/18/13
 * Time: 6:12 PM
 * To change this template use File | Settings | File Templates.
 */

namespace classes\core\repository;


class StorytellerRepository
{
    public function AddStoryteller()
    {

    }

    public function UpdateStoryteller()
    {

    }

    public function ListStorytellers()
    {

    }

    public function DeleteStoryteller($id)
    {

    }

    public function ListGroupsForStoryteller($userId)
    {
        $userId = (int)$userId;
        $sql = <<<EOQ
SELECT
    G.id as `group_id`,
    G.name as `name`
FROM
    st_groups AS SG
    INNER JOIN groups AS G ON SG.group_id = G.id
WHERE
    user_id = $userId
EOQ;

        return ExecuteQueryData($sql);
    }
}