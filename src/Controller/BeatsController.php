<?php

namespace app\Controller;

use classes\character\data\CharacterBeat;
use classes\character\nwod2\BeatService;

use App\Controller\AppController;

/**
 * Beats Controller
 *
 * @property PermissionsComponent Permissions
 */
class BeatsController extends AppController
{
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
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

            if ($beat) {
                if (!$this->Permissions->MayEditCharacter($beat->CharacterId)) {
                    $this->set('message', 'You may not be able to view that character');
                } else {
                    $this->set(compact('beat'));
                }
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
