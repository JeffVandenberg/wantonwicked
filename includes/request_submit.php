<?php
/* @var array $userdata */

use classes\core\helpers\Request;
use classes\core\helpers\Response;
use classes\core\helpers\SessionHelper;
use classes\request\repository\RequestRepository;

$requestId = Request::GetValue('request_id', 0);
$requestRepository = new RequestRepository();
if (!$requestRepository->MayViewRequest($requestId, $userdata['user_id'])) {
    include 'index_redirect.php';
    die();
}

$request = $requestRepository->FindById($requestId);

$requestRepository->Submit($request['id']);
SessionHelper::SetFlashMessage('Submitted Request: ' . $request['title'], 'request');

Response::Redirect('request.php?action=list&character_id=' . $request['character_id']);