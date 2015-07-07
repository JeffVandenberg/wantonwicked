<?php
define('ROOT_PATH', dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR);

include ROOT_PATH . "cgi-bin/dbconnect.php";
include ROOT_PATH . "cgi-bin/common_functions.php";
include ROOT_PATH . 'cgi-bin/timezoneAdjustment.php';

// load composer
require_once ROOT_PATH . '../../vendor/autoload.php';

session_start();
function debug($var)
{
    echo "<pre>".print_r($var, true)."</pre>";
}

ini_set('memory_limit', "64M");
spl_autoload_extensions('.php');
spl_autoload_register(
    function ($className) {
        $path = ROOT_PATH . str_replace("\\", DIRECTORY_SEPARATOR, $className) . '.php';
        if (file_exists($path)) {
            /** @noinspection PhpIncludeInspection */
            require_once($path);
        }
    }
);
date_default_timezone_set('America/Chicago');

