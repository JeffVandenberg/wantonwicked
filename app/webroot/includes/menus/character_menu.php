<?php
use classes\character\data\Character;
use classes\core\repository\RepositoryManager;

/* @var int $characterId */
$characterRepository = RepositoryManager::GetRepository('classes\character\data\Character');
$privateCharacter = $characterRepository->getById($characterId);
/* @var Character $privateCharacter */

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
//                'link' => "/characters/viewOwn/" . $privateCharacter->Slug
                'link' => "/characters/viewOwn/" . $characterId
            ],
            'Beats' => [
                'link' => '/characters/beats/' . $privateCharacter->Slug
            ],
            'Wiki Page' => [
                'link' => '/wiki/?n=Players.' . preg_replace("/[^A-Za-z0-9]/", '', $privateCharacter->CharacterName),
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
