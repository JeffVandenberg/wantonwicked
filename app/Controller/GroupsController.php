<?php
App::uses('AppController', 'Controller');

/**
 * Groups Controller
 *
 * @property Group $Group
 * @property PaginatorComponent $Paginator
 * @property PermissionsComponent Permissions
 * @property MenuComponent Menu
 */
class GroupsController extends AppController
{

    /**
     * Components
     *
     * @var array
     */
    public $components = array('Paginator');

    /**
     * index method
     *
     * @return void
     */
    public function index()
    {
        $this->Group->recursive = 0;
        $this->set('groups', $this->Paginator->paginate());
    }

    /**
     * view method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function view($id = null)
    {
        if (!$this->Group->exists($id)) {
            throw new NotFoundException(__('Invalid group'));
        }
        $options = array('conditions' => array('Group.' . $this->Group->primaryKey => $id));
        $this->set('group', $this->Group->find('first', $options));
    }

    /**
     * add method
     *
     * @return void
     */
    public function add()
    {
        if ($this->request->is('post')) {
            $this->Group->create();
            if ($this->Group->save($this->request->data)) {
                $this->Session->setFlash(__('The group has been saved.'));

                return $this->redirect(array('action' => 'index'));
            }
            else {
                $this->Session->setFlash(__('The group could not be saved. Please, try again.'));
            }
        }
        $groupTypes   = $this->Group->GroupType->find('list');
        $requestTypes = $this->Group->RequestType->find('list');
        $this->set(compact('groupTypes', 'requestTypes'));
    }

    /**
     * edit method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function edit($id = null)
    {
        if (!$this->Group->exists($id)) {
            throw new NotFoundException(__('Invalid group'));
        }
        if ($this->request->is(array('post', 'put'))) {
            if ($this->Group->save($this->request->data)) {
                $this->Session->setFlash(__('The group has been saved.'));

                return $this->redirect(array('action' => 'index'));
            }
            else {
                $this->Session->setFlash(__('The group could not be saved. Please, try again.'));
            }
        }
        else {
            $options             = array('conditions' => array('Group.' . $this->Group->primaryKey => $id));
            $this->request->data = $this->Group->find('first', $options);
        }
        $groupTypes   = $this->Group->GroupType->find('list');
        $requestTypes = $this->Group->RequestType->find('list');
        $this->set(compact('groupTypes', 'requestTypes'));
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
        $this->Group->id = $id;
        if (!$this->Group->exists()) {
            throw new NotFoundException(__('Invalid group'));
        }
        $this->request->onlyAllow('post', 'delete');
        if ($this->Group->delete()) {
            $this->Session->setFlash(__('The group has been deleted.'));
        }
        else {
            $this->Session->setFlash(__('The group could not be deleted. Please, try again.'));
        }

        return $this->redirect(array('action' => 'index'));
    }

    public function listRequestTypes($id = null)
    {
        $this->Group->id = $id;
        if(!$this->Group->exists()) {
            throw new NotFoundException(__('Invalid group'));
        }
        $options = array(
            'conditions' => array(
                'Group.' . $this->Group->primaryKey => $id
            ),
            'contain' => array(
                'RequestType'
            ),
        );
        $group = $this->Group->find('first', $options);

        $list = array();
        foreach($group['RequestType'] as $requestType)
        {
            $list[] = array(
                'id' => $requestType['id'],
                'name' => $requestType['name']
            );
        }

        $this->set(compact('list'));
        $this->set('_serialize', array('list'));
    }

    /**
     * admin_index method
     *
     * @return void
     */
    public function admin_index()
    {
        $this->Group->recursive = 0;
        $this->set('groups', $this->Paginator->paginate());
        $storytellerMenu = $this->Menu->createStorytellerMenu();
        $storytellerMenu['Actions'] = array(
            'link' => '#',
            'submenu' => array(
                'New Group' => array(
                    'link' => array(
                        'action' => 'add'
                    )
                )
            )
        );
        $this->set('submenu', $storytellerMenu);
    }

