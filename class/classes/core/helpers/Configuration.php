<?php
/**
 * Created by PhpStorm.
 * User: JeffVandenberg
 * Date: 7/22/14
 * Time: 9:09 PM
 */

namespace classes\core\helpers;


use classes\core\repository\Database;

class Configuration
{
    public static function read($key)
    {
        $db = new Database();
        $sql = <<<EOQ
SELECT
    `value`
FROM
    configurations
WHERE
    `key` = ?
EOQ;
        $params = array($key);

        return $db->query($sql)->value($params);
    }
}
