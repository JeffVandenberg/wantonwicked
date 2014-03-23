<?php
use classes\character\data\Character;
use classes\core\repository\RepositoryManager;

/* @var int $characterId */
$characterRepository = RepositoryManager::GetRepository('classes\character\data\Character');
$privateCharacter = $characterRepository->GetById($characterId);
/* @var Character $privateCharacter */

$characterMenu = array(
    'Chat' => array(
        'link' => "/chat/?character_id=$characterId",
        'target' => '_blank',
        'submenu' => array(
            'Login' => array(
                'link' => "/chat/?character_id=$characterId",
                'target' => '_blank'
            ),
            'Interface' => array(
                'link' => "/character.php?action=interface&character_id=$characterId"
            )
        )
    ),
    'Character' => array(
        'link' => '#',
        'submenu' => array(
            'Sheet' => array(
                'link' => "view_sheet.php?action=view_own_xp&character_id=$characterId"
            ),
            'Wiki Page' => array(
                'link' => '/wiki/?n=Players.' . preg_replace("/[^A-Za-z0-9]/", '', $privateCharacter->CharacterName),
                'target' => '_blank'
            ),
            'Character Log' => array(
                'link' => "/character.php?action=log&character_id=$characterId"
            ),
        )
    ),
    'Tools' => array(
        'link' => '#',
        'submenu' => array(
            'Dice Roller' => array(
                'link' => "/dieroller.php?action=character&character_id=$characterId"
            ),
            'Requests' => array(
                'link' => "/request.php?action=list&character_id=$characterId",
                'submenu' => array(
                    'New' => array(
                        'link' => "/request.php?action=create&character_id=$characterId"
                    )
                )
            ),
            'Bluebook' => array(
                'link' => "/bluebook.php?action=list&character_id=$characterId",
                'submenu' => array(
                    'New' => array(
                        'link' => "/bluebook.php?action=create&character_id=$characterId"
                    )
                )
            ),
            'Favors' => array(
                'link' => "/favors.php?action=list&character_id=$characterId"
            ),
            'Notes' => array(
                'link' => "/notes.php?action=character&character_id=$characterId"
            )
        )
    ),
    /*'Reference' => array(
        'link' => '#',
        'submenu' => array(
            'Forum' => array(
                'link' => '/forum',
                'target' => '_blank'
            ),
            'Wiki' => array(
                'link' => '/wiki',
                'target' => '_blank'
            ),
            'Game Ref' => array(
                'link' => '/wiki/index.php?n=GameRef.GameRef',
                'target' => '_blank'
            ),
            $privateCharacter->CharacterType . ' Wiki' => array(
                'link' => '/wiki/?n=Players.' . $privateCharacter->CharacterType
            )
        )
    )*/
);