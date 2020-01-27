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
use Cake\Http\Response;
use Cake\ORM\TableRegistry;
use classes\request\repository\RequestRepository;
use Exception;
use function compact;
use const E_USER_DEPRECATED;
use function error_reporting;
use GuzzleHttp\Client;

/**
 * @property ConfigComponent $Config
 * @property PermissionsComponent Permissions
 */
class HomeController extends AppController
{
    /**
     * @param Event $event Event to handle
     * @return Response|void|null
     */
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow([
            'home',
            'staff',
            'gsNews'
        ]);
    }

    /**
     * @return void
     * @throws Exception
     */
    public function home()
    {
        // get news content
        $city = $this->Config->readGlobal('city');

        $this->set(['content' => $this->Config->read('FRONT_PAGE')]);

        // set info for home
        $this->set('isPlotManager', $this->Permissions->isPlotManager());
    }

    /**
     * @return void
     */
    public function staff()
    {
        $users = TableRegistry::getTableLocator()->get('Users');
        $staff = $users->listUsersWithGroups();
        $this->set(compact('staff'));
    }

    /**
     * @return void
     */
    public function gsNews()
    {
        $news = $this->Config->read('gs_frontpage');
        $this->viewBuilder()->setLayout('blank');
        $this->set(compact('news'));
    }

    /**
     * @return void
     */
    public function clearCache()
    {
        Cache::clearAll();
        $this->Flash->set('Application Cache cleared');
        $this->redirect('/storyteller_index.php');
    }

    /**
     * @param array $user User Data
     * @return bool
     */
    public function isAuthorized($user)
    {
        switch (strtolower($this->getRequest()->getParam('action'))) {
            case 'clearcache':
                return $this->Permissions->isAdmin();
        }

        return false;
    }
}
