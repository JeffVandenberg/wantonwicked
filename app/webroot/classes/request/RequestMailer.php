<?php
/**
 * Created by PhpStorm.
 * User: JeffVandenberg
 * Date: 1/24/2015
 * Time: 10:54 AM
 */

namespace classes\request;


use classes\request\data\Request;

class RequestMailer
{
    public function SendMailToPlayer($playerEmail, $fromUser, $status, $note, Request $request)
    {
        $mailer = new \PHPMailer();

        $mailer->addAddress($playerEmail);
        $mailer->setFrom('wantonwicked@gamingsandbox.com');
        $mailer->Subject = 'Request: ' . $request->Title . ' was ' . $status;
        $body            = <<<EOQ
<div id="background" style="background: #ffffff url(http://wantonwicked.gamingsandbox.com/img/main/bg1.jpg);">
    <img src="http://wantonwicked.gamingsandbox.com/img/main/ww_banner.png" />
    <div id="body" style="background-color: #dddddd;margin: 5px;padding: 5px;border: 1px solid #000000;border-radius: 5px;">
        <p>Your request has been updated! Here are the details.</p>
        <p>Your Request: {$request->Title}</p>
        <p>New Status: $status</p>
        <p>By Storyteller: $fromUser</p>
        <p><strong>Note</strong><br />
        $note
        </p>
        <p>
        <a href="http://wantonwicked.gamingsandbox.com/request.php?action=view&request_id={$request->Id}" target="_blank">
        View Request
        </a>
        </p>
    </div>
</div>
EOQ;
        $mailer->Body    = $body;
        $mailer->isHTML(true);

        if (!$mailer->send()) {
            throw new \Exception("Unable to send notice to player: " . $mailer->ErrorInfo);
        }

        return true;
    }
}