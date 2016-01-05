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

    public function listAll()
    {
        $blueBook = RequestType::BlueBook;
        $sql = <<<EOQ
SELECT
    *
FROM
    request_types
WHERE
    id != ?
ORDER BY
    name
EOQ;
        $params = array(
            $blueBook
        );
        return $this->query($sql)->all($params);
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
    request_types AS R
WHERE
    id = ?;
EOQ;
        $params = array(
            $id
        );
        return $this->query($sql)->single($params);
    }

    public function ListForGroupId($groupId)
    {
        $sql = <<<EOQ
SELECT
    RT.*
FROM
    request_types as RT
    INNER JOIN groups_request_types as GRT ON RT.id = GRT.request_type_id
WHERE
    GRT.group_id = ?
ORDER BY
    RT.name
EOQ;

        $params = array($groupId);

        $list = array();
        foreach($this->query($sql)->all($params) as $item)
        {
            $list[] = $this->populateObject($item);
        }

        return $list;
    }
}