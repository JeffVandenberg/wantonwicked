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
        $user = $users->find('first', array(
            'conditions' => array(
                'User.user_id' => $scene['Scene']['id']
            )
        ));

        // load character
        App::uses('Character', 'Model');
        $characters = new Character();
        $character = $characters->find('first', array(
            'conditions' => array(
                'Character.id' => $sceneCharacter['SceneCharater']['character_id']
            ),
            'contain' => false
        ));

        App::uses('CakeEmail', 'Network/Email');
        $emailer = new CakeEmail();
        $emailer
            ->from(array('wantonwicked@gamingsandbox.com' => 'Wanton Wicked Scenes'))
            ->to($user['User']['email_address'])
            ->subject($character['Character']['character_name'] . ' has joined the scene ' . $scene['Scene']['name'])
            ->emailFormat('html')
            ->template('scene_join', 'wantonwicked')
            ->viewVars(
                array(
                    'scene' => $scene,
                    'character' => $character
                )
            )
            ->send();
    }
}