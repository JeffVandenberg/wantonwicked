<?php
/**
 * Created by PhpStorm.
 * User: JeffVandenberg
 * Date: 11/27/2016
 * Time: 8:21 PM
 */

$menuComponents = [];
$menuComponents['base'] = [
    'Home' => [
        'link' => '/',
        'target' => '_top'
    ],
    'New Option' => [
        'link' => '#',
        'onclick' => 'javascript:alert("hello");'
    ],
    'Forums' => [
        'link' => '/forum/index.php',
    ],
    'Utilities' => [
        'link' => '#',
        'submenu' => [
            'Scenes' => [
                'link' => '/scenes'
            ]
        ]
    ],
    'Help' => [
        'link' => '#',
        'submenu' => [
            'Meet the Team' => [
                'link' => '/staff'
            ],
            'Terms of Use' => [
                'link' => '/wiki/index.php?n=GameRef.TermsOfUse',
            ],
            'Code of Conduct' => [
                'link' => '/wiki/index.php?n=GameRef.CodeOfConduct',
            ],
            'Site Policies' => [
                'link' => '/wiki/index.php?n=GameRef.SitePoliciesAndPractices',
            ],
            'House Rules' => [
                'link' => '/wiki/index.php?n=GameRef.HouseRules',
            ],
            'New Player Help' => [
                'link' => '/wiki/index.php?n=GameRef.Help',
            ],
            'Guides' => [
                'link' => '#',
                'submenu' => [
                    'Wiki Guide' => [
                        'link' => '/wiki/index.php?n=GameRef.Wiki',
                    ],
                    'Chat Help' => [
                        'link' => '/wiki/index.php?n=GameRef.Chat',
                    ],
                    'Request System' => [
                        'link' => '/wiki/index.php?n=GameRef.Requests',
                    ],
                    'Experience Guide' => [
                        'link' => '/wiki/index.php?n=GameRef.Experience',
                    ],
                    'Sanctioning Guide' => [
                        'link' => '/wiki/index.php?n=GameRef.Sanctioning',
                    ]
                ]
            ],
            'Character Creation' => [
                'link' => '/wiki/index.php?n=GameRef.CharacterCreation'
            ]
        ]
    ],
    'The City' => [
        'link' => '#',
        'submenu' => [
            'Setting Introduction' => [
                'link' => '/wiki/index.php?n=City.City'
            ],
            'The Embassy' => [
                'link' => '/wiki/index.php?n=City.Embassy'
            ],
            'Spheres' => [
                'link' => '/wiki/index.php?n=City.Spheres'
            ],
            'Map' => [
                'link' => '/mapww5/map.html'
            ],
            'The Districts' => [
                'link' => '/wiki/index.php?n=City.Districts'
            ],
            'Cast List' => [
                'link' => '/characters/cast/'
            ]
        ]
    ],
    'The Venues' => [
        'link' => '#',
        'submenu' => [
            'Changeling' => [
                'link' => '#',
                'submenu' => [
                    'About the Venue' => [
                        'link' => '/wiki/index.php?n=Changeling.Changeling'
                    ],
                    'Changeling Player Guide' => [
                        'link' => '/wiki/index.php?n=Changeling.PlayerGuide'
                    ],
                    'Changeling Forums' => [
                        'link' => '/forum/viewforum.php?f=615'
                    ],
                    'Changeling Cast List' => [
                        'link' => '/characters/cast/changeling'
                    ],
                    'Fae-Touched Cast List' => [
                        'link' => '/characters/cast/faetouched'
                    ]
                ]
            ],
            'Mage' => [
                'link' => '#',
                'submenu' => [
                    'About the Venue' => [
                        'link' => '/wiki/index.php?n=Mage.Mage'
                    ],
                    'Mage Player Guide' => [
                        'link' => '/wiki/index.php?n=Mage.PlayerGuide'
                    ],
                    'Mage Forums' => [
                        'link' => '/forum/viewforum.php?f=624'
                    ],
                    'Mage Cast List' => [
                        'link' => '/characters/cast/mage'
                    ]
                ]
            ],
            'Mortal/+' => [
                'link' => '#',
                'submenu' => [
                    'About the Venue' => [
                        'link' => '/wiki/index.php?n=Mortal.Mortal'
                    ],
                    'Mortal/+ Player Guide' => [
                        'link' => '/wiki/index.php?n=Mortal.PlayerGuide'
                    ],
                    'Mortal/+ Forums' => [
                        'link' => '/forum/viewforum.php?f=625'
                    ],
                    'Mortal/+ Cast List' => [
                        'link' => '/characters/cast/mortal'
                    ]
                ]
            ],
            'Vampire' => [
                'link' => '#',
                'submenu' => [
                    'About the Venue' => [
                        'link' => '/wiki/index.php?n=Vampire.Vampire'
                    ],
                    'Vampire Player Guide' => [
                        'link' => '/wiki/index.php?n=Vampire.PlayerGuide'
                    ],
                    'Vampire Forums' => [
                        'link' => '/forum/viewforum.php?f=665'
                    ],
                    'Vampire Cast List' => [
                        'link' => '/characters/cast/vampire'
                    ],
                    'Ghoul Cast List' => [
                        'link' => '/characters/cast/ghoul'
                    ]
                ]
            ],
            'Werewolf' => [
                'link' => '#',
                'submenu' => [
                    'About the Venue' => [
                        'link' => '/wiki/index.php?n=Werewolf.Werewolf'
                    ],
                    'Werewolf Player Guide' => [
                        'link' => '/wiki/index.php?n=Werewolf.PlayerGuide'
                    ],
                    'Werewolf Forums' => [
                        'link' => '/forum/viewforum.php?f=626'
                    ],
                    'Werewolf Cast List' => [
                        'link' => '/characters/cast/werewolf'
                    ],
                    'Wolfblooded Cast List' => [
                        'link' => '/characters/cast/wolfblooded'
                    ]
                ]
            ]
        ]
    ]
];
$menuComponents['player'] = [
    'Utilities' => [
        'submenu' => [
            'Player Utilities' => [
                'link' => '#',
                'submenu' => [
                    'Dashboard' => [
                        'link' => '/chat.php',
                    ],
                    'Create a Character' => [
                        'link' => '/view_sheet.php?action=create_xp'
                    ],
                    'Requests' => [
                        'link' => '/request.php'
                    ],
                    'Play Preferences' => [
                        'link' => '/play_preferences'
                    ]
                ]
            ]
        ]
    ]
];
$menuComponents['staff'] = [
    'Utilities' => [
        'submenu' => [
            'Staff Utilities' => [
                'link' => '#',
                'submenu' => [
                    'Dashboard' => [
                        'link' => '/storyteller_index.php',
                    ],
                    'Character Lookup' => [
                        'link' => '/view_sheet.php?action=st_view_xp',
                    ],
                    'Request Dashboard' => [
                        'link' => '/request.php?action=st_list'
                    ],
                    'Chat Login' => [
                        'link' => '/chat/?st_login',
                        'target' => '_blank'
                    ]
                ]
            ]
        ]
    ]
];
return $menuComponents;
