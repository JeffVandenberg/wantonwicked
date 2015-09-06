<?php
/**
 * Created by JetBrains PhpStorm.
 * User: JeffVandenberg
 * Date: 8/11/13
 * Time: 12:39 PM
 * To change this template use File | Settings | File Templates.
 */

namespace classes\core\helpers;


class Response {

    public static function Redirect($url, $message = null)
    {
        if($message) {
            SessionHelper::SetFlashMessage($message);
        }
        header('location:http://' . $_SERVER['SERVER_NAME'] . '/' . $url);
        exit();
    }

    public static function SendJson($value)
    {
        header('content-type: application/json');
        echo json_encode($value);
        exit();
    }

    public static function PreventCache()
    {
        header("Cache-Control: no-store, no-cache, must-revalidate");
    }

    public static function EndRequest($message = null)
    {
        if($message) {
            echo $message;
        }
        exit;
    }
}