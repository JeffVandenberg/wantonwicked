<?php

/**
 * Created by PhpStorm.
 * User: JeffVandenberg
 * Date: 7/26/14
 * Time: 9:33 AM
 * @property mixed Permissions
 */
namespace App\Controller;

use App\Controller\Component\PermissionsComponent;
use Cake\Event\Event;

/**
 * @property PermissionsComponent Permissions
 */
class RequestsController extends AppController
{
    public $components = array(
        'Permissions'
    );

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
    }

    public function admin()
    {
        $storytellerMenu = $this->Menu->createStorytellerMenu();
        $this->set('submenu', $storytellerMenu);
    }

    public function isAuthorized($user)
    {
        return $this->Permissions->isAdmin();
    }
}
