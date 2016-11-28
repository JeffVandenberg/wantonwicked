<?php

use classes\character\repository\CharacterRepository;
use classes\core\helpers\MenuHelper;
use classes\core\helpers\UserdataHelper;

/* @var array $userdata */
$menuComponents = include_once(ROOT_PATH . '../Lib/menu_components.php');
$mainMenu = $menuComponents['base'];

if ($userdata['user_id'] != ANONYMOUS) {
    $mainMenu = array_merge_recursive($mainMenu, $menuComponents['player']);

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
        $mainMenu['Utilities']['submenu']['Player Utilities']['submenu'][$character['character_name']] = $characterMenu;
    }
}


if (UserdataHelper::IsSt($userdata)) {
    $mainMenu = array_merge_recursive($mainMenu, $menuComponents['staff']);
}

$menu_bar = MenuHelper::GenerateMenu($mainMenu);
return $mainMenu;
