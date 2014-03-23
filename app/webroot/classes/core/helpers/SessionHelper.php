<?php
/**
 * Created by JetBrains PhpStorm.
 * User: JeffVandenberg
 * Date: 8/18/13
 * Time: 11:07 PM
 * To change this template use File | Settings | File Templates.
 */

namespace classes\core\helpers;


class SessionHelper
{
    public static function SetFlashMessage($message, $section = 'global')
    {
        $_SESSION['flash'][$section] = $message;
    }

    public static function GetFlashMessage($section = 'global')
    {
        $message = "";
        if (isset($_SESSION['flash'][$section])) {
            $message = $_SESSION['flash'][$section];
            unset($_SESSION['flash'][$section]);
            if (count($_SESSION['flash']) == 0) {
                unset($_SESSION['flash']);
            }
        }
        return $message;
    }

    public static function Read($index, $default = null)
    {
        if(isset($_SESSION[$index])) {
            return $_SESSION[$index];
        }
        return $default;
    }

    public static function Write($index, $value)
    {
        $_SESSION[$index] = $value;
    }

    public static function Has($index) {
        return isset($_SESSION[$index]);
    }
}