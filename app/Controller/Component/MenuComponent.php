<?php
/**
 * Created by JetBrains PhpStorm.
 * User: JeffVandenberg
 * Date: 5/26/13
 * Time: 10:37 AM
 * To change this template use File | Settings | File Templates.
 */

App::uses('Component', 'Controller');

/**
 * @property AuthComponent Auth
 * @property SessionComponent Session
 * @property PermissionsComponent Permissions
 */
class MenuComponent extends Component {
    public $components = array(
        'Auth',
        'Permissions',
        'Session'
    );

    private $menu = array();

    public function InitializeMenu()
    {
        $menuComponents = include_once(ROOT . '/app/Lib/menu_components.php');
        $this->menu = $menuComponents['base'];
        if(!is_array($this->menu)) {
            return;
        }

        if($this->Auth->user()) {
            $this->menu = array_merge_recursive($this->menu, $menuComponents['player']);

            App::uses('AppModel', 'Model');
            App::uses('Character', 'Model');
            $characterRepo = new Character();
            $sanctionedCharacters = $characterRepo->ListSanctionedForUser($this->Auth->user('user_id'));
            foreach($sanctionedCharacters as $character) {
                $characterMenu = array(
                    'link' => '/character.php?action=interface&character_id='.$character['Character']['id'],
                    'submenu' => array(
                        'Login' => array(
                            'link' => '/chat/?character_id='.$character['Character']['id']
                        ),
                        'Requests' => array(
                            'link' => '/request.php?action=list&character_id=' . $character['Character']['id']
                        ),
                        'Bluebook' => array(
                            'link' => '/bluebook.php?action=list&character_id=' . $character['Character']['id']
                        ),
                        'Sheet' => array(
                            'link' => '/characters/viewOwn/' . $character['Character']['slug']
                        )
                    )
                );
                $this->menu['Site Tools']['submenu']['Characters']['submenu'][$character['Character']['character_name']] = $characterMenu;
            }
        }

        if($this->Permissions->IsST()) {
            $this->menu = array_merge_recursive($this->menu, $menuComponents['staff']);
        }
    }

    public function GetMenu()
    {
        return $this->menu;
    }

    public function createStorytellerMenu()
    {
        $menu = array(
            'Characters' => array(
                'link' => '#',
                'submenu' => array(
                    'Lookup' => array(
                        'link' => '/characters/stView'
                    ),
                    'Partial Name Search' => array(
                        'link' => '/st_tools.php?action=character_name_lookup'
                    ),
                    'Goals' => array(
                        'link' => array(
                            'admin' => true,
                            'controller' => 'characters',
                            'action' => 'goals'
                        )
                    )
                )
            ),
            'Requests' => array(
                'link' => '#',
                'submenu' => array(
                    'Dashboard' => array(
                        'link' => '/request.php?action=st_list'
                    )
                )
            ),
            'Chat' => array(
                'link' => '#',
                'submenu' => array(
                    'Login' => array(
                        'link' => '/chat/?st_login',
                        'target' => '_blank',
                    ),
                    'Clean Temp Rooms' => array(
                        'link' => '/chat/includes/clean_rooms.php',
                        'target' => '_blank'
                    )
                )
            ),
            'Tools' => array(
                'link' => '#',
                'submenu' => array(
                    'OOC Roller' => array(
                        'link' => '/dieroller.php?action=ooc'
                    ),
                    'Profile Lookup' => array(
                        'link' => '/storyteller_index.php?action=profile_lookup'
                    )
                )
            ),
            'Reports' => array(
                'link' => '#',
                'submenu' => array(
                    'Character Type' => array(
                        'link' => '/st_tools.php?action=character_search'
                    ),
                    'Character Population' => array(
                        'link' => '/st_tools.php?action=character_population_report'
                    ),
                    'Power Search' => array(
                        'link' => '/st_tools.php?action=power_search'
                    ),
                    'Player Preference Venue Report' => [
                        'controller' => 'player_preferences',
                        'action' => 'report_venue'
                    ],
                    'Player Preference Aggregate Report' => [
                        'controller' => 'player_preferences',
                        'action' => 'report_aggregate'
                    ]
                )
            )
        );

        if($this->Permissions->IsHead()) {
            $menu['Chat']['submenu']['Prochat Admin'] = array(
                'link' => '/chat/admin'
            );
            $menu['Tools']['submenu']['Permissions'] = array(
                'link' => '/storyteller_index.php?action=permissions'
            );
            $menu['Tools']['submenu']['Forum Assignments'] = array(
                'link' => array(
                    'controller' => 'users',
                    'action' => 'assignGroups'
                )
            );
            $menu['Tools']['submenu']['Icons'] = array(
                'link' => '/st_tools.php?action=icons_list'
            );
            $menu['Tools']['submenu']['Character Transfer'] = array(
                'link' => '/st_tools.php?action=profile_transfer'
            );
            $menu['Reports']['submenu']['Request Time Report'] = array(
                'link' => '/request.php?action=admin_time_report'
            );
            $menu['Reports']['submenu']['Request Status Report'] = array(
                'link' => '/request.php?action=admin_status_report'
            );
            $menu['Reports']['submenu']['ST Activity Report'] = array(
                'link' => '/st_tools.php?action=st_activity_report'
            );
        }

        if($this->Permissions->IsAdmin()) {
            $menu['Tools']['submenu']['Configuration'] = array(
                'link' => '/configuration'
            );
            $menu['Requests']['submenu']['Administration'] = array(
                'link' => '/admin/request'
            );
        }

        return $menu;
    }
}
