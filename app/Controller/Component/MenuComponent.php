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
        $this->menu = array(
            'Home'       => array(
                'link'   => '/',
                'target' => '_top'
            ),
            'Forums'     => array(
                'link' => '/forum/index.php',
            ),
            'Utilities' => array(
                'link'    => '#',
                'submenu' => array(
                    'Scenes' => array(
                        'link' => array(
                            'controller' => 'scenes',
                            'action' => 'index'
                        )
                    )
                )
            ),
            'Help' => array(
				'link' => '#',
					'submenu' => array(
						'Meet the Team' => array(
                        'link' => array('controller' => 'home', 'action' => 'staff'
                        )
                    ),
					'Terms of Use' => array(
						'link' => '/wiki/index.php?n=GameRef.TermsOfUse',
					),
					'Code of Conduct' => array(
					   'link' => '/wiki/index.php?n=GameRef.CodeOfConduct',
					),
					'Site Policies' => array(
						'link' => '/wiki/index.php?n=GameRef.SitePoliciesAndPractices',
					),
					'New Player Help' => array(
						'link' => '/wiki/index.php?n=GameRef.Help',
					),
                   'Guides' => array(
						'link' => '#',
						'submenu' => array(
							'House Rules' => array(
								'link' => '/wiki/index.php?n=GameRef.HouseRules',
							),
							'Wiki Guide' => array(
								'link' => '/wiki/index.php?n=GameRef.WikiGuide',
							),
							'Chat Help' => array(
								'link' => '/wiki/index.php?n=GameRef.ChatHelp',
							),
							'Request System' => array(
								'link' => '/wiki/index.php?n=GameRef.RequestSystemHelp',
							),
							'Experience Guide' => array(
								'link' => '/wiki/index.php?n=GameRef.ExperienceGuide',
							),
							'Sanctioning Guide' => array(
								'link' => '/wiki/index.php?n=GameRef.SanctioningGuide',
							)
						)
					),
                    'Character Creation' => array(
                        'link' => '/wiki/index.php?n=GameRef.CharacterCreation'
                    )
                )
            ),
            'The City' => array(
				'link' => '#',
				'submenu' => array(
					'Setting Introduction' => array(
						'link' => '/wiki/index.php?n=City.City'
					),
					'The Embassy' => array(
						'link' => '/wiki/index.php?n=City.Embassy'
					),
					'Spheres' => array(
						'link' => '/wiki/index.php?n=City.Spheres'
					),
					'Map' => array(
						'link' => '/mapww5/map.html'
					),
					'The Districts' => array(
						'link' => '/wiki/index.php?n=City.Districts'
					),
					'Cast List' => array(
						'link' => '/characters/cast/'
					)
				)
			),
            'The Venues' => array(
				'link' => '#',
				'submenu' => array(
					'Changeling' => array(
						'link' => '#',
						'submenu' => array(
							'About the Venue' => array(
								'link' => '/wiki/index.php?n=Changeling.Changeling'
							),
							'Changeling Player Guide' => array(
								'link' => '/wiki/index.php?n=Changeling.PlayerGuide'
							),
							'Changeling Forums' => array(
								'link' => '/forum/viewforum.php?f=615'
							),
							'Changeling Cast List' => array(
								'link' => '/characters/cast/changeling'
							),
							'Fae-Touched Cast List' => array(
								'link' => '/characters/cast/faetouched'
							)
						)
					),
					'Mage' => array(
						'link' => '#',
						'submenu' => array(
							'About the Venue' => array(
								'link' => '/wiki/index.php?n=Mage.Mage'
							),
							'Mage Player Guide' => array(
								'link' => '/wiki/index.php?n=Mage.PlayerGuide'
							),
							'Mage Forums' => array(
								'link' => '/forum/viewforum.php?f=624'
							),
							'Mage Cast List' => array(
								'link' => '/characters/cast/mage'
							),
							'Sleepwalker Cast List' => array(
								'link' => '/characters/cast/sleepwalker'
							)
						)
					),
					'Mortal/+' => array(
						'link' => '#',
						'submenu' => array(
							'About the Venue' => array(
								'link' => '/wiki/index.php?n=Mortal.Mortal'
							),
							'Mortal/+ Player Guide' => array(
								'link' => '/wiki/index.php?n=Mortal.PlayerGuide'
							),
							'Mortal/+ Forums' => array(
								'link' => '/forum/viewforum.php?f=625'
							),
							'Mortal/+ Cast List' => array(
								'link' => '/characters/cast/mortal'
							)
						)
					),
					'Vampire' => array(
						'link' => '#',
						'submenu' => array(
							'About the Venue' => array(
								'link' => '/wiki/index.php?n=Vampire.Vampire'
							),
							'Vampire Player Guide' => array(
								'link' => '/wiki/index.php?n=Vampire.PlayerGuide'
							),
							'Vampire Forums' => array(
								'link' => '/forum/viewforum.php?f=665'
							),
							'Vampire Cast List' => array(
								'link' => '/characters/cast/vampire'
							),
							'Ghoul Cast List' => array(
								'link' => '/characters/cast/ghoul'
							)
						)
					),
					'Werewolf' => array(
						'link' => '#',
						'submenu' => array(
							'About the Venue' => array(
								'link' => '/wiki/index.php?n=Werewolf.Werewolf'
							),
							'Werewolf Player Guide' => array(
								'link' => '/wiki/index.php?n=Werewolf.PlayerGuide'
							),
							'Werewolf Forums' => array(
								'link' => '/forum/viewforum.php?f=626'
							),
							'Werewolf Cast List' => array(
								'link' => '/characters/cast/werewolf'
							),
							'Wolfblooded Cast List' => array(
								'link' => '/characters/cast/wolfblooded'
							)
						)
					)
        );

        if($this->Auth->loggedIn()) {
            $this->menu['Utilities']['submenu']['Characters'] = array(
                'link' => '#',
                'submenu' => array(
                    'Dashboard' => [
                        'link' => '/chat.php',
                    ],
                    'Create a Character' => array(
                        'link' => '/view_sheet.php?action=create_xp'
                    )
                )
            );
            $this->menu['Utilites']['submenu']['Requests'] = array(
                'link' => '/request.php'
            );
            $this->menu['Utilites']['submenu']['Play Preferences'] = [
                'link' => [
                    'controller' => 'play_preferences',
                    'action' => 'index'
                ]
            ];

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
                            'link' => '/view_sheet.php?action=view_own_xp&character_id=' . $character['Character']['id']
                        )
                    )
                );
                $this->menu['Utilities']['submenu']['Characters']['submenu'][$character['Character']['character_name']] = $characterMenu;
            }
        }

        if($this->Permissions->IsSupporter(AuthComponent::user('user_id'))) {
            $this->menu['Utilities']['submenu']['Site Supporter']['submenu']['Update Supporter Status'] = array(
                'link' => '/support.php?action=setCharacters'
            );
        }

        if($this->Permissions->IsST()) {
            $this->menu['Utilities']['submenu']['Staff Utilities'] = array(
                'link' => '#',
                'submenu' => array(
                    'Dashboard' => [
                        'link' => '/storyteller_index.php',
                    ],
                    'Character Lookup' => array(
                        'link' => '/view_sheet.php?action=st_view_xp',
                    ),
                    'Requests' => array(
                        'link' => '/request.php?action=st_list'
                    ),
                    'Chat Login' => array(
                        'link' => '/chat/?st_login',
                        'target' => '_blank'
                    )
                )
            );
        }

        if($this->Permissions->IsAdmin()) {
            $this->menu['Utilities']['submenu']['Site Supporter']['submenu']['Manage Support'] = array(
                'link' => '/support.php?action=manage'
            );
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
                        'link' => '/view_sheet.php?action=st_view_xp'
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
