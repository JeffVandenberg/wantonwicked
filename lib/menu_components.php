<?php
/**
 * Created by PhpStorm.
 * User: JeffVandenberg
 * Date: 11/27/2016
 * Time: 8:21 PM
 */

namespace app\Lib;


$menuComponents = [];
$menuComponents['base'] = [
    'Forums' => [
        'link' => '/forum/index.php',
    ],
    'Utilities' => [
        'link' => '#',
        'submenu' => [
            'Plots' => [
                'link' => '/plots',
            ],
            'Scenes' => [
                'link' => '/scenes'
            ]
        ]
    ],
    'Characters' => [
    ],
    'Staff Utilities' => [
    ],
    'Help' => [
        'link' => '#',
        'submenu' => [
            'Meet the Team' => [
                'link' => '/staff'
            ],
            'Terms of Use' => [
                'link' => '/wiki/GameRef/TermsOfUse',
            ],
            'Code of Conduct' => [
                'link' => '/wiki/GameRef/CodeOfConduct',
            ],
            'Site Policies' => [
                'link' => '/wiki/GameRef/SitePoliciesAndPractices',
            ],
            'House Rules' => [
                'link' => '#',
                'submenu' => [
                    'Home' => [
                        'link' => '/wiki/GameRef/HouseRules',
                    ],
                    'Custom Item/Powers' => [
                        'link' => '/wiki/GameRef/CustomItemsAndPowers'
                    ]
                ]
            ],
            'New Player Help' => [
                'link' => '/wiki/GameRef/Help',
            ],
            'Guides' => [
                'link' => '#',
                'submenu' => [
                    'Wiki Guide' => [
                        'link' => '/wiki/GameRef/Wiki',
                    ],
                    'Chat Help' => [
                        'link' => '/wiki/GameRef/Chat',
                    ],
                    'Request System' => [
                        'link' => '/wiki/GameRef/Requests',
                    ],
                    'Experience Guide' => [
                        'link' => '/wiki/GameRef/Experience',
                    ],
                    'Sanctioning Guide' => [
                        'link' => '/wiki/GameRef/Sanctioning',
                    ]
                ]
            ],
            'Character Creation' => [
                'link' => '/wiki/GameRef/CharacterCreation'
            ]
        ]
    ],
    'The City' => [
        'link' => '#',
        'submenu' => [
            'Setting Introduction' => [
                'link' => '/wiki/City/City'
            ],
            'The Embassy' => [
                'link' => '/wiki/City/Embassy'
            ],
            'Spheres' => [
                'link' => '/wiki/City/Spheres'
            ],
            'Map' => [
                'link' => '/mapww5/map.html'
            ],
            'The Districts' => [
                'link' => '/wiki/City/Districts'
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
                        'link' => '/wiki/Changeling/Changeling'
                    ],
                    'Changeling Player Guide' => [
                        'link' => '/wiki/Changeling/PlayerGuide'
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
                        'link' => '/wiki/Mage/Mage'
                    ],
                    'Mage Player Guide' => [
                        'link' => '/wiki/Mage/PlayerGuide'
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
                        'link' => '/wiki/Mortal/Mortal'
                    ],
                    'Mortal/+ Player Guide' => [
                        'link' => '/wiki/Mortal/PlayerGuide'
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
                        'link' => '/wiki/Vampire/Vampire'
                    ],
                    'Vampire Player Guide' => [
                        'link' => '/wiki/Vampire/PlayerGuide'
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
                        'link' => '/wiki/Werewolf/Werewolf'
                    ],
                    'Werewolf Player Guide' => [
                        'link' => '/wiki/Werewolf/PlayerGuide'
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
            'Requests' => [
                'link' => '/requests/'
            ],
            'Bluebooks' => [
                'link' => '/bluebooks/'
            ],
            'Play Preferences' => [
                'link' => '/play_preferences'
            ],
        ]
    ],
    'Characters' => [
        'link' => '#',
        'submenu' => [
            'List' => [
                'link' => '/characters/'
            ]
        ]
    ],
];
$menuComponents['staff'] = [
    'Staff Utilities' => [
        'link' => '#',
        'submenu' => [
            'Dashboard' => [
                'link' => '/storyteller_index.php',
            ],
            'Character Lookup' => [
                'link' => '/characters/stView',
            ],
            'Request Dashboard' => [
                'link' => '/requests/st-dashboard'
            ],
            'Chat Login' => [
                'link' => '/chat/?st_login',
                'target' => '_blank'
            ]
        ]
    ]
];
return $menuComponents;
