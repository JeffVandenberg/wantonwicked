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
            'Site Tools' => array(
                'link'    => '#',
                'submenu' => array(
                    'Site Supporter' => array(
                        'link' => 'support.php'
                    )
                )
            ),
            'Help'       => array(
                'link'    => '#',
                'submenu' => array(
                    'Meet the Team'      => array(
                        'link' => array('controller' => 'home', 'action' => 'staff'
                        )
                    ),
                    'Site Policy'        => array(
                        'link'    => '#',
                        'submenu' => array(
                            'Code of Conduct' => array(
                                'link' => '/wiki/index.php?n=GameRef.CodeOfConduct',
                            ),
                            'Other Policies'  => array(
                                'link' => '/wiki/index.php?n=GameRef.PoliciesandPractices',
                            ),
                            'Disclaimer'      => array(
                                'link' => '/wiki/index.php?n=GameRef.Disclaimer',
                            ),
                            'Book Policy'     => array(
                                'link' => '/wiki/index.php?n=GameRef.BookPolicy',
                            )
                        )
                    ),
                    'Help Guides'        => array(
                        'link'    => '#',
                        'submenu' => array(
                            'New Player Help'   => array(
                                'link' => '/wiki/index.php?n=GameRef.CodeOfConduct',
                            ),
                            'Chat Help'         => array(
                                'link' => '/wiki/index.php?n=GameRef.PoliciesandPractices',
                            ),
                            'Request System'    => array(
                                'link' => '/wiki/index.php?n=GameRef.Disclaimer',
                            ),
                            'Experience Guide'  => array(
                                'link' => '/wiki/index.php?n=GameRef.Disclaimer',
                            ),
                            'Sanctioning Guide' => array(
                                'link' => '/wiki/index.php?n=GameRef.Disclaimer',
                            )
                        )
                    ),
                    'House Rules'        => array(
                        'link' => '/wiki/index.php?n=GameRef.HouseRules',
                    ),
                    'Crossover Rules'    => array(
                        'link' => '/wiki/index.php?n=GameRef.CrossoverErrata'
                    ),
                    'Character Creation' => array(
                        'link' => '/wiki/index.php?n=GameRef.CharacterCreation'
                    )
                )
            ),
            'The City'   => array(
                'link'    => '#',
                'submenu' => array(
                    'Setting Introduction' => array(
                        'link' => '/wiki/index.php?n=City.City'
                    ),
                    'City Map'             => array(
                        'link' => '/ww4map/map.html'
                    ),
                    'City Districts'       => array(
                        'link' => '/wiki/index.php?n=City.Districts'
                    ),
                    'Cast List'            => array(
                        'link' => '/characters/cast/'
                    ),
                    'Crossover Venues'     => array(
                        'link'    => '#',
                        'submenu' => array(
                            'The Shadow'            => array(
                                'link' => '/wiki/index.php?n=Shadow.Shadow'
                            ),
                            'Whitefield University' => array(
                                'link' => '/wiki/index.php?n=Whitefield.Whitefield'
                            ),
                        )
                    )
                )
            ),
            'The Venues' => array(
                'link'    => '#',
                'submenu' => array(
                    'Changeling' => array(
                        'link'    => '#',
                        'submenu' => array(
                            'Overview'               => array(
                                'link' => '/wiki/index.php?n=Changeling.Changeling'
                            ),
                            'Society'                => array(
                                'link' => '/wiki/index.php?n=Changeling.Society'
                            ),
                            'History'                => array(
                                'link' => '/wiki/index.php?n=Changeling.History'
                            ),
                            'Locations'              => array(
                                'link' => '/wiki/index.php?n=Changeling.Locations'
                            ),
                            'Changeling House Rules' => array(
                                'link' => '/wiki/index.php?n=Changeling.HouseRules'
                            ),
                            'Character Creation'     => array(
                                'link' => '/wiki/index.php?n=Changeling.PlayerGuide'
                            ),
                            'Changeling Forums'      => array(
                                'link' => '/forum/viewforum.php?f=615'
                            ),
                            'Cast List'              => array(
                                'link' => '/characters/cast/changeling'
                            )
                        )
                    ),
                    'Geist'      => array(
                        'link'    => '#',
                        'submenu' => array(
                            'Overview'           => array(
                                'link' => '/wiki/index.php?n=Geist.Geist'
                            ),
                            'Society'            => array(
                                'link' => '/wiki/index.php?n=Geist.Society'
                            ),
                            'History'            => array(
                                'link' => '/wiki/index.php?n=Geist.History'
                            ),
                            'Locations'          => array(
                                'link' => '/wiki/index.php?n=Geist.Locations'
                            ),
                            'Geist House Rules'  => array(
                                'link' => '/wiki/index.php?n=Geist.HouseRules'
                            ),
                            'Character Creation' => array(
                                'link' => '/wiki/index.php?n=Geist.PlayerGuide'
                            ),
                            'Geist Forums'       => array(
                                'link' => '/forum/viewforum.php?f=716'
                            ),
                            'Cast List'          => array(
                                'link' => '/characters/cast/geist'
                            )
                        )
                    ),
                    'Mage'       => array(
                        'link'    => '#',
                        'submenu' => array(
                            'Overview'           => array(
                                'link' => '/wiki/index.php?n=Mage.Mage'
                            ),
                            'Society'            => array(
                                'link' => '/wiki/index.php?n=Mage.Society'
                            ),
                            'History'            => array(
                                'link' => '/wiki/index.php?n=Mage.History'
                            ),
                            'Locations'          => array(
                                'link' => '/wiki/index.php?n=Mage.Locations'
                            ),
                            'Mage House Rules'   => array(
                                'link' => '/wiki/index.php?n=Mage.HouseRules'
                            ),
                            'Character Creation' => array(
                                'link' => '/wiki/index.php?n=Mage.PlayerGuide'
                            ),
                            'Mage Forums'        => array(
                                'link' => '/forum/viewforum.php?f=624'
                            ),
                            'Cast List'          => array(
                                'link' => '/characters/cast/mage'
                            )
                        )
                    ),
                    'Mortal'     => array(
                        'link'    => '#',
                        'submenu' => array(
                            'Overview'           => array(
                                'link' => '/wiki/index.php?n=Mortal.Mortal'
                            ),
                            'Society'            => array(
                                'link' => '/wiki/index.php?n=Mortal.Society'
                            ),
                            'History'            => array(
                                'link' => '/wiki/index.php?n=Mortal.History'
                            ),
                            'Locations'          => array(
                                'link' => '/wiki/index.php?n=Mortal.Locations'
                            ),
                            'Mortal House Rules' => array(
                                'link' => '/wiki/index.php?n=Mortal.HouseRules'
                            ),
                            'Character Creation' => array(
                                'link' => '/wiki/index.php?n=Mortal.PlayerGuide'
                            ),
                            'Mortal Forums'      => array(
                                'link' => '/forum/viewforum.php?f=625'
                            ),
                            'Cast List'          => array(
                                'link' => '/characters/cast/mortal'
                            )
                        )
                    ),
                    'Vampire'    => array(
                        'link'    => '#',
                        'submenu' => array(
                            'Overview'            => array(
                                'link' => '/wiki/index.php?n=Vampire.Vampire'
                            ),
                            'Society'             => array(
                                'link' => '/wiki/index.php?n=Vampire.Society'
                            ),
                            'History'             => array(
                                'link' => '/wiki/index.php?n=Vampire.History'
                            ),
                            'Locations'           => array(
                                'link' => '/wiki/index.php?n=Vampire.Locations'
                            ),
                            'Vampire House Rules' => array(
                                'link' => '/wiki/index.php?n=Vampire.HouseRules'
                            ),
                            'Character Creation'  => array(
                                'link' => '/wiki/index.php?n=Vampire.PlayerGuide'
                            ),
                            'Vampire Forums'      => array(
                                'link' => '/forum/viewforum.php?f=665'
                            ),
                            'Cast List'           => array(
                                'link' => '/characters/cast/vampire'
                            )
                        )
                    ),
                    'Werewolf'   => array(
                        'link'    => '#',
                        'submenu' => array(
                            'Overview'             => array(
                                'link' => '/wiki/index.php?n=Werewolf.Werewolf'
                            ),
                            'Society'              => array(
                                'link' => '/wiki/index.php?n=Werewolf.Society'
                            ),
                            'History'              => array(
                                'link' => '/wiki/index.php?n=Werewolf.History'
                            ),
                            'Locations'            => array(
                                'link' => '/wiki/index.php?n=Werewolf.Locations'
                            ),
                            'Werewolf House Rules' => array(
                                'link' => '/wiki/index.php?n=Werewolf.HouseRules'
                            ),
                            'Character Creation'   => array(
                                'link' => '/wiki/index.php?n=Werewolf.PlayerGuide'
                            ),
                            'Werewolf Forums'      => array(
                                'link' => '/forum/viewforum.php?f=626'
                            ),
                            'Cast List'            => array(
                                'link' => '/characters/cast/werewolf'
                            )
                        )
                    ),
                )
            ),
            'Sidegames'  => array(
                'link'    => '#',
                'submenu' => array(
                    'Sidegame List'   => array(
                        'link' => '/wiki/index.php?n=Sidegames.Sidegames'
                    ),
                    'Sidegame Forums' => array(
                        'link' => '/forum/viewforum.php?f=763'
                    )
                )
            )
        );

        if($this->Auth->loggedIn()) {
            $this->menu['Site Tools']['submenu']['Character List'] = array(
                'link' => '/chat.php'
            );
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
                $this->menu['Site Tools']['submenu']['Character List']['submenu'][$character['Character']['character_name']] = $characterMenu;
            }
        }

        if($this->Permissions->IsST()) {
            $this->menu['Site Tools']['submenu']['ST Tools'] = array(
                'link' => '/storyteller_index.php',
                'submenu' => array(
                    'Character Lookup' => array(
                        'link' => '/view_sheet.php?action=st_view_xp',
                    ),
                    'Request Dashboard' => array(
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
            $this->menu['Site Tools']['submenu']['Site Supporter']['submenu']['Manage Support'] = array(
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
                    'Login (Invisible)' => array(
                        'link' => '/chat/?st_login&invisible',
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
                    'Power Search' => array(
                        'link' => '/st_tools.php?action=power_search'
                    ),
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
            $menu['Tools']['submenu']['Icons'] = array(
                'link' => '/st_tools.php?action=icons_list'
            );
            $menu['Tools']['submenu']['Character Transfer'] = array(
                'link' => '/st_tools.php?action=profile_transfer'
            );
            $menu['Reports']['submenu']['Request Time Report'] = array(
                'link' => '/request.php?action=admin_time_report'
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