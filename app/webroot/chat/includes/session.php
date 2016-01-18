<?php

$method = 'ini_set';
$key = 'session.name';
$value = 'CAKEPHP';
$method($key, $value);
session_start();

?>