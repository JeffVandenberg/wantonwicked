<?php

use classes\character\repository\CharacterRepository;
use classes\core\helpers\Request;
use classes\core\helpers\Response;

$requestId = Request::getValue('request_id', 0);
$onlySanctioned = Request::getValue('only_sanctioned');
$term = Request::getValue('query');
$city = Request::getValue('city', 'portland');

$characterRepository = new CharacterRepository();
$characters = $characterRepository->AutocompleteSearch($term, $onlySanctioned, $city);

$list = array();
foreach($characters as $i => $character)
{
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
