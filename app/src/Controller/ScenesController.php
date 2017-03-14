<?php
App::uses('AppController', 'Controller');

/**
 * Scenes Controller
 *
 * @property Scene $Scene
 * @property PaginatorComponent $Paginator
 * @property PermissionsComponent Permissions
 * @property ScenesEmailComponent ScenesEmail
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
    public $helpers = array(
        'Tags.TagCloud'
    );

    public $paginate = [
        'order' => [
            'Scene.run_on_date' => 'asc'
        ]
    ];

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow(['index', 'view', 'tag']);
    }

    public function beforeRender()
    {
        parent::beforeRender();
        $this->set('mayAdd', $this->Auth->user('id') != 1);
    }

    /**
     * index method
     *
     * @param null $year
     * @param null $month
     */
    public function index($year = null, $month = null)
    {
        $year = ($year) ? $year :  date('Y');
        $month = ($month) ? $month :  date('m');

        $scenes = $this->Scene->listScenesForMonth($year, $month);

        $this->set([
            'scenes' => $scenes,
            'year' => $year,
            'month' => $month,
            'mayEdit' => $this->Permissions->IsST(),
            'mayAdd' => $this->Auth->user('id') != 1,
        ]);
    }
    
    public function tag($tag = null)
    {
        $scenes = $this->Scene->listScenesWithTag($tag);
        
        $this->set([
            'scenes' => $scenes,
            'tag' => $tag,
            'mayEdit' => $this->Permissions->IsST(),
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
        $scene = $this->Scene->find('first', array(
            'conditions' => array(
                'Scene.slug' => $slug
            ),
            'contain'    => array(
                'RunBy'     => array(
                    'username'
                ),
                'CreatedBy' => array(
                    'username'
                ),
                'UpdatedBy' => array(
                    'username'
                ),
                'SceneStatus',
                'Tag'
            )
        ));
        if (!$scene) {
            $this->Flash->set('Unable to find Scene');
            $this->redirect(array('action' => 'index'));
        }
        $this->set('scene', $scene);
        $this->set('mayEdit',
                   $this->Permissions->IsST() ||
                   $this->Auth->user('user_id') == $scene['Scene']['created_by_id']);
        $this->set('isLoggedIn', $this->Auth->user('user_id') != 1);
        App::uses('Character', 'Model');
        $character       = new Character();
        $sceneCharacters = $character->FindCharactersForScene($scene['Scene']['id']);
        $this->set(compact('sceneCharacters'));
    }

    /**
     * add method
     *
     * @return void
     */
    public function add()
    {
        if ($this->request->is('post')) {
            if ($this->request->data['action'] == 'Cancel') {
                $this->redirect('/scenes');
            }
            if ($this->request->data['action'] == 'Create') {
                $this->Scene->create();
                $scene = $this->request->data;

                App::uses('SceneStatus', 'Model');
                $scene['Scene']['scene_status_id'] = SceneStatus::Open;
                $scene['Scene']['created_by_id']   = $this->Auth->user('user_id');
                $scene['Scene']['created_on']      = date('Y-m-d H:i:s');
                $scene['Scene']['updated_by_id']   = $this->Auth->user('user_id');
                $scene['Scene']['updated_on']      = date('Y-m-d H:i:s');

                if ($this->Scene->saveScene($scene)) {
                    $this->Flash->set(__('The scene has been saved. Invite people to it with this link:' .
                                                ' http://wantonwicked.gamingsandbox.com/scenes/join/' . $scene['Scene']['slug']));

                    $this->redirect(array('action' => 'view', $scene['Scene']['slug']));
                }
                else {
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
        $scene = $this->Scene->find('first', array(
            'conditions' => array(
                'Scene.slug' => $slug
            )
        ));
        if (!$scene) {
            $this->Flash->set('Unable to find Scene');
            $this->redirect(array('action' => 'index'));
        }

        if ($this->request->is(array('post', 'put'))) {
            if ($this->request->data['action'] == 'Cancel') {
                $this->redirect(array('action' => 'view', $scene['Scene']['slug']));
            }
            if ($this->request->data['action'] == 'Update') {
                $scene   = $this->request->data;
                $newDate = $scene['Scene']['run_on_date']['year'] . '-' .
                    $scene['Scene']['run_on_date']['month'] . '-' .
                    $scene['Scene']['run_on_date']['day'] . ' ' .
                    $scene['Scene']['run_on_date']['hour'] . ':' .
                    $scene['Scene']['run_on_date']['min'] . ' ' .
                    $scene['Scene']['run_on_date']['meridian'];

                $newRunDate = date('Y-m-d H:i:s', strtotime($newDate));
                $oldScene   = $this->Scene->find('first', array(
                    'conditions' => array(
                        'Scene.id' => $scene['Scene']['id']
                    ),
                    'contain'    => false
                ));

                $scene['Scene']['updated_by_id'] = $this->Auth->user('user_id');
                $scene['Scene']['updated_on']    = date('Y-m-d H:i:s');

                if ($this->Scene->saveScene($scene)) {
                    if ($oldScene['Scene']['run_on_date'] != $newRunDate) {
                        $scene['Scene']['run_on_date'] = $newRunDate;
                        $this->ScenesEmail->SendScheduleChange($scene, $oldScene);
                    }

                    $this->Flash->set(__('The scene has been saved.'));

                    $this->redirect(array('action' => 'view', $scene['Scene']['slug']));
                }
                else {
                    $this->Flash->set(__('The scene could not be saved. Please, try again.'));
                }
            }
        }
        else {
            $this->request->data = $scene;
        }
    }

    /**
     * delete method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function delete($id = null)
    {
        $this->Scene->id = $id;
        if (!$this->Scene->exists()) {
            throw new NotFoundException(__('Invalid scene'));
        }
        $this->request->onlyAllow('post', 'delete');
        if ($this->Scene->delete()) {
            $this->Flash->set(__('The scene has been deleted.'));
        }
        else {
            $this->Flash->set(__('The scene could not be deleted. Please, try again.'));
        }

        $this->redirect(array('action' => 'index'));
    }

    /**
     * Join an existing scene
     * @param $slug
     */
    public function join($slug)
    {
        $scene = $this->Scene->find('first', array(
            'conditions' => array(
                'Scene.slug' => $slug
            ),
            'contain'    => false
        ));

        if (!$scene) {
            $this->Flash->set('Unable to find Scene');
            $this->redirect(array('action' => 'index'));
        }

        if ($scene['Scene']['is_closed']) {
            $this->Flash->set('This Scene is closed');
            $this->redirect(array('action' => 'view', $slug));
        }

        if ($this->request->is(array('post', 'put'))) {
            if ($this->request->data['action'] == 'Cancel') {
                $this->redirect(array('action' => 'view', $slug));
            }
            if ($this->request->data['action'] == 'Join') {
                App::uses('SceneCharacter', 'Model');
                $sceneCharacter = new SceneCharacter();
                $sceneCharacter->create();

                $data['SceneCharacter'] = array(
                    'character_id' => $this->request->data['character_id'],
                    'scene_id'     => $scene['Scene']['id'],
                    'note'         => $this->request->data['note'],
                    'added_on'     => date('Y-m-d H:i:s')
                );

                if ($sceneCharacter->save($data)) {
                    $this->ScenesEmail->SendJoinEmail($scene, $data);
                    $this->Flash->set('Added character to scene');
                    $this->redirect(array('action' => 'view', $slug));
                }
                else {
                    $this->Flash->set('Error attaching character to scene');
                }
            }
        }

        App::uses('Character', 'Model');
        $character  = new Character();
        $characters = $character->FindCharactersNotInScene($this->Auth->user('user_id'), $scene['Scene']['id']);

        if (count($characters) == 0) {
            $this->Flash->set('You have no sanctioned characters, or all of your characters have joined the scene.');
            $this->redirect(array('action' => 'view', $slug));
        }

        $this->set(compact('characters', 'scene'));
    }

    public function cancel($slug)
    {
        $scene = $this->Scene->find('first', array(
            'conditions' => array(
                'Scene.slug' => $slug
            ),
            'contain'    => false
        ));

        if (!$scene) {
            $this->Flash->set('Unable to find Scene');
            $this->redirect(array('action' => 'index'));
        }

        App::uses('SceneStatus', 'Model');
        $scene['Scene']['scene_status_id'] = SceneStatus::Cancelled;

        if ($this->Scene->saveScene($scene)) {
            $this->ScenesEmail->SendCancelEmails($scene);
            $this->Flash->set('Scene Cancelled');
        }
        else {
            $this->Flash->set('Error Cancelling Scene');
        }
        $this->redirect(array('action' => 'view', $slug));
    }

    public function complete($slug)
    {
        $scene = $this->Scene->find('first', array(
            'conditions' => array(
                'Scene.slug' => $slug
            ),
            'contain'    => false
        ));

        if (!$scene) {
            $this->Flash->set('Unable to find Scene');
            $this->redirect(array('action' => 'index'));
        }

        App::uses('SceneStatus', 'Model');
        $scene['Scene']['scene_status_id'] = SceneStatus::Completed;

        if ($this->Scene->saveScene($scene)) {
            $this->Flash->set('Scene Completed');
        }
        else {
            $this->Flash->set('Error Completing Scene');
        }
        $this->redirect(array('action' => 'view', $slug));
    }

    public function isAuthorized($user)
    {
        switch ($this->request->params['action']) {
            default:
                return true || $this->Permissions->IsAdmin();
        }
    }

    public function my_scenes()
    {
        App::uses('SceneStatus', 'Model');
        $this->Scene->recursive    = 0;
        $this->Paginator->settings = array(
            'fields'     => array(
                'Scene.id',
                'Scene.name',
                'Scene.run_on_date',
                'Scene.summary',
                'Scene.slug',
                'Scene.run_by_id',
                'SceneStatus.name',
                'CreatedBy.username',
                'UpdatedBy.username',
                'RunBy.username'
            ),
            'conditions' => array(
//                'Scene.scene_status_id !=' => SceneStatus::Cancelled,
                'or'                       => array(
                    'Scene.run_by_id' => $this->Auth->user('user_id'),
                    'Character.user_id' => $this->Auth->user('user_id')
                )
            ),
            'order'      => array(
                'Scene.run_on_date' => 'asc'
            ),
            'contain' => array(
                'SceneStatus',
                'RunBy',
                'CreatedBy',
                'UpdatedBy',
                'SceneCharacter' => array(
                    'Character'
                )
            ),
            'joins' => array(
                array(
                    'alias' => 'SceneCharacter',
                    'table' => 'scene_characters',
                    'type' => 'LEFT',
                    'conditions' => '`Scene`.`id` = `SceneCharacter`.`scene_id`'
                ),
                array(
                    'alias' => 'Character',
                    'table' => 'characters',
                    'type' => 'LEFT',
                    'conditions' => '`SceneCharacter`.`character_id` = `Character`.`id`'
                )
            )
        );

        $this->set('scenes', $this->Paginator->paginate());
        $this->set('mayEdit', $this->Permissions->IsST());
        $this->set(compact('includePast'));

    }

    public function leave($slug, $characterId)
    {
        $scene = $this->Scene->find('first', array(
            'conditions' => array(
                'Scene.slug' => $slug
            ),
            'contain'    => false
        ));

        if (!$this->Permissions->MayEditCharacter($characterId)) {
            $this->Flash->set('You may not act on that character');
            $this->redirect(array(
                                'action' => 'view',
                                $slug
                            ));
        }
        App::uses('SceneCharacter', 'Model');
        $sceneCharacterRepo = new SceneCharacter();
        if ($sceneCharacterRepo->deleteAll(array(
                                            'SceneCharacter.scene_id'     => $scene['Scene']['id'],
                                            'SceneCharacter.character_id' => $characterId
                                        ))
        ) {
            $this->Flash->set('Removed your character from the scene');
        }
        else {
            $this->Flash->set('Unable to remove your character from the scene');
        }
        $this->redirect(array(
                            'action' => 'view',
                            $slug
                        ));
    }

    public function player_preferences($slug)
    {
        $scene = $this->Scene->find('first', array(
            'conditions' => array(
                'Scene.slug' => $slug
            ),
            'contain'    => false
        ));

        App::uses('PlayPreferenceResponse', 'Model');
        $repo = new PlayPreferenceResponse();
        $this->set('report', $repo->reportResponsesForPlayersInScene($scene['Scene']['id']));
        $this->set('scene', $scene);
    }
}
