<?php
use classes\character\data\Character;
use classes\core\repository\RepositoryManager;

/* @var int $characterId */
$characterRepository = RepositoryManager::getRepository('classes\character\data\Character');
$privateCharacter = $characterRepository->getById($characterId);
/* @var Character $privateCharacter */

$characterSlug = $privateCharacter->Slug;
$characterName = $privateCharacter->CharacterName;
$charactermenu = require_once ROOT_PATH . '../lib/character_components.php';
