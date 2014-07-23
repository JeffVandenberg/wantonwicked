<?php
/**
 * Created by PhpStorm.
 * User: JeffVandenberg
 * Date: 7/22/14
 * Time: 8:32 PM
 */

App::uses('AppController', 'Controller');

class UsersController extends AppController
{
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow();
    }

    public function login() {
        $this->redirect('/forum/ucp.php?mode=login');
    }
} 