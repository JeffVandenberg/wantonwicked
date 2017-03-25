<?php
/* @var array $userdata */

use classes\core\helpers\Request;
use classes\core\helpers\Response;
use classes\core\helpers\SessionHelper;
use classes\request\data\RequestCharacter;
use classes\request\data\RequestStatus;
use classes\request\repository\RequestCharacterRepository;
use classes\request\repository\RequestRepository;
use classes\request\RequestMailer;

$requestId = Request::getValue('request_id', 0);
$requestRepository = new RequestRepository();
$requestCharacterRepository = new RequestCharacterRepository();

if (!$requestRepository->MayEditRequest($requestId, $userdata['user_id'])) {
    Response::redirect('/');
}

$request = $requestRepository->getById($requestId);
/* @var \classes\request\data\Request $request */

$requestRepository->UpdateStatus($requestId, RequestStatus::Submitted, $userdata['user_id']);
$mailer = new RequestMailer();
$mailer->newRequestSubmission($request);

SessionHelper::SetFlashMessage('Submitted Request: ' . $request->Title);

$primaryCharacter = $requestCharacterRepository->FindByRequestIdAndIsPrimary($requestId, true);
/* @var RequestCharacter $primaryCharacter */

if($primaryCharacter->Id && $primaryCharacter->Character->UserId == $userdata['user_id']) {
    Response::redirect('request.php?action=list&character_id=' . $primaryCharacter->CharacterId);
}
else {
    Response::redirect('request.php');
}