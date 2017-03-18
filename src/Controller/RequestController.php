<?php

/**
 * Created by PhpStorm.
 * User: JeffVandenberg
 * Date: 7/26/14
 * Time: 9:33 AM
 * @property mixed Permissions
 */namespace app\Controller;

use App\Controller\AppController;



class RequestController extends AppController
{
    public $components = array(
        'Permissions'
    );

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
    }

    public function admin_index()
    {
        $storytellerMenu = $this->Menu->createStorytellerMenu();
        $storytellerMenu['Actions'] = array(
            'link' => '#',
            'submenu' => array(
                'List' => array(
                    'link' => array(
                        'action' => 'index'
                    )
                ),
                'Edit' => array(
                    'link' => array(
                        'action' => 'edit',
                        $id
                    )
                ),
                'New Template' => array(
                    'link' => array(
                        'action' => 'add'
                    )
                ),
            )
        );
        $this->set('submenu', $storytellerMenu);
    }

    public function isAuthorized($user)
    {
        return $this->Permissions->isAdmin();
    }
}