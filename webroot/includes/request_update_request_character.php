<?php
/* @var array $userdata */
use classes\character\repository\CharacterRepository;
use classes\core\helpers\Request;
use classes\core\helpers\Response;
use classes\core\helpers\SessionHelper;
use classes\request\repository\RequestCharacterRepository;

$characterId = Request::getValue('character_id', 0);
$requestCharacterId = Request::getValue('request_character_id', 0);
$isApproved = Request::getValue('is_approved', false);

$characterRepository = new CharacterRepository();
if(!$characterRepository->MayViewCharacter($characterId, $userdata['user_id'])) {
    SessionHelper::SetFlashMessage('Unable to Process Request');
    Response::redirect('');
}
$requestCharacterRepository = new RequestCharacterRepository();
$requestCharacter = $requestCharacterRepository->findById($requestCharacterId);

if($characterId != $requestCharacter['character_id']) {
    SessionHelper::SetFlashMessage('Unable to process request');
    Response::redirect('request.php?action=list&character_id=' . $characterId);
}

$requestCharacterRepository->setIsApproved($requestCharacterId, $isApproved);

SessionHelper::SetFlashMessage('Updated Request');
Response::redirect('request.php?action=list&character_id=' . $characterId);
