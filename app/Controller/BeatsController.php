<?php

use classes\character\data\CharacterBeat;
use classes\character\nwod2\BeatService;

App::uses('AppController', 'Controller');

/**
 * Beats Controller
 *
 * @property PermissionsComponent Permissions
 */
class BeatsController extends AppController
{
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow(
            [
            ]);
    }

    public function viewDetails($beatId)
    {
        if (!$beatId) {
            $this->set('message', 'No Beat Specified');
        } else {
            $beatService = new BeatService();
            $beat = $beatService->findBeatById($beatId);

            if($beat) {
                $this->set(compact('beat'));
            } else {
                $this->set('message', 'Unable to find that beat');
            }
        }
    }

    public function isAuthorized()
    {
        switch (strtolower($this->request->params['action'])) {
        }

        return true;
    }
}
