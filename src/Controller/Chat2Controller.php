<?php

/**
 * Created by PhpStorm.
 * User: jvandenberg
 * Date: 12/20/2015
 * Time: 12:08 AM
 * @property PermissionsComponent Permissions
 */namespace app\Controller;

use App\Controller\AppController;


class Chat2Controller extends AppController
{
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
    }

    public function login_st()
    {

    }

    public function isAuthorized($user)
    {
        switch($this->request->params['action'])
        {
            case 'login_st':
                return $this->Permissions->IsST();
                break;
            case 'login_ooc':
                return $this->Auth->loggedIn();
                break;
        }
        return false;
    }
}