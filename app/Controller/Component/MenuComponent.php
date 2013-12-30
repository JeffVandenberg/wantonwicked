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
            'Home' => array(
                'link' => '/',
                'target' => '_top'
            ),
            'The Setting' => array(
                'link' => '#',
                'submenu' => array(
                    'The City' => array(
                        'link' => 'wiki/index.php?n=City.City'
                    ),
                    'Changeling' => array(
                        'link' => 'wiki/index.php?n=Changeling.Changeling',
                        'submenu' => array(
                            'House Rules' => array(
                                'link' => 'wiki/index.php?n=Changeling.HouseRules'
                            ),
                            'Player Guide' => array(
                                'link' => 'wiki/index.php?n=Changeling.PlayerGuide'
                            ),
                            'Cast' => array(
                                'link' => 'wiki/index.php?n=Players.Changeling'
                            ),
                        )
                    ),
                    'Geist' => array(
                        'link' => 'wiki/index.php?n=Geist.Geist',
                        'submenu' => array(
                            'House Rules' => array(
                                'link' => 'wiki/index.php?n=Geist.HouseRules'
                            ),
                            'Player Guide' => array(
                                'link' => 'wiki/index.php?n=Geist.PlayerGuide'
                            ),
                            'Cast' => array(
                                'link' => 'wiki/index.php?n=Players.Geist'
                            ),
                        )
                    ),
                    'Mage' => array(
                        'link' => 'wiki/index.php?n=Mage.Mage',
                        'submenu' => array(
                            'House Rules' => array(
                                'link' => 'wiki/index.php?n=Mage.HouseRules'
                            ),
                            'Player Guide' => array(
                                'link' => 'wiki/index.php?n=Mage.PlayerGuide'
                            ),
                            'Cast' => array(
                                'link' => 'wiki/index.php?n=Players.Mage'
                            ),
                        )
                    ),
                    'Mortal' => array(
                        'link' => 'wiki/index.php?n=Mortal.Mortal',
                        'submenu' => array(
                            'House Rules' => array(
                                'link' => 'wiki/index.php?n=Mortal.HouseRules'
                            ),
                            'Player Guide' => array(
                                'link' => 'wiki/index.php?n=Mortal.PlayerGuide'
                            ),
                            'Cast' => array(
                                'link' => 'wiki/index.php?n=Players.Mortal'
                            ),
                        )
                    ),
                    'Vampire' => array(
                        'link' => 'wiki/index.php?n=Vampire.Vampire',
                        'submenu' => array(
                            'House Rules' => array(
                                'link' => 'wiki/index.php?n=Vampire.HouseRules'
                            ),
                            'Player Guide' => array(
                                'link' => 'wiki/index.php?n=Vampire.PlayerGuide'
                            ),
                            'Cast' => array(
                                'link' => 'wiki/index.php?n=Players.Vampire'
                            ),
                        )
                    ),
                    'Werewolf' => array(
                        'link' => 'wiki/index.php?n=Werewolf.Werewolf',
                        'submenu' => array(
                            'House Rules' => array(
                                'link' => 'wiki/index.php?n=Werewolf.HouseRules'
                            ),
                            'Player Guide' => array(
                                'link' => 'wiki/index.php?n=Werewolf.PlayerGuide'
                            ),
                            'Cast' => array(
                                'link' => 'wiki/index.php?n=Players.Werewolf'
                            ),
                        )
                    ),
                    'Crossover Sub-venues' => array(
                        'link' => '#',
                        'submenu' => array(
                            'Whitefield' => array(
                                'link' => 'wiki/index.php?n=Whitefield.Whitefield',
                                'submenu' => array(
                                    'House Rules' => array(
                                        'link' => 'wiki/index.php?n=Whitefield.HouseRules'
                                    ),
                                    'Player Guide' => array(
                                        'link' => 'wiki/index.php?n=Whitefield.PlayerGuide'
                                    ),
                                    'Cast' => array(
                                        'link' => 'wiki/index.php?n=Players.Whitefield'
                                    ),
                                )
                            ),
                        )
                    ),
                    'The Cast' => array(
                        'link' => 'wiki/index.php?n=Players'
                    )
                )
            ),
            'Game Guide' => array(
                'link' => '#',
                'submenu' => array(
                    'House Rules' => array(
                        'link' => 'wiki/index.php?n=GameRef.HouseRules',
                        'submenu' => array(
                            'Crossover Errata' => array(
                                'link' => 'wiki/index.php?n=GameRef.CrossoverErrata'
                            )
                        )
                    ),
                    'Character Creation' => array(
                        'link' => 'wiki/index.php?n=GameRef.CharacterCreation',
                        'submenu' => array(
                            'Sanctioning Checklist' => array(
                                'link' => 'wiki/index.php?n=GameRef.SanctioningGuide'
                            ),
                            'Book Policy' => array(
                                'link' => 'wiki/index.php?n=GameRef.BookPolicy'
                            ),
                        )
                    ),
                    'Experience Guide' => array(
                        'link' => 'wiki/index.php?n=GameRef.ExperienceGuide'
                    ),
                    'Policies and Practices' => array(
                        'link' => 'wiki/index.php?n=GameRef.PoliciesandPractices',
                        'submenu' => array(
                            'Crossover Policy' => array(
                                'link' => 'wiki/index.php?n=GameRef.CrossoverPolicy'
                            )
                        )
                    ),
                )
            ),
            'Tools' => array(
                'link' => '#',
                'submenu' => array(
                    'Help' => array(
                        'link' => 'wiki/index.php?n=GameRef.Help',
                        'submenu' => array(
                            'Request System' => array(
                                'link' => 'wiki/index.php?n=GameRef.RequestSystemHelp',
                            ),
                            'Chat Interface' => array(
                                'link' => 'wiki/index.php?n=GameRef.ChatHelp',
                            )
                        )
                    ),
                    'Site Supporter' => array(
                        'link' => 'support.php'
                    )
                )
            ),
            'Forums' => array(
                'link' => 'forum/index.php'
            ),
            'Site Info' => array(
                'link' => '#',
                'submenu' => array(
                    'The Team' => array(
                        'link' => array('controller' => 'home', 'action' => 'staff')
                    ),
                    'Code of Conduct' => array(
                        'link' => 'wiki/index.php?n=GameRef.CodeOfConduct',
                        'submenu' => array(
                            'Personal Information' => array(
                                'link' => 'wiki/index.php?n=GameRef.PersonalInformation',
                            )
                        )
                    ),
                    'Disclaimer' => array(
                        'link' => 'wiki/index.php?n=GameRef.Disclaimer',
                    )
                )
            )
        );

        if($this->Auth->loggedIn()) {
            $this->menu['Tools']['submenu']['Game/Chat Interface'] = array(
                'link' => '/chat.php'
            );
        }

        if($this->Permissions->IsST()) {
            $this->menu['Tools']['submenu']['Storyteller Tools'] = array(
                'link' => '/storyteller_index.php'
            );
        }

        if($this->Permissions->IsAdmin()) {
            $this->menu['Tools']['submenu']['Site Supporter']['submenu']['Manage Support'] = array(
                'link' => '/support.php?action=manage'
            );
        }
    }

    public function GetMenu()
    {
        return $this->menu;
    }


}