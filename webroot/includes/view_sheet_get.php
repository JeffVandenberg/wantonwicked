<?php
/* @var array $userdata */
use classes\character\helper\CharacterSheetHelper;
use classes\character\repository\CharacterRepository;
use classes\core\helpers\Request;
use classes\core\helpers\Response;

Response::preventCache();

$character_type = Request::getValue('character_type', 'Mortal');
$characterId    = Request::getValue('character_id', 0);
$type           = Request::getValue('type');

$characterRepository = new CharacterRepository();
$stats               = $characterRepository->FindById($characterId);

$characterSheetHelper = new CharacterSheetHelper();

switch ($type) {
    case 'st_view':
        echo $characterSheetHelper->MakeStView($stats, $userdata, $character_type);
        break;
    case 'view_own':
        echo $characterSheetHelper->MakeViewOwn($stats, $character_type);
        break;

    case 'new':
        echo $characterSheetHelper->MakeNewView($stats, $character_type);
        break;

    default:
        echo $characterSheetHelper->MakeLockedView($stats, $character_type);
        break;
}
Response::endRequest();