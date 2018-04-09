<?php
/**
 * Created by JetBrains PhpStorm.
 * User: JeffVandenberg
 * Date: 5/26/13
 * Time: 10:37 AM
 * To change this template use File | Settings | File Templates.
 */
namespace App\Controller\Component;


use App\Model\Entity\Character;
use Cake\Controller\Component;
use Cake\ORM\TableRegistry;
use classes\character\data\CharacterStatus;


/**
 * @property Component\AuthComponent Auth
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
        $menuComponents = include_once(ROOT . '/lib/menu_components.php');
        $this->menu = $menuComponents['base'];
        if (!is_array($this->menu)) {
            return;
        }

        if ($this->Auth->user('user_id') != 1) {
            $this->menu = array_merge_recursive($this->menu, $menuComponents['player']);

            $characterTable = TableRegistry::get('Characters');
            $query = $characterTable
                ->find()
                ->select([
                    'Characters.character_name',
                    'Characters.id',
                    'Characters.slug'
                ])
                ->where([
                    'Characters.character_status_id IN' => CharacterStatus::Sanctioned,
                    'Characters.user_id' => $this->Auth->user('user_id'),
                ])
                ->order([
                    'Characters.character_name'
                ]);
            $sanctionedCharacters = $query->toArray();
            /* @var Character[] $sanctionedCharacters */
            
            foreach ($sanctionedCharacters as $character) {
                $characterMenu = [
                    'link' => '/character.php?action=interface&character_id=' . $character->id,
                    'submenu' => [
                        'Login' => [
                            'link' => '/chat/?character_id=' . $character->id
                        ],
                        'Requests' => [
                            'link' => [
                                'controller' => 'requests',
                                'action' => 'character',
                                $character->id
                            ]
                        ],
                        'Bluebook' => [
                            'link' => [
                                'controller' => 'bluebooks',
                                'action' => 'character',
                                $character->id
                            ]
                        ],
                        'Sheet' => [
                            'link' => '/characters/viewOwn/' . $character->slug
                        ]
                    ]
                ];
                $this->menu['Characters']['submenu'][$character->character_name] = $characterMenu;
            }
        }

        if ($this->Permissions->IsST()) {
            $this->menu = array_merge_recursive($this->menu, $menuComponents['staff']);
        }

        // prune the menu
        foreach($this->menu as $header => $menuOptions) {
            if(!isset($menuOptions['link']) && (empty($menuOptions) || empty($menuOptions['submenu']))) {
                unset($this->menu[$header]);
            }
        }
    }

    public function GetMenu()
    {
        return $this->menu;
    }

    public function createStorytellerMenu()
    {
        $menu = [
            'Characters' => [
                'link' => '#',
                'submenu' => [
                    'Lookup' => [
                        'link' => '/characters/stView'
                    ],
                    'Partial Name Search' => [
                        'link' => '/st_tools.php?action=character_name_lookup'
                    ],
                    'Goals' => [
                        'link' => [
                            'admin' => true,
                            'controller' => 'characters',
                            'action' => 'goals'
                        ]
                    ],
                    'Beat Awards' => [
                        'link' => [
                            'admin' => false,
                            'controller' => 'characters',
                            'action' => 'stBeats'
                        ]
                    ]
                ]
            ],
            'Requests' => [
                'link' => '#',
                'submenu' => [
                    'Dashboard' => [
                        'link' => '/requests/st-dashboard/'
                    ]
                ]
            ],
            'Chat' => [
                'link' => '#',
                'submenu' => [
                    'Login' => [
                        'link' => '/chat/?st_login',
                        'target' => '_blank',
                    ],
                    'Clean Temp Rooms' => [
                        'link' => '/chat/includes/clean_rooms.php',
                        'target' => '_blank'
                    ]
                ]
            ],
            'Tools' => [
                'link' => '#',
                'submenu' => [
                    'OOC Roller' => [
                        'link' => '/dieroller.php?action=ooc'
                    ],
                    'Profile Lookup' => [
                        'link' => '/storyteller_index.php?action=profile_lookup'
                    ]
                ]
            ],
            'Reports' => [
                'link' => '#',
                'submenu' => [
                    'Character Type' => [
                        'link' => '/st_tools.php?action=character_search'
                    ],
                    'Character Population' => [
                        'link' => '/st_tools.php?action=character_population_report'
                    ],
                    'Power Search' => [
                        'link' => '/st_tools.php?action=power_search'
                    ],
                    'Player Preference Venue Report' => [
                        'link' =>[
                            'controller' => 'playPreferences',
                            'action' => 'reportVenue'
                        ]
                    ],
                    'Player Preference Aggregate Report' => [
                        'link' => [
                            'controller' => 'playPreferences',
                            'action' => 'reportAggregate'
                        ]
                    ]
                ]
            ]
        ];

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
                'link' => '/requests/time-report'
            );
            $menu['Reports']['submenu']['Request Status Report'] = array(
                'link' => '/requests/status-report'
            );
            $menu['Reports']['submenu']['ST Activity Report'] = array(
                'link' => '/st_tools.php?action=st_activity_report'
            );
        }

        if ($this->Permissions->IsAdmin()) {
            $menu['Tools']['submenu']['Configuration'] = array(
                'link' => '/configurations'
            );
            $menu['Requests']['submenu']['Administration'] = array(
                'link' => [
                    'controller' => 'requests',
                    'action' => 'admin',
                ]
            );
        }

        return $menu;
    }

    public function createCharacterMenu($characterId, $characterName, $characterSlug = null)
    {
        $characterMenu = include_once(ROOT . '/lib/character_components.php');
        if (!is_array($characterMenu)) {
            return null;
        }

        return $characterMenu;
    }
}
