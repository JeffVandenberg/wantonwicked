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
use App\Model\Table\CharactersTable;
use App\Model\Table\PlotsTable;
use App\Model\Table\ScenesTable;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use classes\request\repository\RequestRepository;
use function compact;

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

        $plots = TableRegistry::get('Plots');
        /* @var PlotsTable $plots */
        $plotList = $plots->listForHome();

        if($this->Auth->user('user_id') > 1) {
            $characters = TableRegistry::get('Characters');
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
