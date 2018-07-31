<?php

namespace App\Controller;

/**
 * Created by PhpStorm.
 * User: jvandenberg
 * Date: 12/27/13
 * Time: 11:56 AM
 */

use App\Controller\Component\ConfigComponent;
use App\Controller\Component\PermissionsComponent;
use App\Model\Entity\RequestStatus;
use App\Model\Entity\RequestType;
use App\Model\Table\CharactersTable;
use App\Model\Table\PlotsTable;
use App\Model\Table\RequestsTable;
use App\Model\Table\ScenesTable;
use Cake\Cache\Cache;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use classes\request\repository\RequestRepository;
use function compact;
use const E_USER_DEPRECATED;
use function error_reporting;
use GuzzleHttp\Client;

/**
 * @property ConfigComponent Config
 * @property PermissionsComponent Permissions
 */
class HomeController extends AppController
{
    public $components = array(
        'Config'
    );

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow([
            'home',
            'staff',
            'gsNews'
        ]);
    }

    public function home()
    {
        // get news content
        $this->set(['content' => $this->Config->read('FRONT_PAGE')]);

        // get scene information
        $scenes = TableRegistry::getTableLocator()->get('Scenes');
        /* @var ScenesTable $scenes */
        $sceneList = $scenes->listForHome();

        // get request information
        $requests = TableRegistry::getTableLocator()->get('Requests');
        /* @var RequestsTable $requests */
        $playerRequests = $requests->listForHome($this->Auth->user('user_id'));

        $plots = TableRegistry::getTableLocator()->get('Plots');
        /* @var PlotsTable $plots */
        $plotList = $plots->listForHome();

        if ($this->Auth->user('user_id') > 1) {
            $characters = TableRegistry::getTableLocator()->get('Characters');
            /* @var CharactersTable $characters */
            $characterList = $characters->listForHome($this->Auth->user('user_id'));
            $this->set(compact('characterList'));
        }

        // set info for home
        $this->set('isPlotManager', $this->Permissions->isPlotManager());
        $this->set(compact('sceneList', 'playerRequests', 'plotList'));
    }

    public function staff()
    {
        $users = TableRegistry::getTableLocator()->get('Users');
        $staff = $users->listUsersWithGroups();
        $this->set(compact('staff'));
    }

    public function gsNews()
    {
        $news = $this->Config->read('gs_frontpage');
        $this->viewBuilder()->setLayout('blank');
        $this->set(compact('news'));
    }

    public function clearCache()
    {
        Cache::clearAll();
        $this->Flash->set('Application Cache cleared');
        $this->redirect('/storyteller_index.php');
    }

    public function isAuthorized($user)
    {
        switch (strtolower($this->getRequest()->getParam('action'))) {
            case 'clearcache':
                return $this->Permissions->isAdmin();
        }

        return false;
    }
}
