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
    public static function setFlashMessage($message, $section = 'flash'): void
    {
        if($section === '') {
            throw new \RuntimeException('No section specified for flash message');
        }

        $_SESSION['Flash'][$section][] = array(
            'message' => $message,
            'key' => 'flash',
            'element' => 'Flash/default',
            'params' => []
        );
    }

    public static function getFlashMessage($section = 'flash'): array
    {
        $messages = [];
        if (isset($_SESSION['Flash'][$section]) && count($_SESSION['Flash'][$section])) {
            foreach($_SESSION['Flash'][$section] as $data) {
                $messages[] = $data;
            }
            unset($_SESSION['Flash'][$section]);
        }
        return $messages;
    }

    public static function read($index, $default = null)
    {
        if(isset($_SESSION[$index])) {
            return $_SESSION[$index];
        }
        return $default;
    }

    public static function write($index, $value): void
    {
        $_SESSION[$index] = $value;
    }

    public static function has($index): bool
    {
        return isset($_SESSION[$index]);
    }
}
