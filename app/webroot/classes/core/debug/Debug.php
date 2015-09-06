<?php
/**
 * Created by PhpStorm.
 * User: jvandenberg
 * Date: 9/6/2015
 * Time: 2:09 PM
 */

namespace core\debug;

class Debug
{
    public static function printVariable($var)
    {
        echo "<pre>".print_r($var, true)."</pre>";
    }
}