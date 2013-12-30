<?php

/**
 * Created by PhpStorm.
 * User: jvandenberg
 * Date: 12/27/13
 * Time: 12:25 PM
 * @property HtmlHelper Html
 */

class UserPanelHelper extends AppHelper
{
    public $helpers = array('Html');

    public function Create($page) {
        $panel = <<<EOQ
<a href="/forum/ucp.php?mode=login&redirect=$page">Login</a>
-
<a href="/forum/ucp.php?mode=register&redirect=$page">Register</a>
EOQ;

        if(AuthComponent::user('username') !== null) {
            // show user name
            $userName = AuthComponent::user('username');
            $logout = $this->Html->link('Logout', $this->Html->url('/').'forum/ucp.php?mode=logout&sid='.AuthComponent::user('session_id'));

            $panel = <<<EOQ
<span>$userName</span>
-
$logout
-
<a href="forum/ucp.php">User Control Panel</a>
EOQ;

        }

        return $panel;
    }
} 