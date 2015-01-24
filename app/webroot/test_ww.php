<?php

use classes\request\data\Request;
use classes\request\RequestMailer;

require_once 'cgi-bin/start_of_page.php';

$mailer = new RequestMailer();

$request = new Request();
$request->Title = 'My First Request';
if($mailer->SendMailToPlayer(
    'jeffvandenberg@gmail.com',
    //'jarden@outlook.com',
    //'marianne.esl@gmail.com',
    //'schwarzaile@yahoo.com',
    //'twistedpretty@gmail.com',
    'JeffV',
    'Approved',
    'You are good',
    $request
)) {
    echo "sent mail!";
}