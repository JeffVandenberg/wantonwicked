<?php
/**
 * Created by PhpStorm.
 * User: JeffVandenberg
 * Date: 1/14/14
 * Time: 10:51 PM
 */
use classes\core\helpers\UserdataHelper;
/* @var array $userdata */

$storytellerMenu = [
    'Characters' => [
        'link' => '#',
        'submenu' => [
            'Lookup' => [
                'link' => '/characters/stView'
            ],
            'Partial Name Search' => [
                'link' => 'st_tools.php?action=character_name_lookup'
            ],
            'Goals' => [
                'link' => '/characters/stGoals'
            ],
            'Beat Awards' => [
                'link' => '/characters/stBeats'
            ]
        ]
    ],
    'Requests' => [
        'link' => '#',
        'submenu' => [
            'Dashboard' => [
                'link' => '/requests/st-dashboard'
            ]
        ]
    ],
    'Chat' => [
        'link' => '#',
        'submenu' => [
            'Login' => [
                'link' => 'chat/?st_login',
                'target' => '_blank',
            ],
            'Clean Temp Rooms' => [
                'link' => 'chat/includes/clean_rooms.php',
                'target' => '_blank'
            ]
        ]
    ],
    'Tools' => [
        'link' => '#',
        'submenu' => [
            'OOC Roller' => [
                'link' => 'dieroller.php?action=ooc'
            ],
            'Profile Lookup' => [
                'link' => 'storyteller_index.php?action=profile_lookup'
            ],
            'Territory Management' => [
                'link' => 'territory.php?action=list'
            ]
        ]
    ],
    'Reports' => [
        'link' => '#',
        'submenu' => [
            'Character Type' => [
                'link' => 'st_tools.php?action=character_search'
            ],
            'Character Population' => [
                'link' => 'st_tools.php?action=character_population_report'
            ],
            'Power Search' => [
                'link' => 'st_tools.php?action=power_search'
            ],
        ]
    ]
];

if(UserdataHelper::IsHead($userdata)) {
    $storytellerMenu['Chat']['submenu']['Prochat Admin'] = array(
        'link' => 'chat/admin'
    );
    $storytellerMenu['Tools']['submenu']['Permissions'] = array(
        'link' => 'storyteller_index.php?action=permissions'
    );
    $storytellerMenu['Tools']['submenu']['Assign Forums'] = array(
        'link' => '/users/assignGroups'
    );
    $storytellerMenu['Tools']['submenu']['Icons'] = array(
        'link' => 'st_tools.php?action=icons_list'
    );
    $storytellerMenu['Tools']['submenu']['Character Transfer'] = array(
        'link' => 'st_tools.php?action=profile_transfer'
    );
    $storytellerMenu['Tools']['submenu']['Beat Types'] = [
        'link' => '/beatTypes'
    ];
    $storytellerMenu['Reports']['submenu']['Request ST Activity Report'] = array(
        'link' => '/requests/activity-report'
    );
    $storytellerMenu['Reports']['submenu']['Request Time Report'] = array(
        'link' => '/requests/time-report'
    );
    $storytellerMenu['Reports']['submenu']['Request Status Report'] = array(
        'link' => '/requests/status-report'
    );
    $storytellerMenu['Reports']['submenu']['ST Activity Report'] = array(
        'link' => '/st_tools.php?action=st_activity_report'
    );
}

if(UserdataHelper::IsAdmin($userdata)) {
    $storytellerMenu['Tools']['submenu']['Configuration'] = array(
        'link' => '/configurations'
    );
    $storytellerMenu['Requests']['submenu']['Administration'] = array(
        'link' => '/requests/admin'
    );
}

return $storytellerMenu;
