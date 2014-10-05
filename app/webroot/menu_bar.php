<?php
global $userdata;
// check if they are an ST

use classes\character\repository\CharacterRepository;
use classes\core\helpers\MenuHelper;
use classes\core\helpers\UserdataHelper;

$mainMenu = array(
    'Home' => array(
        'link' => '/',
        'target' => '_top'
    ),
    'The Setting' => array(
        'link' => '#',
        'submenu' => array(
            'The City' => array(
                'link' => '/wiki/index.php?n=City.City'
            ),
            'Changeling' => array(
                'link' => '/wiki/index.php?n=Changeling.Changeling',
                'submenu' => array(
                    'House Rules' => array(
                        'link' => '/wiki/index.php?n=Changeling.HouseRules'
                    ),
                    'Player Guide' => array(
                        'link' => '/wiki/index.php?n=Changeling.PlayerGuide'
                    ),
                    'Cast' => array(
                        'link' => '/characters/cast/Changeling'
                    ),
                )
            ),
            'Geist' => array(
                'link' => '/wiki/index.php?n=Geist.Geist',
                'submenu' => array(
                    'House Rules' => array(
                        'link' => '/wiki/index.php?n=Geist.HouseRules'
                    ),
                    'Player Guide' => array(
                        'link' => '/wiki/index.php?n=Geist.PlayerGuide'
                    ),
                    'Cast' => array(
                        'link' => '/characters/cast/Geist'
                    ),
                )
            ),
            'Mage' => array(
                'link' => '/wiki/index.php?n=Mage.Mage',
                'submenu' => array(
                    'House Rules' => array(
                        'link' => '/wiki/index.php?n=Mage.HouseRules'
                    ),
                    'Player Guide' => array(
                        'link' => '/wiki/index.php?n=Mage.PlayerGuide'
                    ),
                    'Cast' => array(
                        'link' => '/characters/cast/Mage'
                    ),
                )
            ),
            'Mortal' => array(
                'link' => '/wiki/index.php?n=Mortal.Mortal',
                'submenu' => array(
                    'House Rules' => array(
                        'link' => '/wiki/index.php?n=Mortal.HouseRules'
                    ),
                    'Player Guide' => array(
                        'link' => '/wiki/index.php?n=Mortal.PlayerGuide'
                    ),
                    'Cast' => array(
                        'link' => '/characters/cast/Mortal'
                    ),
                )
            ),
            'Vampire' => array(
                'link' => '/wiki/index.php?n=Vampire.Vampire',
                'submenu' => array(
                    'House Rules' => array(
                        'link' => '/wiki/index.php?n=Vampire.HouseRules'
                    ),
                    'Player Guide' => array(
                        'link' => '/wiki/index.php?n=Vampire.PlayerGuide'
                    ),
                    'Cast' => array(
                        'link' => '/characters/cast/Vampire'
                    ),
                )
            ),
            'Werewolf' => array(
                'link' => '/wiki/index.php?n=Werewolf.Werewolf',
                'submenu' => array(
                    'House Rules' => array(
                        'link' => '/wiki/index.php?n=Werewolf.HouseRules'
                    ),
                    'Player Guide' => array(
                        'link' => '/wiki/index.php?n=Werewolf.PlayerGuide'
                    ),
                    'Cast' => array(
                        'link' => '/characters/cast/Werewolf'
                    ),
                )
            ),
            'Crossover Sub-venues' => array(
                'link' => '#',
                'submenu' => array(
                    'Whitefield' => array(
                        'link' => '/wiki/index.php?n=Whitefield.Whitefield',
                        'submenu' => array(
                            'House Rules' => array(
                                'link' => '/wiki/index.php?n=Whitefield.HouseRules'
                            ),
                            'Player Guide' => array(
                                'link' => '/wiki/index.php?n=Whitefield.PlayerGuide'
                            ),
                        )
                    ),
                )
            ),
            'The Cast' => array(
                'link' => '/characters/cast'
            )
        )
    ),
    'Game Guide' => array(
        'link' => '#',
        'submenu' => array(
            'House Rules' => array(
                'link' => '/wiki/index.php?n=GameRef.HouseRules',
                'submenu' => array(
                    'Crossover Errata' => array(
                        'link' => '/wiki/index.php?n=GameRef.CrossoverErrata'
                    )
                )
            ),
            'Character Creation' => array(
                'link' => '/wiki/index.php?n=GameRef.CharacterCreation',
                'submenu' => array(
                    'Sanctioning Checklist' => array(
                        'link' => '/wiki/index.php?n=GameRef.SanctioningGuide'
                    ),
                    'Book Policy' => array(
                        'link' => '/wiki/index.php?n=GameRef.BookPolicy'
                    ),
                )
            ),
            'Experience Guide' => array(
                'link' => '/wiki/index.php?n=GameRef.ExperienceGuide'
            ),
            'Policies and Practices' => array(
                'link' => '/wiki/index.php?n=GameRef.PoliciesandPractices',
                'submenu' => array(
                    'Crossover Policy' => array(
                        'link' => '/wiki/index.php?n=GameRef.CrossoverPolicy'
                    )
                )
            ),
        )
    ),
    'Tools' => array(
        'link' => '#',
        'submenu' => array(
            'Help' => array(
                'link' => '/wiki/index.php?n=GameRef.Help',
                'submenu' => array(
                    'Request System' => array(
                        'link' => '/wiki/index.php?n=GameRef.RequestSystemHelp',
                    ),
                    'Chat Interface' => array(
                        'link' => '/wiki/index.php?n=GameRef.ChatHelp',
                    )
                )
            ),
            'Site Supporter' => array(
                'link' => 'support.php'
            )
        )
    ),
    'Forums' => array(
        'link' => '/forum/index.php'
    ),
    'Site Info' => array(
        'link' => '#',
        'submenu' => array(
            'The Team' => array(
                'link' => array('controller' => 'home', 'action' => 'staff')
            ),
            'Code of Conduct' => array(
                'link' => '/wiki/index.php?n=GameRef.CodeOfConduct',
                'submenu' => array(
                    'Personal Information' => array(
                        'link' => '/wiki/index.php?n=GameRef.PersonalInformation',
                    )
                )
            ),
            'Disclaimer' => array(
                'link' => '/wiki/index.php?n=GameRef.Disclaimer',
            )
        )
    )
);

