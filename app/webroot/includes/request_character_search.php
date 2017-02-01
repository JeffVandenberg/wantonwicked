<?php

use classes\core\helpers\Request;
use classes\core\helpers\Response;
use classes\request\repository\RequestRepository;

$requestId = Request::getValue('request_id', 0);
$onlySanctioned = Request::getValue('only_sanctioned', true);
$term = Request::getValue('query');

$requestRepository = new RequestRepository();
$characters = $requestRepository->SearchCharactersForRequest($onlySanctioned, $term);

$list = [];
foreach ($characters as $i => $character) {
    $list[] = [
        'value' => $character['character_name'],
        'data' => $character['id']
    ];
}

if(count($list) == 0)
{
    $list[] = [
        'value' => 'No Characters',
        'data' => -1
    ];
}

Response::preventCache();

$data = [
    'query' => $term,
    'suggestions' => $list
];

Response::sendJson($data);
