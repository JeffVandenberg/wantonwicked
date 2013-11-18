<?php
/**
 * Created by JetBrains PhpStorm.
 * User: JeffVandenberg
 * Date: 8/11/13
 * Time: 11:43 AM
 * To change this template use File | Settings | File Templates.
 */

namespace classes\request\repository;


use classes\core\repository\AbstractRepository;
use classes\request\data\RequestType;

class RequestTypeRepository extends AbstractRepository
{
    function __construct() {
        parent::__construct('classes\request\data\RequestType');
    }

    public function ListAll()
    {
        $blueBook = RequestType::BlueBook;
        $sql = <<<EOQ
SELECT
    *
FROM
    request_types
WHERE
    id != $blueBook
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
    request_types AS R
WHERE
    id = $id;
EOQ;
        return ExecuteQueryItem($sql);
    }
}