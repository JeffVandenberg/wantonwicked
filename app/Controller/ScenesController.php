<?php
use classes\core\helpers\SlugHelper;

App::uses('AppController', 'Controller');

/**
 * Scenes Controller
 *
 * @property Scene $Scene
 * @property PaginatorComponent $Paginator
 * @property PermissionsComponent Permissions
 */
class ScenesController extends AppController
{

    /**
     * Components
     *
     * @var array
     */
    public $components = array('Paginator');

    public $paginate = array(
        'order' => array(
            'Scene.run_on_date' => 'asc'
        )
    );

    public function beforeFilter()
    {
//        $this->redirect('/');
        parent::beforeFilter();
        $this->Auth->allow(array(
                               'index',
                               'view'
                           ));
    }

    /**
     * index method
     *
     * @param bool $includePast
     */
    public function index($includePast = false)
    {
        $this->Scene->recursive    = 0;
        $this->Paginator->settings = array(
            'fields' => array(
                'Scene.id',
                'Scene.name',
                'Scene.run_on_date',
                'Scene.summary',
                'Scene.slug',
                'CreatedBy.username',
                'UpdatedBy.username',
                'RunBy.username'
            ),
            'order'  => array(
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
                $scene                           = $this->request->data;
                $scene['Scene']['updated_by_id'] = $this->Auth->user('user_id');
                $scene['Scene']['updated_on']    = date('Y-m-d H:i:s');

                if ($this->Scene->save($scene)) {
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
        $characters = $character->find('list', array(
            'conditions' => array(
                'Character.user_id'       => $this->Auth->user('user_id'),
                'Character.is_sanctioned' => 'Y',
                'Character.is_deleted'    => 'N'
            ),
            'order'      => array(
                'character_name' => 'desc'
            ),
            'contain'    => false
        ));

        $this->set(compact('characters', 'scene'));
    }

    public function cancel($slug) {
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

        if($this->Scene->save($scene)) {
            $this->Session->setFlash('Scene Cancelled');
        }
        else {
            $this->Session->setFlash('Error Cancelling Scene');
        }
        $this->redirect(array('action' => 'view', $slug));
    }

    public function complete($slug) {
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

        if($this->Scene->save($scene)) {
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

}
