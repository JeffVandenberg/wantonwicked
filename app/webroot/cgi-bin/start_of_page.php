<?php
define('ROOT_PATH', dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR);

include ROOT_PATH . "cgi-bin/dbconnect.php";

// load composer
require_once ROOT_PATH . '../../vendor/autoload.php';

session_start();