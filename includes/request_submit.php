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
    include 'index_redirect.php';
    die();
}

$requestRepository->UpdateStatus($requestId, RequestStatus::Submitted, $userdata['user_id']);
$request = $requestRepository->GetById($requestId);
/* @var \classes\request\data\Request $request */

SessionHelper::SetFlashMessage('Submitted Request: ' . $request->Title, 'request');

Response::Redirect('request.php?action=list&character_id=' . $request->CharacterId);