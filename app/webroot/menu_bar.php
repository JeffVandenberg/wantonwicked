<?php
global $userdata;
// check if they are an ST

use classes\character\repository\CharacterRepository;
use classes\core\helpers\MenuHelper;
use classes\core\helpers\UserdataHelper;

$mainMenu = array(
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
                        'link' => '/wiki/index.php?n=GameRef.Help',
                    ),
					'Wiki Guide'         => array(
                        'link' => '/wiki/index.php?n=GameRef.WikiGuide',
                    ),
                    'Chat Help'         => array(
                        'link' => '/wiki/index.php?n=GameRef.ChatHelp',
                    ),
                    'Request System'    => array(
                        'link' => '/wiki/index.php?n=GameRef.RequestSystemHelp',
                    ),
                    'Experience Guide'  => array(
                        'link' => '/wiki/index.php?n=GameRef.ExperienceGuide',
                    ),
                    'Sanctioning Guide' => array(
                        'link' => '/wiki/index.php?n=GameRef.SanctioningGuide',
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
                    'Vampire Cast List'           => array(
                        'link' => '/characters/cast/vampire'
                    ),
					'Ghoul Cast List'           => array(
                        'link' => '/characters/cast/ghoul'
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
                    'Werewolf Cast List'            => array(
                        'link' => '/characters/cast/werewolf'
                    ),
					'Wolfblooded Cast List'            => array(
                        'link' => '/characters/cast/wolfblooded'
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

if ($userdata['user_id'] != ANONYMOUS) {
    $mainMenu['Site Tools']['submenu']['Character List'] = array(
        'link' => '/chat.php',
        'submenu' => array(
            'Create a Character' => array(
                'link' => '/view_sheet.php?action=create_xp'
            )
        )
    );

    $characterRepository  = new CharacterRepository();
    $sanctionedCharacters = $characterRepository->ListSanctionedCharactersByPlayerId($userdata['user_id']);
    foreach ($sanctionedCharacters as $character) {
        $characterMenu                                                                                = array(
            'link'    => '/character.php?action=interface&character_id=' . $character['id'],
            'submenu' => array(
                'Login'    => array(
                    'link' => '/chat/?character_id=' . $character['id']
                ),
                'Requests' => array(
                    'link' => '/request.php?action=list&character_id=' . $character['id']
                ),
                'Bluebook' => array(
                    'link' => '/bluebook.php?action=list&character_id=' . $character['id']
                ),
                'Sheet'    => array(
                    'link' => '/view_sheet.php?action=view_own_xp&character_id=' . $character['id']
                )
            )
        );
        $mainMenu['Site Tools']['submenu']['Character List']['submenu'][$character['character_name']] = $characterMenu;
    }
}

if (UserdataHelper::IsSt($userdata)) {
    $mainMenu['Site Tools']['submenu']['ST Tools'] = array(
        'link'    => '/storyteller_index.php',
        'submenu' => array(
            'Character Lookup'  => array(
                'link' => '/view_sheet.php?action=st_view_xp',
            ),
            'Request Dashboard' => array(
                'link' => '/request.php?action=st_list'
            ),
            'Chat Login'        => array(
                'link'   => '/chat/?st_login',
                'target' => '_blank'
            )
        )
    );
}

$menu_bar = MenuHelper::GenerateMenu($mainMenu);