<?php

namespace App\Controller;

use App\Controller\Component\PermissionsComponent;
use App\Controller\Component\ScenesEmailComponent;
use App\Model\Entity\Scene;
use App\Model\Entity\SceneCharacter;
use App\Model\Entity\SceneStatus;
use App\Model\Table\CharactersTable;
use App\Model\Table\PlayPreferenceResponsesTable;
use App\Model\Table\ScenesTable;
use Cake\Controller\Component\PaginatorComponent;
use Cake\Event\Event;
use Cake\ORM\Query;
use Cake\ORM\TableRegistry;
use classes\character\data\CharacterStatus;
use IntlDateFormatter;


/**
 * Scenes Controller
 *
 * @property PaginatorComponent $Paginator
 * @property PermissionsComponent Permissions
 * @property \app\Controller\Component\ScenesEmailComponent $ScenesEmail
 * @property \App\Model\Table\ScenesTable $Scenes
 */
class ScenesController extends AppController
{
    /**
     * Components
     *
     * @var array
     */
    public $components = array(
        'ScenesEmail'
    );
    public $helpers = array();

    public $paginate = [
        'order' => [
            'Scene.run_on_date' => 'asc'
        ]
    ];

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(['index', 'view', 'tag', 'search', 'tagged']);
    }

    public function beforeRender(Event $event)
    {
        parent::beforeRender($event);
        $this->set('mayAdd', $this->Auth->user() && ($this->Auth->user('user_id') != 1));
    }

    /**
     * index method
     *
     * @param null $year
     * @param null $month
     */
    public function index($year = null, $month = null)
    {
        $year = ($year) ? $year : date('Y');
        $month = ($month) ? $month : date('m');

        $monthStart = "$year-$month-01";
        $monthEnd = date("Y-m-d", strtotime("+1 month", strtotime($monthStart)));

        $scenes = TableRegistry::getTableLocator()->get('Scenes')
            ->find()
            ->where(
                [
                    'Scenes.run_on_date >=' => $monthStart,
                    'Scenes.run_on_date <' => $monthEnd,
                ]
            )
            ->contain([
            ])
            ->order('Scenes.run_on_date')
            ->toArray();

        $this->set([
            'scenes' => $scenes,
            'year' => $year,
            'month' => $month,
            'mayEdit' => $this->Permissions->isST(),
        ]);
    }

    public function tagged($tag = null)
    {
        $scenes = $this->paginate(
            $this->Scenes->listScenesWithTag($tag),
            [
                'limit' => 25,
                'order' => [
                    'Scenes.run_on_date' => 'asc'
                ]
            ]);

        $this->set([
            'scenes' => $scenes,
            'tag' => $tag,
            'mayEdit' => $this->Permissions->isST(),
            'mayAdd' => $this->Auth->user('id') != 1,
        ]);
    }

    /**
     * view method
     *
     *
     * @param null $slug
     * @internal param string $id
     */
    public function view($slug = null)
    {
        $query = $this->Scenes
            ->find()
            ->where(['Scenes.slug' => $slug])
            ->contain([
                'RunBy',
                'CreatedBy',
                'UpdatedBy',
                'SceneStatuses',
                'Tags'
            ]);

        $scene = $query->firstOrFail();
        /* @var Scene $scene */

        if (!$scene) {
            $this->Flash->set('Unable to find Scene');
            $this->redirect(array('action' => 'index'));
        }
        $this->set('scene', $query->first());
        $this->set('mayEdit',
            $this->Permissions->isST() ||
            $this->Auth->user('user_id') == $scene->created_by_id ||
            $this->Auth->user('user_id') == $scene->run_by_id);
        $this->set('isLoggedIn', $this->Auth->user('user_id') != 1);


        $sceneCharactersTable = TableRegistry::getTableLocator()->get('SceneCharacters');

        $sceneCharacters = $sceneCharactersTable
            ->query()
            ->where(['SceneCharacters.scene_id' => $scene->id])
            ->contain([
                'Characters'
            ])
            ->order([
                'Characters.character_name'
            ])
            ->toArray();
        $this->set(compact('sceneCharacters'));
    }

    /**
     * add method
     *
     * @return void
     */
    public function add()
    {
        if ($this->getRequest()->is('post')) {
            if ($this->getRequest()->getData()['action'] == 'Cancel') {
                $this->redirect('/scenes');
            }
            if ($this->getRequest()->getData()['action'] == 'Create') {
                $scene = $this->Scenes->newEntity();
                $scene = $this->Scenes->patchEntity($scene, $this->getRequest()->getData());
                $scene->scene_status_id = SceneStatus::OPEN;
                $scene->created_by_id = $this->Auth->user('user_id');
                $scene->created_on = date('Y-m-d H:i:s');
                $scene->updated_by_id = $this->Auth->user('user_id');
                $scene->updated_on = date('Y-m-d H:i:s');

                if ($this->Scenes->save($scene)) {
                    $this->Flash->set(__('The scene has been saved. Invite people to it with this link:' .
                        ' http://wantonwicked.gamingsandbox.com/scenes/join/' . $scene->slug));

                    $this->redirect(array('action' => 'view', $scene->slug));
                } else {
                    $this->Flash->set(__('The scene could not be saved. Please, try again.'));
                }
            }
        }
    }

    /**
     * edit method
     *
     *
     * @param null $slug
     */
    public function edit($slug = null)
    {
        $scene = $this->Scenes
            ->query()
            ->where([
                    'Scenes.slug' => $slug
                ]
            )
            ->contain([
                'RunBy' => [
                    'fields' => [
                        'username'
                    ]
                ],
                'Tags'
            ])
            ->first();
        /* @var Scene $scene */

        if (!$scene) {
            $this->Flash->set('Unable to find Scene');
            return $this->redirect(array('action' => 'index'));
        }

        if ($this->getRequest()->is(['post', 'put', 'patch'])) {
            if ($this->getRequest()->getData('action') === 'Cancel') {
                return $this->redirect(['action' => 'view', $scene->slug]);
            }

            $oldScene = clone $scene;
            $scene = $this->Scenes->patchEntity($scene, $this->getRequest()->getData());

            $scene->updated_by_id = $this->Auth->user('user_id');
            $scene->updated_on = date('Y-m-d H:i:s');

            if ($this->Scenes->save($scene)) {
                if ($oldScene->run_on_date != $scene->run_on_date) {
                    $this->ScenesEmail->sendScheduleChange($scene, $oldScene);
                }

                $this->Flash->set(__('The scene has been saved.'));

                return $this->redirect(array('action' => 'view', $scene->slug));
            }

            $this->Flash->set(__('The scene could not be saved. Please, try again.'));
        }
        $this->set(compact('scene'));
    }

    /**
     * Join an existing scene
     * @param $slug
     */
    public function join($slug)
    {
        $scene = $this->Scenes
            ->query()
            ->where([
                    'Scenes.slug' => $slug
                ]
            )
            ->contain([
                'RunBy' => [
                    'fields' => [
                        'username'
                    ]
                ]
            ])
            ->first();
        /* @var Scene $scene */
        if (!$scene) {
            $this->Flash->set('Unable to find Scene');
            $this->redirect(array('action' => 'index'));
        }

        if ($scene->scene_status_id == SceneStatus::COMPLETED) {
            $this->Flash->set('This Scene is closed');
            $this->redirect(array('action' => 'view', $slug));
        }

        if ($this->getRequest()->is(array('post', 'put'))) {
            if ($this->getRequest()->getData()['action'] == 'Cancel') {
                $this->redirect(array('action' => 'view', $slug));
            }
            if ($this->getRequest()->getData()['action'] == 'Join') {
                $sceneCharacters = TableRegistry::getTableLocator()->get('SceneCharacters');
                $sceneCharacter = $sceneCharacters->newEntity();
                /* @var SceneCharacter $sceneCharacter */
                $data = $this->getRequest()->getData();
                $sceneCharacter->character_id = $data['character_id'];
                $sceneCharacter->scene_id = $data['scene_id'];
                $sceneCharacter->note = $data['note'];
                $sceneCharacter->added_on = date('Y-m-d H:i:s');

                if ($sceneCharacters->save($sceneCharacter)) {
                    $this->ScenesEmail->sendJoinEmail($scene, $sceneCharacter);
                    $this->Flash->set('Added character to scene');
                    $this->redirect(array('action' => 'view', $slug));
                } else {
                    $this->Flash->set('Error attaching character to scene');
                }
            }
        }

        $characterTable = TableRegistry::getTableLocator()->get('Characters');
        /* @var CharactersTable $characters */
        $query = $characterTable
            ->find('list')
            ->select([
                'Characters.id',
                'Characters.character_name',
            ])
            ->where([
                'Characters.user_id' => $this->Auth->user('user_id'),
                'Characters.character_status_id IN ' => CharacterStatus::SANCTIONED,
            ])
            ->notMatching('SceneCharacters', function (Query $q) use ($scene) {
                return $q->where([
                    'SceneCharacters.scene_id' => $scene->id
                ]);
            });
        $characters = $query->toArray();

        if (count($characters) == 0) {
            $this->Flash->set('You have no sanctioned characters, or all of your characters have joined the scene.');
            $this->redirect(array('action' => 'view', $slug));
        }

        $this->set(compact('characters', 'scene'));
    }

    public function cancel($slug)
    {
        $scene = $this->Scenes
            ->query()
            ->where([
                    'Scenes.slug' => $slug
                ]
            )
            ->first();
        /* @var Scene $scene */

        if (!$scene) {
            $this->Flash->set('Unable to find Scene');
            $this->redirect(array('action' => 'index'));
        }

        $scene->scene_status_id = SceneStatus::CANCELLED;

        if ($this->Scenes->save($scene)) {
            $this->ScenesEmail->sendCancelEmails($scene);
            $this->Flash->set('Scene Cancelled');
        } else {
            $this->Flash->set('Error Cancelling Scene');
        }
        $this->redirect(array('action' => 'view', $slug));
    }

    public function complete($slug)
    {
        $scene = $this->Scenes
            ->query()
            ->where([
                    'Scenes.slug' => $slug
                ]
            )
            ->first();
        /* @var Scene $scene */

        if (!$scene) {
            $this->Flash->set('Unable to find Scene');
            $this->redirect(array('action' => 'index'));
        }

        $scene->scene_status_id = SceneStatus::COMPLETED;

        if ($this->Scenes->save($scene)) {
            $this->Flash->set('Scene Completed');
        } else {
            $this->Flash->set('Error Completing Scene');
        }
        $this->redirect(array('action' => 'view', $slug));
    }

    public function isAuthorized($user)
    {
        switch ($this->getRequest()->getParam('action')) {
            default:
                return true || $this->Permissions->isAdmin();
        }
    }

    public function myScenes()
    {
        $query = $this->Scenes
            ->query()
            ->select([
                'Scenes.id',
                'Scenes.name',
                'Scenes.run_on_date',
                'Scenes.summary',
                'Scenes.slug',
                'Scenes.run_by_id',
                'SceneStatuses.name',
                'CreatedBy.username',
                'UpdatedBy.username',
                'RunBy.username'
            ])
            ->where([
                'or' => [
                    'Scenes.run_by_id' => $this->Auth->user('user_id'),
                    'Characters.user_id' => $this->Auth->user('user_id')
                ]
            ])
            ->contain([
                'SceneStatuses',
                'RunBy',
                'CreatedBy',
                'UpdatedBy',
                'SceneCharacters' => array(
                    'Characters'
                )
            ])
            ->join([
                    [
                        'alias' => 'SceneCharacters',
                        'table' => 'scene_characters',
                        'type' => 'LEFT',
                        'conditions' => '`Scenes`.`id` = `SceneCharacters`.`scene_id`'
                    ],
                    [
                        'alias' => 'Characters',
                        'table' => 'characters',
                        'type' => 'LEFT',
                        'conditions' => '`SceneCharacters`.`character_id` = `Characters`.`id`'
                    ]
                ]
            );

        $this->set('scenes', $this->paginate($query, [
            'limit' => 25,
            'order' => [
                'Scenes.run_on_date' => 'asc'
            ]
        ]));
        $this->set('mayEdit', $this->Permissions->isST());
        $this->set(compact('includePast'));

    }

    public function leave($slug, $characterId)
    {
        $scene = $this->Scenes
            ->query()
            ->where([
                    'Scenes.slug' => $slug
                ]
            )
            ->first();

        if (!$this->Permissions->mayEditCharacter($characterId)) {
            $this->Flash->set('You may not act on that character');
            $this->redirect(array(
                'action' => 'view',
                $slug
            ));
        }
        $sceneCharacterTable = TableRegistry::getTableLocator()->get('SceneCharacters');
        if ($sceneCharacterTable->deleteAll(array(
            'SceneCharacter.scene_id' => $scene->id,
            'SceneCharacter.character_id' => $characterId
        ))
        ) {
            $this->Flash->set('Removed your character from the scene');
        } else {
            $this->Flash->set('Unable to remove your character from the scene');
        }
        $this->redirect(array(
            'action' => 'view',
            $slug
        ));
    }

    public function playerPreferences($slug)
    {
        $scene = $this->Scenes
            ->query()
            ->where([
                    'Scenes.slug' => $slug
                ]
            )
            ->first();

        $repo = TableRegistry::getTableLocator()->get('PlayPreferenceResponses');//
        /* @var PlayPreferenceResponsesTable $repo */
        $this->set('report', $repo->reportResponsesForPlayersInScene($scene->id));
        $this->set('scene', $scene);
    }

    public function search()
    {
        $query = $this->getRequest()->getQuery('query', '');
        $suggestions = [];
        if ($query) {
            $scenes = $this->Scenes->find('all', [
                'contain' => [
                    'RunBy' => [
                        'fields' => ['username']
                    ]
                ],
                'fields' => [
                    'Scenes.name',
                    'Scenes.id',
                    'Scenes.run_on_date'
                ],
                'conditions' => [
                    'Scenes.name like' => $query . '%',
                ],
                'order' => [
                    'Scenes.name' => 'asc',
                    'Scenes.run_on_date' => 'asc'
                ]
            ]);
            /* @var Scene[] $scenes */
            foreach ($scenes as $scene) {
                $suggestions[] = [
                    'data' => $scene->id,
                    'value' => $scene->name . ' on ' . $scene->run_on_date . ' by ' . $scene->run_by->username
                ];
            }
        }
        $this->set(compact('query', 'suggestions'));
        $this->set('_serialize', ['query', 'suggestions']);
    }
}
