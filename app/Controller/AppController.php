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

App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {
    public $components = array(
        'DebugKit.Toolbar',
        'Session',
        'RequestHandler',
        'Paginator',
        'Auth',
        'Menu',
        'Permissions',
        'RequestHandler',
        'Flash'
    );

    public $helpers = array(
        'Html',
        'Session',
        'Form',
        'Js' => array(
            'Jquery'
        ),
        'MainMenu',
        'Shrink.Shrink' => [
            'debugLevel' => 1
        ]
    );

    public function beforeFilter()
    {
        $this->Auth->authenticate = array('Phpbb');
        $this->Auth->authorize = array('Controller');
        $this->Auth->unauthorizedRedirect = '/forum/ucp.php?mode=login';
        $this->Auth->loginRedirect = '/forum/ucp.php?mode=login';

        if(!AuthComponent::user())
        {
            $this->Auth->login();
        }
        else {
            global $userdata;
            if($userdata['user_id'] != $this->Auth->user('user_id')) {
                $this->Auth->logout();
                $this->Auth->login();
            }
        }
        $this->Menu->InitializeMenu();
        $this->Auth->deny();
    }

    public function beforeRender() {
        parent::beforeRender();
        $this->layout = ($this->request->is("ajax")) ? "ajax" : "default";
        $this->set('menu', $this->Menu->GetMenu());
        $this->set('serverTime', (microtime(true) + date('Z'))*1000);
        $this->set('buildNumber', file_get_contents(ROOT . '/build_number'));
    }

}
