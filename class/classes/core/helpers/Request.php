<?php
/**
 * Created by JetBrains PhpStorm.
 * User: JeffVandenberg
 * Date: 8/11/13
 * Time: 10:12 AM
 * To change this template use File | Settings | File Templates.
 */

namespace classes\core\helpers;


class Request {
    public static function getValue($name, $defaultValue = null)
    {
        $value = (isset($_GET[$name])) ? $_GET[$name] : $defaultValue;
        $value = (isset($_POST[$name])) ? $_POST[$name] : $value;

        if(is_int($defaultValue))
        {
            $value = (int) trim($value, " \t\r\n\x0B\xA0\x00");
        }
        if(is_bool($defaultValue))
        {
            $value = (bool) trim($value);
        }

        return $value;
    }

    public static function isAjax()
    {
        if($_SERVER["HTTP_X_REQUESTED_WITH"] == "XMLHttpRequest")
        {
            return true;
        }
        return false;
    }

    public static function isGet()
    {
        return strtolower($_SERVER['REQUEST_METHOD']) == 'get';
    }

    public static function isPost()
    {
        return strtolower($_SERVER['REQUEST_METHOD']) == 'post';
    }

    public static function preventCache()
    {
        header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
        header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
    }
}
