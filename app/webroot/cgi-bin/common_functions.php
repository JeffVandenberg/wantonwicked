<?php
//set_include_path('/mnt/Target01/331671/349277/www.wantonwicked.net/web/content/');

include_once ROOT_PATH . 'cgi-bin/buildInput.php';
include_once ROOT_PATH . 'cgi-bin/buildSelect.php';
include_once ROOT_PATH . 'cgi-bin/buildMultiSelect.php';
include_once ROOT_PATH . 'cgi-bin/verifyDate.php';
include_once ROOT_PATH . 'cgi-bin/buildDigiApplet.php';
include_once ROOT_PATH . 'cgi-bin/buildAddOnChatApplet.php';
include_once ROOT_PATH . 'cgi-bin/getNextID.php';
include_once ROOT_PATH . 'cgi-bin/viewSiteContent.php';
include_once ROOT_PATH . 'cgi-bin/makeDots.php';
include_once ROOT_PATH . 'cgi-bin/getMaxPowerPoints.php';
include_once ROOT_PATH . 'includes/database/mysql.php';
include_once ROOT_PATH . 'includes/helpers/security.php';

/**
 * @param $array
 * @return array
 */
function array_valuekeys($array)
{
    $list = array();
    foreach ($array as $key => $value) {
        if(is_array($value)) {
            $list[$key] = array_valuekeys($value);
        }
        else {
            $list[$value] = $value;
        }
    }
    return $list;
}

