<?php

/**
 * Created by PhpStorm.
 * User: jvandenberg
 * Date: 12/27/13
 * Time: 11:56 AM
 * @property ConfigComponent Config
 */

class HomeController extends AppController
{
    public $components = array(
        'Config'
    );

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow();
    }

    public function home() {
        $this->set('content', $this->Config->Read('FRONT_PAGE'));
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

    function gsNews() {
        $news = $this->Config->Read('gs_frontpage');
        echo $news;
        die();
    }
} 