<?php
/**
 * Created by PhpStorm.
 * User: JeffVandenberg
 * Date: 1/14/14
 * Time: 10:51 PM
 */
use classes\core\helpers\UserdataHelper;
/* @var array $userdata */

$storytellerMenu = array(
    'Characters' => array(
        'link' => '#',
        'submenu' => array(
            'Lookup' => array(
                'link' => 'view_sheet.php?action=st_view_xp'
            ),
            'Partial Name Search' => array(
                'link' => 'st_tools.php?action=character_name_lookup'
            )
        )
    ),
    'Requests' => array(
        'link' => '#',
        'submenu' => array(
            'Dashboard' => array(
                'link' => 'request.php?action=st_list'
            )
        )
    ),
    'Chat' => array(
        'link' => '#',
        'submenu' => array(
            'Login' => array(
                'link' => 'chat/?st_login',
                'target' => '_blank',
            ),
            'Login (Invisible)' => array(
                'link' => 'chat/?st_login&invisible',
                'target' => '_blank',
            ),
            'Clean Temp Rooms' => array(
                'link' => 'chat/includes/clean_rooms.php',
                'target' => '_blank'
            )
        )
    ),
    'Tools' => array(
        'link' => '#',
        'submenu' => array(
            'OOC Roller' => array(
                'link' => 'dieroller.php?action=ooc'
            ),
            'Profile Lookup' => array(
                'link' => 'storyteller_index.php?action=profile_lookup'
            ),
            'Territory Management' => array(
                'link' => 'territory.php?action=list'
            )
        )
    ),
    'Reports' => array(
        'link' => '#',
        'submenu' => array(
            'Character Type' => array(
                'link' => 'st_tools.php?action=character_search'
            ),
            'Power Search' => array(
                'link' => 'st_tools.php?action=power_search'
            ),
        )
    )
);

if(UserdataHelper::IsHead($userdata)) {
    $storytellerMenu['Chat']['submenu']['Prochat Admin'] = array(
        'link' => 'chat/admin'
    );
    $storytellerMenu['Tools']['submenu']['Permissions'] = array(
        'link' => 'storyteller_index.php?action=permissions'
    );
    $storytellerMenu['Tools']['submenu']['Icons'] = array(
        'link' => 'st_tools.php?action=icons_list'
    );
    $storytellerMenu['Tools']['submenu']['Character Transfer'] = array(
        'link' => 'st_tools.php?action=profile_transfer'
    );
    $storytellerMenu['Reports']['submenu']['Request Time Report'] = array(
        'link' => 'request.php?action=admin_time_report'
    );
}

if(UserdataHelper::IsAdmin($userdata)) {
    $storytellerMenu['Tools']['submenu']['Configuration'] = array(
        'link' => '/configuration'
    );
    $storytellerMenu['Requests']['submenu']['Administration'] = array(
        'link' => '/admin/request'
    );
}

return $storytellerMenu;