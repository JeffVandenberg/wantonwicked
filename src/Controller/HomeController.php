<?php

use App\Model\Table\ScenesTable;

/**
 * Created by PhpStorm.
 * User: jvandenberg
 * Date: 12/27/13
 * Time: 11:56 AM
 */
namespace App\Controller;

use App\Controller\Component\ConfigComponent;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use classes\request\repository\RequestRepository;

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
        // get scene information
        $scenes = TableRegistry::get('Scenes');
        /* @var ScenesTable $scenes */
        $sceneList = $scenes->listForHome();

        // get request information
        $requestRepo = new RequestRepository();
        $playerRequests = $requestRepo->ListByUserId($this->Auth->user('user_id'), 1, 5, 'updated_on desc', []);

        // set info for home
        $this->set('content', $this->Config->Read('FRONT_PAGE'));
        $this->set('plots', $this->Config->Read('CURRENT_PLOTS'));
        $this->set(compact('sceneList', 'playerRequests'));
    }

    public function staff()
    {
        $users = TableRegistry::get('Users');

        $staff = $users->listUsersWithGroups();
        $this->set(compact('staff'));
    }

    function gsNews()
    {
        $news = $this->Config->Read('gs_frontpage');
        $this->viewBuilder()->setLayout('blank');
        $this->set(compact('news'));
    }
}
