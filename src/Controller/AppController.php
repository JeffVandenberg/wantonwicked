<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use App\Controller\Component\ConfigComponent;
use App\Controller\Component\MenuComponent;
use App\Controller\Component\PermissionsComponent;
use Cake\Controller\Controller;
use Cake\Event\Event;
use Cake\Http\Response;
use Exception;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @property MenuComponent $Menu
 * @package        app.Controller
 * @link        http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 * @property PermissionsComponent $Permissions
 * @property ConfigComponent Config
 */
class AppController extends Controller
{
    /**
     * @var array
     */
    public $components = [
        'RequestHandler',
        'Paginator',
        'Auth' => [
            'authenticate' => ['Phpbb'],
            'authorize' => ['Controller'],
            'loginAction' => '/forum/ucp.php?mode=login',
            'unauthorizedRedirect' => '/forum/ucp.php?mode=login',
        ],
        'Permissions',
        'Menu',
        'RequestHandler',
        'Flash',
        'Config',
    ];

    /**
     * @var array
     */
    public $helpers = [
        'Html',
        'Form',
        'MainMenu',
        'Shrink.Shrink' => [
            'debugLevel' => 1,
        ],
    ];

    /**
     * @param Event $event Event to handle
     * @return Response|void|null
     */
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);

        global $userdata;
        if ((int)$userdata['user_id'] !== (int)$this->Auth->user('user_id')) {
            $this->Auth->logout();
            $this->Auth->setUser($userdata);
        }
        $this->Menu->initializeMenu();
        $this->Auth->deny();
    }

    /**
     * @param Event $event Event to handle
     * @return Response|void|null
     * @throws Exception
     */
    public function beforeRender(Event $event)
    {
        parent::beforeRender($event);
        $this->viewBuilder()->setLayout(($this->getRequest()->is('ajax')) ? 'ajax' : 'default');
        $this->set('menu', $this->Menu->GetMenu());
        $this->set('serverTime', (microtime(true) + date('Z')) * 1000);
        $this->set('buildNumber', file_get_contents(ROOT . '/build_number'));
        $this->set('isLoggedIn', $this->Auth->user('user_id') > 1);
        $this->set('city', $this->Config->readGlobal('city'));
    }
}
