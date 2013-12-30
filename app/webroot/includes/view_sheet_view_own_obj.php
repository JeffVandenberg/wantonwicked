<?php

use classes\character\data\Character;
use classes\character\helper\CharacterSheetHelper;
use classes\core\helpers\Request;
use classes\core\repository\RepositoryManager;

$characterId = Request::GetValue('character_id', 0);

$characterRepository = RepositoryManager::GetRepository('classes\character\data\Character');
$character = $characterRepository->GetById($characterId);
/* @var Character $character */

$page_title = 'View ' . $character->CharacterName;
$contentHeader = $page_title;

$header = CharacterSheetHelper::MakeHeaderView($character);
$vitals = CharacterSheetHelper::MakeVitalsViewOwn($character);
$attributes = CharacterSheetHelper::MakeAttributesView($character);
$skills = CharacterSheetHelper::MakeSkillsView($character);

$page_content = <<<EOQ
$header
$vitals
$attributes
$skills
EOQ;
