<?php
/* @var array $userdata */

use classes\core\helpers\Request;
use classes\core\helpers\Response;
use classes\core\helpers\SessionHelper;
use classes\request\data\RequestStatus;
use classes\request\repository\RequestRepository;

$requestId = Request::GetValue('request_id', 0);
$requestRepository = new RequestRepository();
if (!$requestRepository->MayViewRequest($requestId, $userdata['user_id'])) {
    Response::Redirect('/');
}

$request = $requestRepository->GetById($requestId);
$requestRepository->UpdateStatus($requestId, RequestStatus::Closed, $userdata['user_id']);
SessionHelper::SetFlashMessage('Closed Request: ' . $request->Title);

Response::Redirect('request.php?action=list&character_id=' . $request->CharacterId);