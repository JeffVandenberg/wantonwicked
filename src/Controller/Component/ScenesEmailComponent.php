<?php
namespace app\Controller\Component;

/**
 * Created by PhpStorm.
 * User: jvandenberg
 * Date: 7/6/15
 * Time: 3:45 PM
 */

use App\Model\Entity\Character;
use App\Model\Entity\Scene;
use App\Model\Entity\SceneCharacter;
use App\Model\Entity\User;
use Cake\Controller\Component;
use Cake\Mailer\Email;
use Cake\ORM\TableRegistry;

class ScenesEmailComponent extends Component
{
    public function SendJoinEmail(Scene $scene, SceneCharacter $sceneCharacter)
    {
        // load runner
        $usersTable = TableRegistry::getTableLocator()->get('Users');
        $user = $usersTable->get($scene->run_by_id);
        /* @var User $user */

        if (!$user) {
            // abort if there's no one declared running the scene yet
            return;
        }

        // load character
        $characters = TableRegistry::getTableLocator()->get('Characters');
        $character = $characters->get($sceneCharacter->character_id);
        /* @var Character $character */

        $emailer = new Email();
        $emailer->setTo($user->user_email);
        $emailer->setFrom('wantonwicked@gamingsandbox.com');
        $emailer->setSubject('A New character has joined your scene: ' . $scene->name);
        $emailer->setEmailFormat('html');
        $emailer->setLayout('wantonwicked');
        $emailer->setTemplate('scene_join')->setViewVars(
            [
                'scene' => $scene,
                'sceneCharacter' => $sceneCharacter,
                'character' => $character
            ]
        );
        $emailer->send();
    }

    public function SendScheduleChange(Scene $newScene, $oldScene)
    {
        $query = TableRegistry::getTableLocator()->get('SceneCharacters')->query();
        $query
            ->select([
                'Characters.character_name',
                'Users.user_email'
            ])
            ->contain([
                'Characters' => [
                    'Users'
                ]
            ])
            ->where([
                'SceneCharacters.scene_id' => $newScene->id
            ]);
        $sceneCharacters = $query->toArray();

        $emailer = (new Email())
            ->setFrom('wantonwicked@gamingsandbox.com')
            ->setSubject($newScene['Scene']['name'] . ' is being run at a new time')
            ->setEmailFormat('html')
            ->setLayout('wantonwicked')
            ->setTemplate('scene_schedule')
            ->setViewVars(
                [
                    'newScene' => $newScene,
                    'oldScene' => $oldScene
                ]
            );
        foreach ($sceneCharacters as $sceneCharacter) {
            $emailer->setTo($sceneCharacter['Character']['Player']['user_email']);
            $emailer->send();
        }
    }

    public function SendCancelEmails(Scene $scene)
    {
        $sceneCharacterTable = TableRegistry::getTableLocator()->get('SceneCharacters');
        $sceneCharacters = $sceneCharacterTable
            ->find()
            ->select()
            ->contain([
                'Character' => [
                    'Users' => [
                        'fields' => [
                            'user_email'
                        ]
                    ]
                ]
            ])
            ->where([
                'SceneCharacters.scene_id' => $scene->id
            ])
            ->toArray();
        /* @var SceneCharacter[] $sceneCharacters */

        $emailer = (new Email())
            ->setFrom('wantonwicked@gamingsandbox.com')
            ->setSubject($scene->name . ' has been cancelled')
            ->setEmailFormat('html')
            ->setLayout('wantonwicked')
            ->setTemplate('scene_cancel')
            ->setViewVars([
                'scene' => $scene,
            ]);
        foreach ($sceneCharacters as $sceneCharacter) {
            $emailer->setTo($sceneCharacter->character->user->user_email);
            $emailer->send();
        }
    }
}
