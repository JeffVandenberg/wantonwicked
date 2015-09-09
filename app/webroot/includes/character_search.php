<?php

use classes\character\repository\CharacterRepository;
use classes\core\helpers\Request;
use classes\core\helpers\Response;

$requestId = Request::getValue('request_id', 0);
$onlySanctioned = Request::getValue('only_sanctioned');
$term = Request::getValue('term');

$characterRepository = new CharacterRepository();
$characters = $characterRepository->AutocompleteSearch($term, $onlySanctioned);

$list = array();
foreach($characters as $i => $character)
{
    $list[$i]['label'] = $character['character_name'];
    $list[$i]['id'] = $character['id'];
}

if(count($list) == 0)
{
    $list[0]['label'] = 'No characters';
    $list[0]['id'] = -1;
}

Response::preventCache();
Response::sendJson($list);