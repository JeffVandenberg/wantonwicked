<?php
/**
 * Created by PhpStorm.
 * User: jvandenberg
 * Date: 12/27/13
 * Time: 11:56 AM
 */

class HomeController extends AppController
{
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow();
    }

    public function home() {

    }

    public function staff() {
        App::uses('LegacyUser', 'Model');
        $legacyUser = new LegacyUser();

        $admins = $legacyUser->listAdmins();
        $sts = $legacyUser->listSts();
        $assts = $legacyUser->listAssts();
        $wikis = $legacyUser->listWikiManagers();

        $this->set(compact('admins', 'sts', 'assts', 'wikis'));
    }
} 