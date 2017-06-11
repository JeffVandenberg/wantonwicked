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

        if ($this->Auth->user()) {
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
                            'link' => '/request.php?action=list&character_id=' . $character->id
                        ],
                        'Bluebook' => [
                            'link' => '/bluebook.php?action=list&character_id=' . $character->id
                        ],
                        'Sheet' => [
                            'link' => '/characters/viewOwn/' . $character->slug
                        ]
                    ]
                ];
                $this->menu['Utilities']['submenu']['Characters']['submenu'][$character->character_name] = $characterMenu;
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
                        'link' => '/request.php?action=st_list'
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
