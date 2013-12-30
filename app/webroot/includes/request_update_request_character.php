<?php
/* @var array $userdata */
use classes\character\repository\CharacterRepository;
use classes\core\helpers\Request;
use classes\core\helpers\Response;
use classes\core\helpers\SessionHelper;
use classes\request\repository\RequestCharacterRepository;

$characterId = Request::GetValue('character_id', 0);
$requestCharacterId = Request::GetValue('request_character_id', 0);
$isApproved = Request::GetValue('is_approved', false);

$characterRepository = new CharacterRepository();
if(!$characterRepository->MayViewCharacter($characterId, $userdata['user_id'])) {
    SessionHelper::SetFlashMessage('Unable to Process Request');
    Response::Redirect('');
}
$requestCharacterRepository = new RequestCharacterRepository();
$requestCharacter = $requestCharacterRepository->FindById($requestCharacterId);

if($characterId != $requestCharacter['character_id']) {
    SessionHelper::SetFlashMessage('Unable to process request');
    Response::Redirect('request.php?action=list&character_id=' . $characterId);
}

$requestCharacterRepository->SetIsApproved($requestCharacterId, $isApproved);

SessionHelper::SetFlashMessage('Updated Request');
Response::Redirect('request.php?action=list&character_id=' . $characterId);