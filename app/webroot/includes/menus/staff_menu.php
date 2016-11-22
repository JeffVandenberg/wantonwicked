<?php
/**
 * Created by PhpStorm.
 * User: JeffVandenberg
 * Date: 1/14/14
 * Time: 10:51 PM
 */
use classes\core\helpers\UserdataHelper;
/* @var array $userdata */

$staffMenu = array(
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
			'Scenes' => array(
                'link' => '/scenes'
            ),
			'Character Sheet Lookup' => array(
                'link' => 'view_sheet.php?action=st_view_xp'
            ),
			'Character Name Search' => array(
                'link' => 'staff_tools.php?action=character_name_lookup'
            ),
            'Profile Lookup' => array(
                'link' => 'storyteller_index.php?action=profile_lookup'
            ),
			'Conditions' => array(
                'link' => '/conditions/add'
            )
            'Territory Management' => array(
                'link' => 'territory.php?action=list'
            )
        )
    ),
    'Reports' => array(
        'link' => '#',
        'submenu' => array(
            'Character Goals' => array(
                'link' => '/admin/characters/goals'
            ),
			'Character Type' => array(
                'link' => 'staff_tools.php?action=character_search'
            ),
			'Character Population' => array(
                'link' => 'staff_tools.php?action=character_population_report'
            ),
            'Power Search' => array(
                'link' => 'staff_tools.php?action=power_search'
            )
        )
    )
);



//*if(UserdataHelper::IsHead($userdata)) {  IsHead will no longer be a thing with the new rolls

}  *//

if(UserdataHelper::IsAdmin($userdata)) {
	$staffMenu['Requests']['submenu']['Administration'] = array(
        'link' => '/admin/request'
    );
	$staffMenu['Chat']['submenu']['Prochat Admin'] = array(
        'link' => 'chat/admin'
    );
	$staffMenu['Reports']['submenu']['Request Time Report'] = array(
        'link' => 'request.php?action=admin_time_report'
    );
    $staffMenu['Reports']['submenu']['Request Status Report'] = array(
        'link' => 'request.php?action=admin_status_report'
    );
	$staffMenu['Reports']['submenu']['Staff Activity Report'] = array(
    'link' => 'request.php?action=admin_activity_report'
	);
    $staffMenu['Reports']['submenu']['Narrator Activity Report'] = array(
        'link' => '/staff_tools.php?action=st_activity_report'
    );
	$staffMenu['Admin Only'] = array(  //*JEFF HOW DO WE ADD AN ADMIN ONLY MENU? *//
        'link' => '/configuration'
    );	
    $staffMenu['Admin Only']['submenu']['Site Permissions'] = array(
        'link' => 'storyteller_index.php?action=permissions'
    );
    $staffMenu['Admin Only']['submenu']['Forum Permissions'] = array(
        'link' => '/users/assignGroups'
    );
    $staffMenu['Admin Only']['submenu']['Icon Administration'] = array(
        'link' => 'staff_tools.php?action=icons_list'
    );
    $staffMenu['Admin Only']['submenu']['Character Transfer'] = array(
        'link' => 'staff_tools.php?action=profile_transfer'
    );
    $staffMenu['Admin Only']['submenu']['Game Configuration'] = array(
        'link' => '/configuration'
    );
}

return $staffMenu;