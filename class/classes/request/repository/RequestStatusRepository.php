<?php
/**
 * Created by JetBrains PhpStorm.
 * User: JeffVandenberg
 * Date: 8/11/13
 * Time: 5:57 PM
 * To change this template use File | Settings | File Templates.
 */

namespace classes\request\repository;


use classes\core\repository\AbstractRepository;

class RequestStatusRepository extends AbstractRepository
{
    function __construct() {
        parent::__construct('classes\request\data\RequestStatus');
    }

    public function listAll()
    {
        $sql = <<<EOQ
SELECT
    *
FROM
    request_statuses
ORDER BY
    name
EOQ;

        return $this->query($sql)->all();
    }

    public function simpleListAll()
    {
        $options = $this->listAll();
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
    id = ?;
EOQ;
        $params = array($id);
        return $this->query($sql)->single($params);
    }

}