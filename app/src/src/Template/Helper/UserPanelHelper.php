<?php

/**
 * Created by PhpStorm.
 * User: jvandenberg
 * Date: 12/27/13
 * Time: 12:25 PM
 * @property HtmlHelper Html
 */namespace app\Template\Helper;

use App\View\Helper\AppHelper;


class UserPanelHelper extends AppHelper
{
    public $helpers = array('Html');

    public function Create($page) {
        $panel = <<<EOQ
<a href="/forum/ucp.php?mode=login&redirect=$page">Login</a>
<span id="server-time"></span><br>
<a href="/forum/ucp.php?mode=register&redirect=$page">Register</a>
EOQ;

        if(AuthComponent::user('username') !== null) {
            // show user name
            $userName = AuthComponent::user('username');
            use App\Model\Request;
            $request = new Request();

            $requestCount = $request->find(
                'count',
                array(
                    'conditions' => array(
                        'Request.request_type_id != 4',
                        'Request.created_by_id' => AuthComponent::user('user_id'),
                        'Request.request_status_id IN (1,2,3,4,5,6)'
                    ),
                    'contain' => false
                )
            );
            $logout = $this->Html->link('Logout', $this->Html->url('/').'forum/ucp.php?mode=logout&sid='.AuthComponent::user('session_id'));

            $panel = <<<EOQ
<span>$userName</span>
<span id="server-time"></span><br>
$logout <br />
<a href="/forum/ucp.php">User Control Panel</a>
EOQ;
            if($requestCount) {
                $panel .= ' - ' . $this->Html->link(
                        'Open Requests (' . $requestCount . ')',
                        '/request.php');
            }

            $stRequests = $request->findNewStRequests(AuthComponent::user('user_id'));
            if($stRequests) {
                $panel .= ' - ' . $this->Html->link(
                        'New Requests to Process ('.$stRequests.')',
                    '/request.php?action=st_list');
            }
        }

        return $panel;
    }
} 
