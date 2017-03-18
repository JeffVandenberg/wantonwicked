<?php

/**
 * Created by PhpStorm.
 * User: jvandenberg
 * Date: 12/27/13
 * Time: 11:56 AM
 */
namespace App\Controller;

use App\Controller\Component\ConfigComponent;
use App\Model\LegacyUser;
use Cake\Event\Event;

/**
 * @property ConfigComponent Config
 */
class HomeController extends AppController
{
    public $components = array(
        'Config'
    );

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow();
    }

    public function home()
    {
        $this->set('content', $this->Config->Read('FRONT_PAGE'));
    }

    public function staff()
    {
        $legacyUser = new LegacyUser();

        $staff = $legacyUser->listUsersWithGroups();
        $this->set(compact('staff'));
    }

    function gsNews()
    {
        $news = $this->Config->Read('gs_frontpage');
        $this->viewBuilder()->setLayout('blank');
        $this->set(compact('news'));
    }
}