    /**
     * admin_view method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function admin_view($id = null)
    {
        if (!$this->Group->exists($id)) {
            throw new NotFoundException(__('Invalid group'));
        }
        $options = array('conditions' => array('Group.' . $this->Group->primaryKey => $id));
        $this->set('group', $this->Group->find('first', $options));
        $storytellerMenu = $this->Menu->createStorytellerMenu();
        $storytellerMenu['Actions'] = array(
            'link' => '#',
            'submenu' => array(
                'New Group' => array(
                    'link' => array(
                        'action' => 'add'
                    )
                ),
                'Edit' => array(
                    'link' => array(
                        'action' => 'edit',
                        $id
                    )
                )
            )
        );
        $this->set('submenu', $storytellerMenu);
    }

    /**
     * admin_add method
     *
     * @return void
     */
    public function admin_add()
    {
        if ($this->request->is('post')) {
            $this->Group->create();
            $this->request->data['Group']['created_by'] = $this->Auth->user('user_id');
            if ($this->Group->save($this->request->data)) {
                $this->Session->setFlash(__('The group has been saved.'));

                return $this->redirect(array('action' => 'index'));
            }
            else {
                $this->Session->setFlash(__('The group could not be saved. Please, try again.'));
            }
        }
        $groupTypes   = $this->Group->GroupType->find('list');
        $requestTypes = $this->Group->RequestType->find('list');
        $this->set(compact('groupTypes', 'requestTypes'));
        $storytellerMenu = $this->Menu->createStorytellerMenu();
        $storytellerMenu['Actions'] = array(
            'link' => '#',
            'submenu' => array(
                'List' => array(
                    'link' => array(
                        'action' => 'index'
                    )
                ),
            )
        );
        $this->set('submenu', $storytellerMenu);
    }

    /**
     * admin_edit method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function admin_edit($id = null)
    {
        if (!$this->Group->exists($id)) {
            throw new NotFoundException(__('Invalid group'));
        }
        if ($this->request->is(array('post', 'put'))) {
            if ($this->Group->save($this->request->data)) {
                $this->Session->setFlash(__('The group has been saved.'));

                return $this->redirect(array('action' => 'index'));
            }
            else {
                $this->Session->setFlash(__('The group could not be saved. Please, try again.'));
            }
        }
        else {
            $options             = array('conditions' => array('Group.' . $this->Group->primaryKey => $id));
            $this->request->data = $this->Group->find('first', $options);
        }
        $groupTypes   = $this->Group->GroupType->find('list');
        $requestTypes = $this->Group->RequestType->find('list');
        $this->set(compact('groupTypes', 'requestTypes'));
        $storytellerMenu = $this->Menu->createStorytellerMenu();
        $storytellerMenu['Actions'] = array(
            'link' => '#',
            'submenu' => array(
                'List' => array(
                    'link' => array(
                        'action' => 'index'
                    )
                ),
            )
        );
        $this->set('submenu', $storytellerMenu);
    }

    /**
     * admin_delete method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function admin_delete($id = null)
    {
        $this->Group->id = $id;
        if (!$this->Group->exists()) {
            throw new NotFoundException(__('Invalid group'));
        }
        $this->request->onlyAllow('post', 'delete');

        $this->request->data['Group']['is_deleted'] = 1;
        $this->request->data['Group']['id'] = $id;

        if ($this->Group->save($this->request->data)) {
            $this->Session->setFlash(__('The group has been deleted.'));
        }
        else {
            $this->Session->setFlash(__('The group could not be deleted. Please, try again.'));
        }

        return $this->redirect(array('action' => 'index'));
    }

    public function isAuthorized($user)
    {
        switch($this->request->params['action']) {
            case 'listRequestTypes':
                return true;
                break;
            default:
                return $this->Permissions->IsAdmin();
        }
    }
}