if($userdata['user_id'] != ANONYMOUS) {
    $mainMenu['Tools']['submenu']['Character List'] = array(
        'link' => '/chat.php'
    );

    $characterRepository = new CharacterRepository();
    $sanctionedCharacters = $characterRepository->ListSanctionedCharactersByPlayerId($userdata['user_id']);
    foreach($sanctionedCharacters as $character) {
        $characterMenu = array(
            'link' => '/character.php?action=interface&character_id='.$character['id'],
            'submenu' => array(
                'Login' => array(
                    'link' => '/chat/?character_id='.$character['id']
                ),
                'Requests' => array(
                    'link' => '/request.php?action=list&character_id=' . $character['id']
                ),
                'Bluebook' => array(
                    'link' => '/bluebook.php?action=list&character_id=' . $character['id']
                ),
                'Sheet' => array(
                    'link' => '/view_sheet.php?action=view_own_xp&character_id=' . $character['id']
                )
            )
        );
        $mainMenu['Tools']['submenu']['Character List']['submenu'][$character['character_name']] = $characterMenu;
    }
}

if(UserdataHelper::IsSt($userdata)) {
    $mainMenu['Tools']['submenu']['ST Tools'] = array(
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

if(UserdataHelper::IsAdmin($userdata)) {
    $mainMenu['Tools']['submenu']['Site Supporter']['submenu']['Manage Support'] = array(
        'link' => '/support.php?action=manage'
    );
}

$menu_bar = MenuHelper::GenerateMenu($mainMenu);