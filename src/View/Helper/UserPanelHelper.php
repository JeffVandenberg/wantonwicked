<?php
namespace App\View\Helper;

use Cake\View\Helper\HtmlHelper;
use classes\request\repository\RequestRepository;

/**
 * Created by PhpStorm.
 * User: jvandenberg
 * Date: 12/27/13
 * Time: 12:25 PM
 * @property HtmlHelper Html
 */
class UserPanelHelper extends AppHelper
{
    public $helpers = ['Html'];


    public function Create($page)
    {
        $panel = <<<EOQ
<a href="/forum/ucp.php?mode=login&redirect=$page">Login</a>
<span id="server-time"></span><br>
<a href="/forum/ucp.php?mode=register&redirect=$page">Register</a>
EOQ;

        if ($this->request->session()->read('Auth.User.username') !== null) {
            // show user name
            $userName = $this->request->session()->read('Auth.User.username');

            $requestRepo = new RequestRepository();
            $requestCount = $requestRepo->countOpenForUser($this->request->session()->read('Auth.User.user_id'));

            $logout = $this->Html->link('Logout', $this->Html->Url->build('/') . 'forum/ucp.php?mode=logout&sid=' . $this->request->session()->read('Auth.User.session_id'));

            $panel = <<<EOQ
<span>$userName</span>
<span id="server-time"></span><br>
$logout <br />
<a href="/forum/ucp.php">User Control Panel</a>
EOQ;
            if ($requestCount) {
                $panel .= ' - ' . $this->Html->link(
                        'Open Requests (' . $requestCount . ')',
                        '/request.php');
            }

            $stRequests = $requestRepo->countNewStRequests($this->request->session()->read('Auth.User.user_id'));

            if ($stRequests) {
                $panel .= ' - ' . $this->Html->link(
                        'New Requests to Process (' . $stRequests . ')',
                        '/request.php?action=st_list');
            }
        }

        return $panel;
    }
} 
