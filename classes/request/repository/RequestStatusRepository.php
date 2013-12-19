<?php
/**
 * Created by JetBrains PhpStorm.
 * User: JeffVandenberg
 * Date: 8/11/13
 * Time: 5:57 PM
 * To change this template use File | Settings | File Templates.
 */

namespace classes\request\repository;


class RequestStatusRepository {
    public function ListAll()
    {
        $sql = <<<EOQ
SELECT
    *
FROM
    request_statuses
ORDER BY
    name
EOQ;

        return ExecuteQueryData($sql);
    }

    public function SimpleListAll()
    {
        $options = $this->ListAll();
        $list = array();
        foreach($options as $option)
        {
            $list[$option['id']] = $option['name'];
        }
        return $list;
    }

    public function FindById($id)
    {
        $id = (int) $id;
        $sql = <<<EOQ
SELECT
    R.*
FROM
    request_statuses AS R
WHERE
    id = $id;
EOQ;
        return ExecuteQueryItem($sql);
    }

}