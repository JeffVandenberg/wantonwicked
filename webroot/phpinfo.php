<?php
/**
 * Created by PhpStorm.
 * User: JeffVandenberg
 * Date: 2/4/2017
 * Time: 9:03 PM
 */
phpinfo();
die();
$level_names = array('E_ERROR', 'E_WARNING', 'E_PARSE', 'E_NOTICE',
    'E_CORE_ERROR', 'E_CORE_WARNING', 'E_COMPILE_ERROR', 'E_COMPILE_WARNING',
    'E_USER_ERROR', 'E_USER_WARNING', 'E_USER_NOTICE', 'E_ALL');
foreach ($level_names as $level) {
    if (error_reporting() & constant($level)) {
        echo $level . '<br />';
    }
}
