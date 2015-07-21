<?php
/* @var array $userdata */

use classes\core\helpers\FormHelper;
use classes\core\helpers\Request;
use classes\core\helpers\Response;
use classes\core\helpers\SessionHelper;
use classes\request\data\RequestCharacter;
use classes\request\data\RequestStatus;
use classes\request\repository\GroupRepository;
use classes\request\repository\RequestCharacterRepository;
use classes\request\repository\RequestRepository;
use classes\request\repository\RequestTypeRepository;

$requestId = Request::GetValue('request_id', 0);
$requestRepository = new RequestRepository();
$requestCharacterRepository = new RequestCharacterRepository();

if (!$requestRepository->MayEditRequest($requestId, $userdata['user_id'])) {
    Response::Redirect('/');
}

// load request
$request = $requestRepository->GetById($requestId);
/* @var \classes\request\data\Request $request */

$primaryCharacter = $requestCharacterRepository->FindByRequestIdAndIsPrimary($requestId, true);
/* @var RequestCharacter $primaryCharacter */

if($request->RequestStatusId == 1) {
    if($requestRepository->Delete($requestId)) {
        SessionHelper::SetFlashMessage('Request ' . $request->Title .' has been succesfully deleted.');
    }
    else {
        SessionHelper::SetFlashMessage('Error deleting Request.');
    }

}
else {
    SessionHelper::SetFlashMessage('Can not delete a request that has been submitted.');
}

// redirect to appropriate place
if($primaryCharacter->Id && $primaryCharacter->Character->UserId == $userdata['user_id']) {
    Response::Redirect('request.php?action=list&character_id=' . $primaryCharacter->CharacterId);
}
else {
    Response::Redirect('request.php');
}
