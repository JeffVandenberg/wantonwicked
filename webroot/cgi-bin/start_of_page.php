<?php
if(!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR);
}

// load composer
require_once ROOT_PATH . '../vendor/autoload.php';

if(!session_id()) {
    session_start();
}
