<?php
/* @var array $userdata */

use classes\core\helpers\FormHelper;
use classes\core\helpers\Request;
use classes\core\helpers\Response;
use classes\core\helpers\SessionHelper;
use classes\request\data\RequestStatus;
use classes\request\repository\GroupRepository;
use classes\request\repository\RequestRepository;
use classes\request\repository\RequestTypeRepository;

$requestId = Request::GetValue('request_id', 0);
$requestRepository = new RequestRepository();
if (!$userdata['is_admin'] && !$requestRepository->MayViewRequest($requestId, $userdata['user_id'])) {
    include 'index_redirect.php';
    die();
}

$request = $requestRepository->GetById($requestId);
/* @var \classes\request\data\Request $request */

if($requestRepository->Delete($requestId)) {
    SessionHelper::SetFlashMessage('Request ' . $request->Title .' has been succesfully deleted.');
}
else {
    SessionHelper::SetFlashMessage('Error deleting Request.');
}
Response::Redirect('request.php?action=list&character_id='.$request->CharacterId);