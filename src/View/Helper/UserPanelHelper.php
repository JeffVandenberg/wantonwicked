<?php

namespace App\View\Helper;

use App\Model\Table\RequestsTable;
use Cake\Http\ServerRequest;
use Cake\ORM\TableRegistry;
use Cake\View\Helper\HtmlHelper;
use Exception;

/**
 * Created by PhpStorm.
 * User: jvandenberg
 * Date: 12/27/13
 * Time: 12:25 PM
 * @property HtmlHelper Html
 * @property ServerRequest request
 */
class UserPanelHelper extends AppHelper
{
    public $helpers = ['Html'];


    /**
     * @param string $page URL to refer to
     * @return string
     * @throws Exception
     */
    public function create(string $page): string
    {
        $panel = <<<EOQ
<span id="server-time"></span>
<a href="/forum/ucp.php?mode=login&redirect=$page">Login</a>
<a href="/forum/ucp.php?mode=register&redirect=$page">Register</a>
EOQ;

        if ((int)$this->request->getSession()->read('Auth.User.user_id') !== 1) {
            // show user name
            $userName = $this->request->getSession()->read('Auth.User.username');

            $requestsTable = TableRegistry::getTableLocator()->get('Requests');
            /** @var RequestsTable $requestsTable */
            $requestCount = $requestsTable->getCountOpenForUser($this->request->getSession()->read('Auth.User.user_id'));
            $stRequests = $requestsTable->getCountNewStRequests($this->request->getSession()->read('Auth.User.user_id'));

            $logout = $this->Html->link('Logout', $this->Html->Url->build('/') . 'forum/ucp.php?mode=logout&sid=' . $this->request->getSession()->read('Auth.User.session_id'));

            $panel = <<<EOQ
<span id="server-time"></span>&nbsp;
EOQ;
            if ($stRequests > 0) {
                $panel .= <<<EOQ
<a href="/requests/st-dashboard/" class="button-badge">
    <i class="fa fi-clipboard storyteller-action" title="ST Request Dashboard"></i>
    <span class="badge badge-primary warning" title="New Requests">$stRequests</span>
</a>&nbsp;
EOQ;
            }

            $panel .= <<<EOQ
<a href="/requests" class="button-badge">
    <i class="fa fi-clipboard" title="Your Requests"></i>
EOQ;
            if ($requestCount) {
                $panel .= '<span class="badge badge-primary warning" title="Open Requests">' . $requestCount . '</span>';
            }
            $panel .= '</a>&nbsp;';

            $panel .= <<<EOQ
<button class="button" type="button" data-toggle="user-dropdown">
    $userName
</button>
<div class="dropdown-pane" id="user-dropdown" data-dropdown>
    <div><a href="/forum/ucp.php">User Control Panel</a></div>
    <div>$logout</div>
</div>
EOQ;

        }

        return $panel;
    }
}
