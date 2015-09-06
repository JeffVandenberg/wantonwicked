<?php
/* @var array $userdata */
use classes\character\helper\CharacterSheetHelper;
use classes\character\repository\CharacterRepository;
use classes\core\helpers\Request;
use classes\core\helpers\Response;

Response::PreventCache();

$character_type = Request::GetValue('character_type', 'Mortal');
$characterId    = Request::GetValue('character_id', 0);
$type           = Request::GetValue('type');
$stats          = '';

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
Response::EndRequest();