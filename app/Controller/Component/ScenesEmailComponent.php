<?php

/**
 * Created by PhpStorm.
 * User: jvandenberg
 * Date: 7/6/15
 * Time: 3:45 PM
 */
class ScenesEmailComponent extends Component
{
    public function SendJoinEmail($scene, $sceneCharacter)
    {
        // load runner
        App::uses('User', 'Model');
        $users = new User();
        $user  = $users->find('first', array(
            'conditions' => array(
                'User.user_id' => $scene['Scene']['run_by_id']
            )
        ));

        // load character
        App::uses('Character', 'Model');
        $characters = new Character();
        $character  = $characters->find('first', array(
            'conditions' => array(
                'Character.id' => $sceneCharacter['SceneCharacter']['character_id']
            ),
            'contain'    => false
        ));

        App::uses('CakeEmail', 'Network/Email');
        $emailer = new CakeEmail();
        $emailer->to($user['User']['user_email']);
        $emailer->from('wantonwicked@gamingsandbox.com');
        $emailer->subject('A New character has joined your scene: ' . $scene['Scene']['name']);
        $emailer->emailFormat('html');
        $emailer->template('scene_join', 'wantonwicked')->viewVars(
            array(
                'scene'          => $scene,
                'sceneCharacter' => $sceneCharacter,
                'character'      => $character
            )
        );
        $emailer->send();
    }

    public function SendScheduleChange($newScene, $oldScene)
    {
        App::uses('SceneCharacter', 'Model');
        $sceneCharacterRepo = new SceneCharacter();
        $sceneCharacters    = $sceneCharacterRepo->find('all', array(
            'conditions' => array(
                'SceneCharacter.scene_id' => $newScene['Scene']['id']
            ),
            'contain'    => array(
                'Character' => array(
                    'fields' => array(
                        'id'
                    ),
                    'Player' => array(
                        'user_email'
                    )
                )
            )
        ));

        App::uses('CakeEmail', 'Network/Email');
        $emailer = new CakeEmail();
        $emailer->from('wantonwicked@gamingsandbox.com');
        $emailer->subject($newScene['Scene']['name'] . ' is being run at a new time');
        $emailer->emailFormat('html');
        $emailer->template('scene_schedule', 'wantonwicked')->viewVars(
            array(
                'newScene' => $newScene,
                'oldScene' => $oldScene
            )
        );
        foreach ($sceneCharacters as $sceneCharacter) {
            $emailer->to($sceneCharacter['Character']['Player']['user_email']);
            $emailer->send();
        }
    }

    public function SendCancelEmails($scene)
    {
        App::uses('SceneCharacter', 'Model');
        $sceneCharacterRepo = new SceneCharacter();
        $sceneCharacters    = $sceneCharacterRepo->find('all', array(
            'conditions' => array(
                'SceneCharacter.scene_id' => $scene['Scene']['id']
            ),
            'contain'    => array(
                'Character' => array(
                    'fields' => array(
                        'id'
                    ),
                    'Player' => array(
                        'user_email'
                    )
                )
            )
        ));

        App::uses('CakeEmail', 'Network/Email');
        $emailer = new CakeEmail();
        $emailer->from('wantonwicked@gamingsandbox.com');
        $emailer->subject($scene['Scene']['name'] . ' has been cancelled');
        $emailer->emailFormat('html');
        $emailer->template('scene_cancel', 'wantonwicked')->viewVars(
            array(
                'scene' => $scene,
            )
        );
        foreach ($sceneCharacters as $sceneCharacter) {
            $emailer->to($sceneCharacter['Character']['Player']['user_email']);
            $emailer->send();
        }
    }
}