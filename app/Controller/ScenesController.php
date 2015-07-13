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

    public $paginate = array(
        'order' => array(
            'Scene.run_on_date' => 'asc'
        )
    );

    public function beforeFilter()
    {
        parent::beforeFilter();
    }

    /**
     * index method
     *
     * @param bool $includePast
     */
    public function index($includePast = false)
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
                'CreatedBy.username',
                'UpdatedBy.username',
                'RunBy.username'
            ),
            'conditions' => array(
                'Scene.scene_status_id !=' => SceneStatus::Cancelled
            ),
            'order'      => array(
                'Scene.run_on_date' => 'asc'
            )
        );

        if (!$includePast) {
            $this->Paginator->settings['conditions']['Scene.run_on_date >='] = date('Y-m-d H:i:s');
        }
        $this->set('scenes', $this->Paginator->paginate());
        $this->set('mayEdit', $this->Permissions->IsST());
        $this->set('mayAdd', $this->Auth->user('id') != 1);
        $this->set(compact('includePast'));
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
                'SceneStatus'
            )
        ));
        if (!$scene) {
            print_r($this->request->params);
            die($slug);
            $this->Session->setFlash('Unable to find Scene');
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

                if ($this->Scene->save($scene)) {
                    $this->Session->setFlash(__('The scene has been saved. Invite people to it with this link:' .
                                                ' http://wantonwicked.gamingsandbox.com/scenes/join/' . $scene['Scene']['slug']));

                    $this->redirect(array('action' => 'view', $scene['Scene']['slug']));
                }
                else {
                    $this->Session->setFlash(__('The scene could not be saved. Please, try again.'));
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
            $this->Session->setFlash('Unable to find Scene');
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

                if ($this->Scene->save($scene)) {
                    if ($oldScene['Scene']['run_on_date'] != $newRunDate) {
                        $scene['Scene']['run_on_date'] = $newRunDate;
                        $this->ScenesEmail->SendScheduleChange($scene, $oldScene);
                    }

                    $this->Session->setFlash(__('The scene has been saved.'));

                    $this->redirect(array('action' => 'view', $scene['Scene']['slug']));
                }
                else {
                    $this->Session->setFlash(__('The scene could not be saved. Please, try again.'));
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
            $this->Session->setFlash(__('The scene has been deleted.'));
        }
        else {
            $this->Session->setFlash(__('The scene could not be deleted. Please, try again.'));
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
            $this->Session->setFlash('Unable to find Scene');
            $this->redirect(array('action' => 'index'));
        }

        if ($scene['Scene']['is_closed']) {
            $this->Session->setFlash('This Scene is closed');
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
                    $this->Session->setFlash('Added character to scene');
                    $this->redirect(array('action' => 'view', $slug));
                }
                else {
                    $this->Session->setFlash('Error attaching character to scene');
                }
            }
        }

        App::uses('Character', 'Model');
        $character  = new Character();
        $characters = $character->FindCharactersNotInScene($this->Auth->user('user_id'), $scene['Scene']['id']);

        if (count($characters) == 0) {
            $this->Session->setFlash('You have no sanctioned characters, or all of your characters have joined the scene.');
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
            $this->Session->setFlash('Unable to find Scene');
            $this->redirect(array('action' => 'index'));
        }

        App::uses('SceneStatus', 'Model');
        $scene['Scene']['scene_status_id'] = SceneStatus::Cancelled;

        if ($this->Scene->save($scene)) {
            $this->ScenesEmail->SendCancelEmails($scene);
            $this->Session->setFlash('Scene Cancelled');
        }
        else {
            $this->Session->setFlash('Error Cancelling Scene');
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
            $this->Session->setFlash('Unable to find Scene');
            $this->redirect(array('action' => 'index'));
        }

        App::uses('SceneStatus', 'Model');
        $scene['Scene']['scene_status_id'] = SceneStatus::Completed;

        if ($this->Scene->save($scene)) {
            $this->Session->setFlash('Scene Completed');
        }
        else {
            $this->Session->setFlash('Error Completing Scene');
        }
        $this->redirect(array('action' => 'view', $slug));
    }

    public function isAuthorized($user)
    {
        switch ($this->request->params['action']) {
            case 'listRequestTypes':
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
        $this->set('mayAdd', $this->Auth->user('id') != 1);
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
            $this->Session->setFlash('You may not act on that character');
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
            $this->Session->setFlash('Removed your character from the scene');
        }
        else {
            $this->Session->setFlash('Unable to remove your character from the scene');
        }
        $this->redirect(array(
                            'action' => 'view',
                            $slug
                        ));
    }

    public function test()
    {
        $scene = $this->Scene->find('first', array(
            'conditions' => array(
                'Scene.id' => 22
            ),
            'contain'    => false
        ));

        App::uses('SceneCharacter', 'Model');
        $sceneCharacters = new SceneCharacter();
        $sceneCharacter  = $sceneCharacters->find('first', array(
            'conditions' => array(
                'SceneCharacter.id' => 19
            ),
            'contain'    => false
        ));

        $this->set(compact('scene', 'sceneCharacter'));


        App::uses('CakeEmail', 'Network/Email');
        $emailer = new CakeEmail();
        $emailer->to('jeffvandenberg@gmail.com');
        $emailer->from('wantonwicked@gamingsandbox.com');
        $emailer->subject('Test Message');
        $emailer->emailFormat('html');
        $emailer->template('scene_join', 'wantonwicked')->viewVars(
            array(
                'scene'          => $scene,
                'sceneCharacter' => $sceneCharacter
            )
        );
        $emailer->send();
    }

}
