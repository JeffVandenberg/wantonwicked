<?php

/**
 * Created by PhpStorm.
 * User: jvandenberg
 * Date: 12/27/13
 * Time: 11:56 AM
 * @property ConfigComponent Config
 */namespace app\Controller;

use App\Controller\AppController;


class HomeController extends AppController
{
    public $components = array(
        'Config'
    );

    public function beforeFilter(Event $event) {
        parent::beforeFilter($event);
        $this->Auth->allow();
    }

    public function home() {
        $this->set('content', $this->Config->Read('FRONT_PAGE'));
    }

    public function staff() {
        use App\Model\LegacyUser;
        $legacyUser = new LegacyUser();

        $staff = $legacyUser->listUsersWithGroups();
        $this->set(compact('staff'));
    }

    function gsNews() {
        $news = $this->Config->Read('gs_frontpage');
        $this->layout = 'blank';
        $this->set(compact('news'));
    }
}
