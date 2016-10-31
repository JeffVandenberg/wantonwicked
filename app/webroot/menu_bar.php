<?php

use classes\character\repository\CharacterRepository;
use classes\core\helpers\MenuHelper;
use classes\core\helpers\UserdataHelper;

/* @var array $userdata */
$mainMenu = array(
    'Home' => array(
        'link' => '/',
        'target' => '_top'
    ),
	'The City' => array(
        'link' => '#',
        'submenu' => array(
            'Setting Introduction' => array(
                'link' => '/wiki/index.php?n=City.City'
            ),
            'Map' => array(
                'link' => '/mapww5/map.html'
            ),
            'The Districts' => array(
                'link' => '/wiki/index.php?n=City.Districts'
            ),
			'The Embassy' => array(
                'link' => '/wiki/index.php?n=City.Embassy'
            ),
			'Spheres' => array(
                'link' => '/wiki/index.php?n=City.Spheres'
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
            ),
        )
    ),
    'Sidegames' => array(
        'link' => '#',
        'submenu' => array(
            'Sidegame List' => array(
                'link' => '/wiki/index.php?n=Sidegames.Sidegames'
            ),
            'Sidegame Forums' => array(
                'link' => '/forum/viewforum.php?f=763'
            )
        )
    ),	
    'Forums' => array(
        'link' => '/forum/index.php',
    ),
	'Help' => array(
        'link' => '#',
        'submenu' => array(
            'Meet the Team' => array(
                'link' => '/staff'
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
    'Utilities' => array(
        'link' => '#',
        'submenu' => array(
            'Site Supporter' => array(
                'link' => '#',
                'submenu' => array(
                    'Home' => [
                        'link' => '/support.php',
                    ],
                    'Support Information' => [
                        'link' => '/wiki/index.php?n=GameRef.5For5Offer'
                    ]
                )
            ),
            'Scenes' => array(
                'link' => '/scenes'
            )
        )
    ),    
    'Sidegames' => array(
        'link' => '#',
        'submenu' => array(
            'Sidegame List' => array(
                'link' => '/wiki/index.php?n=Sidegames.Sidegames'
            ),
            'Sidegame Forums' => array(
                'link' => '/forum/viewforum.php?f=763'
            )
        )
    )
);

if ($userdata['user_id'] != ANONYMOUS) {
    $mainMenu['Site Tools']['submenu']['Characters'] = array(
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

    $characterRepository = new CharacterRepository();
    $sanctionedCharacters = $characterRepository->ListSanctionedCharactersByPlayerId($userdata['user_id']);
    foreach ($sanctionedCharacters as $character) {
        $characterMenu = array(
            'link' => '/character.php?action=interface&character_id=' . $character['id'],
            'submenu' => array(
                'Login' => array(
                    'link' => '/chat/?character_id=' . $character['id']
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
        $mainMenu['Utilities']['submenu']['Characters']['submenu'][$character['character_name']] = $characterMenu;
    }

    $mainMenu['Utilities']['submenu']['Requests'] = array(
        'link' => '/request.php'
    );
    $mainMenu['Utilities']['submenu']['Play Preferences'] = array(
        'link' => '/play_preferences'
    );
}


if (UserdataHelper::IsSt($userdata)) {
    $mainMenu['Utilities']['submenu']['Staff'] = array(
        'link' => '#',
        'submenu' => array(
            'Dashboard' => [
                'link' => '/storyteller_index.php',
            ],
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

if (UserdataHelper::IsAdmin($userdata)) {
    $mainMenu['Utilities']['submenu']['Site Supporter']['submenu']['Manage Support'] = array(
        'link' => '/support.php?action=manage'
    );
}

if (UserdataHelper::IsSupporter($userdata)) {
    $mainMenu['Utilities']['submenu']['Site Supporter']['submenu']['Update Update Status'] = array(
        'link' => '/support.php?action=setCharacters'
    );
}

$menu_bar = MenuHelper::GenerateMenu($mainMenu);
return $mainMenu;
