<?php

use classes\character\data\Character;
use classes\character\repository\CharacterRepository;
use classes\core\helpers\Response;
use classes\core\helpers\SessionHelper;
use classes\core\repository\Database;
use classes\log\CharacterLog;
use classes\log\data\ActionType;

require_once 'cgi-bin/start_of_page.php';

$rolls = [
    1 => 0,
    2 => 0,
    3 => 0,
    4 => 0,
    5 => 0,
    6 => 0,
    7 => 0,
    8 => 0,
    9 => 0,
    10 => 0,
];
for($i = 0; $i < 100000; $i++) {
    $rolls[mt_rand(1,10)]++;
}

var_dump($rolls);
die();

SessionHelper::SetFlashMessage('Test message generated at: ' . date('Y-m-d H:i:s'));

Response::redirect('/');
$db = Database::getInstance();

$data = array(
    14394 => 2,
    14419 => 5,
    14390 => 5,
    14433 => 8,
    14414 => 11,
    14447 => 11,
    13607 => 23,
    14505 => 26,
    14512 => 29,
    14496 => 29,
    14541 => 35,
    14553 => 35,
    14527 => 35,
    14554 => 35,
    14557 => 35,
    14452 => 35,
    14550 => 35
);

$characterRepository = new CharacterRepository();
$db->startTransaction();
foreach ($data as $characterId => $xpToAward) {
    $character = $characterRepository->getById($characterId);
    /* @var Character $character */
    $character->CurrentExperience += $xpToAward;
    $character->TotalExperience += $xpToAward;
    $characterRepository->save($character);
    CharacterLog::LogAction($characterId, ActionType::XPModification, 'Year 3 award: ' . $xpToAward, 8);
    echo 'Awarded ' . $xpToAward . ' to ' . $character->CharacterName . '<br />';
}

$db->rollBackTransaction();