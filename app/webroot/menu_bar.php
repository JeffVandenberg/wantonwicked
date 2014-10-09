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
                        'link' => '/wiki/index.php?n=GameRef.CodeOfConduct',
                    ),
                    'Chat Help'         => array(
                        'link' => '/wiki/index.php?n=GameRef.PoliciesandPractices',
                    ),
                    'Request System'    => array(
                        'link' => '/wiki/index.php?n=GameRef.Disclaimer',
                    ),
                    'Experience Guide'  => array(
                        'link' => '/wiki/index.php?n=GameRef.Disclaimer',
                    ),
                    'Sanctioning Guide' => array(
                        'link' => '/wiki/index.php?n=GameRef.Disclaimer',
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
                    'Cast List'           => array(
                        'link' => '/characters/cast/vampire'
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
                    'Cast List'            => array(
                        'link' => '/characters/cast/werewolf'
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
        'link' => '/chat.php'
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
?>
<div id="nav">
    <ul class='menu' id='main-menu'>
        <li><a href="/" target="_top"><span>Home</span></a></li>
        <li><a href="/forum/index.php"><span>Forums</span></a></li>
        <li><a href="#"><span>Site Tools</span></a>
            <ul>
                <li><a href="support.php"><span>Site Supporter</span></a>
                    <ul>
                        <li><a href="/support.php?action=manage"><span>Manage Support</span></a></li>
                    </ul>
                </li>
                <li><a href="/chat.php"><span>Character List</span></a>
                    <ul>
                        <li>
                            <a href="/character.php?action=interface&character_id=11983"><span>Professor Javier Mendoza</span></a>
                            <ul>
                                <li><a href="/chat/?character_id=11983"><span>Login</span></a></li>
                                <li><a href="/request.php?action=list&character_id=11983"><span>Requests</span></a></li>
                                <li><a href="/bluebook.php?action=list&character_id=11983"><span>Bluebook</span></a>
                                </li>
                                <li>
                                    <a href="/view_sheet.php?action=view_own_xp&character_id=11983"><span>Sheet</span></a>
                                </li>
                            </ul>
                        </li>
                        <li><a href="/character.php?action=interface&character_id=12016"><span>Test Mekhet</span></a>
                            <ul>
                                <li><a href="/chat/?character_id=12016"><span>Login</span></a></li>
                                <li><a href="/request.php?action=list&character_id=12016"><span>Requests</span></a></li>
                                <li><a href="/bluebook.php?action=list&character_id=12016"><span>Bluebook</span></a>
                                </li>
                                <li>
                                    <a href="/view_sheet.php?action=view_own_xp&character_id=12016"><span>Sheet</span></a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li><a href="/storyteller_index.php"><span>ST Tools</span></a>
                    <ul>
                        <li><a href="/view_sheet.php?action=st_view_xp"><span>Character Lookup</span></a></li>
                        <li><a href="/request.php?action=st_list"><span>Request Dashboard</span></a></li>
                        <li><a href="/chat/?st_login" target="_blank"><span>Chat Login</span></a></li>
                    </ul>
                </li>
            </ul>
        </li>
        <li><a href="#"><span>Help</span></a>
            <ul>
                <li><a href="/staff"><span>Meet the Team</span></a></li>
                <li><a href="#"><span>Site Policy</span></a>
                    <ul>
                        <li><a href="/wiki/index.php?n=GameRef.CodeOfConduct"><span>Code of Conduct</span></a></li>
                        <li><a href="/wiki/index.php?n=GameRef.PoliciesandPractices"><span>Other Policies</span></a>
                        </li>
                        <li><a href="/wiki/index.php?n=GameRef.Disclaimer"><span>Disclaimer</span></a></li>
                        <li><a href="/wiki/index.php?n=GameRef.BookPolicy"><span>Book Policy</span></a></li>
                    </ul>
                </li>
                <li><a href="#"><span>Help Guides</span></a>
                    <ul>
                        <li><a href="/wiki/index.php?n=GameRef.CodeOfConduct"><span>New Player Help</span></a></li>
                        <li><a href="/wiki/index.php?n=GameRef.PoliciesandPractices"><span>Chat Help</span></a></li>
                        <li><a href="/wiki/index.php?n=GameRef.Disclaimer"><span>Request System</span></a></li>
                        <li><a href="/wiki/index.php?n=GameRef.Disclaimer"><span>Experience Guide</span></a></li>
                        <li><a href="/wiki/index.php?n=GameRef.Disclaimer"><span>Sanctioning Guide</span></a></li>
                    </ul>
                </li>
                <li><a href="/wiki/index.php?n=GameRef.HouseRules"><span>House Rules</span></a></li>
                <li><a href="/wiki/index.php?n=GameRef.CrossoverErrata"><span>Crossover Rules</span></a></li>
                <li><a href="/wiki/index.php?n=GameRef.CharacterCreation"><span>Character Creation</span></a></li>
            </ul>
        </li>
        <li><a href="#"><span>The City</span></a>
            <ul>
                <li><a href="/wiki/index.php?n=City.City"><span>Setting Introduction</span></a></li>
                <li><a href="/ww4map/map.html"><span>City Map</span></a></li>
                <li><a href="/wiki/index.php?n=City.Districts"><span>City Districts</span></a></li>
                <li><a href="/characters/cast/"><span>Cast List</span></a></li>
                <li><a href="#"><span>Crossover Venues</span></a>
                    <ul>
                        <li><a href="/wiki/index.php?n=Shadow.Shadow"><span>The Shadow</span></a></li>
                        <li><a href="/wiki/index.php?n=Whitefield.Whitefield"><span>Whitefield University</span></a>
                        </li>
                    </ul>
                </li>
            </ul>
        </li>
        <li><a href="#"><span>The Venues</span></a>
            <ul>
                <li><a href="#"><span>Changeling</span></a>
                    <ul>
                        <li><a href="/wiki/index.php?n=Changeling.Changeling"><span>Overview</span></a></li>
                        <li><a href="/wiki/index.php?n=Changeling.Society"><span>Society</span></a></li>
                        <li><a href="/wiki/index.php?n=Changeling.History"><span>History</span></a></li>
                        <li><a href="/wiki/index.php?n=Changeling.Locations"><span>Locations</span></a></li>
                        <li><a href="/wiki/index.php?n=Changeling.HouseRules"><span>Changeling House Rules</span></a>
                        </li>
                        <li><a href="/wiki/index.php?n=Changeling.PlayerGuide"><span>Character Creation</span></a></li>
                        <li><a href="/forum/viewforum.php?f=615"><span>Changeling Forums</span></a></li>
                        <li><a href="/characters/cast/changeling"><span>Cast List</span></a></li>
                    </ul>
                </li>
                <li><a href="#"><span>Geist</span></a>
                    <ul>
                        <li><a href="/wiki/index.php?n=Geist.Geist"><span>Overview</span></a></li>
                        <li><a href="/wiki/index.php?n=Geist.Society"><span>Society</span></a></li>
                        <li><a href="/wiki/index.php?n=Geist.History"><span>History</span></a></li>
                        <li><a href="/wiki/index.php?n=Geist.Locations"><span>Locations</span></a></li>
                        <li><a href="/wiki/index.php?n=Geist.HouseRules"><span>Geist House Rules</span></a></li>
                        <li><a href="/wiki/index.php?n=Geist.PlayerGuide"><span>Character Creation</span></a></li>
                        <li><a href="/forum/viewforum.php?f=716"><span>Geist Forums</span></a></li>
                        <li><a href="/characters/cast/geist"><span>Cast List</span></a></li>
                    </ul>
                </li>
                <li><a href="#"><span>Mage</span></a>
                    <ul>
                        <li><a href="/wiki/index.php?n=Mage.Mage"><span>Overview</span></a></li>
                        <li><a href="/wiki/index.php?n=Mage.Society"><span>Society</span></a></li>
                        <li><a href="/wiki/index.php?n=Mage.History"><span>History</span></a></li>
                        <li><a href="/wiki/index.php?n=Mage.Locations"><span>Locations</span></a></li>
                        <li><a href="/wiki/index.php?n=Mage.HouseRules"><span>Mage House Rules</span></a></li>
                        <li><a href="/wiki/index.php?n=Mage.PlayerGuide"><span>Character Creation</span></a></li>
                        <li><a href="/forum/viewforum.php?f=624"><span>Mage Forums</span></a></li>
                        <li><a href="/characters/cast/mage"><span>Cast List</span></a></li>
                    </ul>
                </li>
                <li><a href="#"><span>Mortal</span></a>
                    <ul>
                        <li><a href="/wiki/index.php?n=Mortal.Mortal"><span>Overview</span></a></li>
                        <li><a href="/wiki/index.php?n=Mortal.Society"><span>Society</span></a></li>
                        <li><a href="/wiki/index.php?n=Mortal.History"><span>History</span></a></li>
                        <li><a href="/wiki/index.php?n=Mortal.Locations"><span>Locations</span></a></li>
                        <li><a href="/wiki/index.php?n=Mortal.HouseRules"><span>Mortal House Rules</span></a></li>
                        <li><a href="/wiki/index.php?n=Mortal.PlayerGuide"><span>Character Creation</span></a></li>
                        <li><a href="/forum/viewforum.php?f=625"><span>Mortal Forums</span></a></li>
                        <li><a href="/characters/cast/mortal"><span>Cast List</span></a></li>
                    </ul>
                </li>
                <li><a href="#"><span>Vampire</span></a>
                    <ul>
                        <li><a href="/wiki/index.php?n=Vampire.Vampire"><span>Overview</span></a></li>
                        <li><a href="/wiki/index.php?n=Vampire.Society"><span>Society</span></a></li>
                        <li><a href="/wiki/index.php?n=Vampire.History"><span>History</span></a></li>
                        <li><a href="/wiki/index.php?n=Vampire.Locations"><span>Locations</span></a></li>
                        <li><a href="/wiki/index.php?n=Vampire.HouseRules"><span>Vampire House Rules</span></a></li>
                        <li><a href="/wiki/index.php?n=Vampire.PlayerGuide"><span>Character Creation</span></a></li>
                        <li><a href="/forum/viewforum.php?f=665"><span>Vampire Forums</span></a></li>
                        <li><a href="/characters/cast/vampire"><span>Cast List</span></a></li>
                    </ul>
                </li>
                <li><a href="#"><span>Werewolf</span></a>
                    <ul>
                        <li><a href="/wiki/index.php?n=Werewolf.Werewolf"><span>Overview</span></a></li>
                        <li><a href="/wiki/index.php?n=Werewolf.Society"><span>Society</span></a></li>
                        <li><a href="/wiki/index.php?n=Werewolf.History"><span>History</span></a></li>
                        <li><a href="/wiki/index.php?n=Werewolf.Locations"><span>Locations</span></a></li>
                        <li><a href="/wiki/index.php?n=Werewolf.HouseRules"><span>Werewolf House Rules</span></a></li>
                        <li><a href="/wiki/index.php?n=Werewolf.PlayerGuide"><span>Character Creation</span></a></li>
                        <li><a href="/forum/viewforum.php?f=626"><span>Werewolf Forums</span></a></li>
                        <li><a href="/characters/cast/werewolf"><span>Cast List</span></a></li>
                    </ul>
                </li>
            </ul>
        </li>
        <li><a href="#"><span>Sidegames</span></a>
            <ul>
                <li><a href="/wiki/index.php?n=Sidegames.Sidegames"><span>Sidegame List</span></a></li>
                <li><a href="/forum/viewforum.php?f=763"><span>Sidegame Forums</span></a></li>
            </ul>
        </li>
    </ul>
</div>