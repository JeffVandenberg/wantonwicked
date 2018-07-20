<?php

use classes\character\repository\CharacterRepository;
use classes\core\helpers\MenuHelper;
use classes\core\helpers\UserdataHelper;

/* @var array $userdata */
$menuComponents = include_once(ROOT_PATH . '../lib/menu_components.php');
$mainMenu = $menuComponents['base'];

if ($userdata['user_id'] != ANONYMOUS) {
    $mainMenu = array_merge_recursive($mainMenu, $menuComponents['player']);

    $characterRepository = new CharacterRepository();
    $sanctionedCharacters = $characterRepository->ListSanctionedCharactersByPlayerId($userdata['user_id']);
    foreach ($sanctionedCharacters as $character) {
        $characterMenu = [
            'link' => '/character.php?action=interface&character_id=' . $character['id'],
            'submenu' => [
                'Login' => [
                    'link' => '/chat/?character_id=' . $character['id']
                ],
                'Requests' => [
                    'link' => '/requests/character/' . $character['id']
                ],
                'Bluebook' => [
                    'link' => '/bluebooks/character/' . $character['id']
                ],
                'Sheet' => [
                    'link' => '/characters/viewOwn/' . $character['slug']
                ]
            ]
        ];
        $mainMenu['Utilities']['submenu']['Characters']['submenu'][$character['character_name']] = $characterMenu;
    }
}


if (UserdataHelper::IsSt($userdata)) {
    $mainMenu = array_merge_recursive($mainMenu, $menuComponents['staff']);
}

foreach($mainMenu as $header => $menuOptions) {
    if(!isset($menuOptions['link']) && (empty($menuOptions) || empty($menuOptions['submenu']))) {
        unset($mainMenu[$header]);
    }
}
$menu_bar = MenuHelper::generateMenu($mainMenu);
return $mainMenu;
