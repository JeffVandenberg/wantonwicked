<?php
/**
 * Created by JetBrains PhpStorm.
 * User: JeffVandenberg
 * Date: 5/26/13
 * Time: 10:37 AM
 * To change this template use File | Settings | File Templates.
 */namespace app\Controller\Component;



use Cake\Controller\Component;

/**
 * @property AuthComponent Auth
 * @property SessionComponent Session
 * @property PermissionsComponent Permissions
 */
class MenuComponent extends Component
{
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
        if (!is_array($this->menu)) {
            return;
        }

        if ($this->Auth->user()) {
            $this->menu = array_merge_recursive($this->menu, $menuComponents['player']);

            use App\Model\AppModel;
            use App\Model\Character;
            $characterRepo = new Character();
            $sanctionedCharacters = $characterRepo->ListSanctionedForUser($this->Auth->user('user_id'));
            foreach ($sanctionedCharacters as $character) {
                $characterMenu = array(
                    'link' => '/character.php?action=interface&character_id=' . $character['Character']['id'],
                    'submenu' => array(
                        'Login' => array(
                            'link' => '/chat/?character_id=' . $character['Character']['id']
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
                $this->menu['Utilities']['submenu']['Characters']['submenu'][$character['Character']['character_name']] = $characterMenu;
            }
        }

        if ($this->Permissions->IsST()) {
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
                    ),
                    'Beat Awards' => [
                        'link' => [
                            'admin' => false,
                            'controller' => 'characters',
                            'action' => 'stBeats'
                        ]
                    ]
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

        if ($this->Permissions->IsHead()) {
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
            $menu['Tools']['submenu']['Beat Types'] = [
                'link' => '/beatTypes'
            ];
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

        if ($this->Permissions->IsAdmin()) {
            $menu['Tools']['submenu']['Configuration'] = array(
                'link' => '/configuration'
            );
            $menu['Requests']['submenu']['Administration'] = array(
                'link' => '/admin/request'
            );
        }

        return $menu;
    }

    public function createCharacterMenu($characterId, $characterName)
    {
        $characterMenu = [
            'Chat' => [
                'link' => "/chat/?character_id=$characterId",
                'target' => '_blank',
                'submenu' => [
                    'Login' => [
                        'link' => "/chat/?character_id=$characterId",
                        'target' => '_blank'
                    ],
                    'Interface' => [
                        'link' => "/character.php?action=interface&character_id=$characterId"
                    ]
                ]
            ],
            'Character' => [
                'link' => '#',
                'submenu' => [
                    'Sheet' => [
                        'link' => "/characters/viewOwn/" . $characterId
                    ],
                    'Beats' => [
                        'link' => '/characters/beats/' . $characterId
                    ],
                    'Wiki Page' => [
                        'link' => '/wiki/?n=Players.' . preg_replace("/[^A-Za-z0-9]/", '', $characterName),
                        'target' => '_blank'
                    ],
                    'Character Log' => [
                        'link' => "/character.php?action=log&character_id=$characterId"
                    ],
                    'Delete' => [
                        'link' => '/chat.php?action=delete&character_id=' . $characterId
                    ]
                ]
            ],
            'Tools' => [
                'link' => '#',
                'submenu' => [
                    'Dice Roller' => [
                        'link' => "/dieroller.php?action=character&character_id=$characterId"
                    ],
                    'Requests' => [
                        'link' => "/request.php?action=list&character_id=$characterId",
                        'submenu' => [
                            'New' => [
                                'link' => "/request.php?action=create&character_id=$characterId"
                            ]
                        ]
                    ],
                    'Bluebook' => [
                        'link' => "/bluebook.php?action=list&character_id=$characterId",
                        'submenu' => [
                            'New' => [
                                'link' => "/bluebook.php?action=create&character_id=$characterId"
                            ]
                        ]
                    ],
                    'Favors' => [
                        'link' => "/favors.php?action=list&character_id=$characterId"
                    ],
                    'Notes' => [
                        'link' => "/notes.php?action=character&character_id=$characterId"
                    ]
                ]
            ],
        ];

        return $characterMenu;
    }
}
