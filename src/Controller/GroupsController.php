<?php

namespace App\Controller;

use App\Controller\Component\MenuComponent;
use App\Controller\Component\PermissionsComponent;
use App\Model\Table\GroupsTable;
use Cake\Network\Exception\NotFoundException;

/**
 * Groups Controller
 *
 * @property GroupsTable $Groups
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
        $this->set('groups', $this->paginate($this->Groups, [
            'contain' => [
                'GroupTypes'
            ]
        ]));
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
        $this->set('group', $this->Groups->get($id, [
            'contain' => [
                'GroupTypes'
            ]
        ]));
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
            } else {
                $this->Session->setFlash(__('The group could not be saved. Please, try again.'));
            }
        }
        $groupTypes = $this->Group->GroupType->find('list');
        $requestTypes = $this->Group->RequestType->find('list');
        $this->set(compact('groupTypes', 'requestTypes'));
    }

    /**
     * edit method
     *
     * @throws NotFoundException
     * @param string $id
     * @return \Cake\Network\Response|null Redirects on successful edit, renders view otherwise.
     */
    public function edit($id = null)
    {
        $group = $this->Groups->get($id, [
            'contain' => ['RequestTypes']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $group = $this->Groups->patchEntity($group, $this->request->getData());
            $group->created_by = $this->Auth->user('user_id');
            if ($this->Groups->save($group)) {
                $this->Flash->success(__('The group has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The group could not be saved. Please, try again.'));
        }

        $groupTypes = $this->Groups->GroupTypes->find('list', ['limit' => 200]);
        $requestTypes = $this->Groups->RequestTypes->find('list', ['limit' => 200]);
        $this->set(compact('group', 'groupTypes', 'requestTypes'));

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
        } else {
            $this->Session->setFlash(__('The group could not be deleted. Please, try again.'));
        }

        return $this->redirect(array('action' => 'index'));
    }

    public function listRequestTypes($id = null)
    {
        $this->Group->id = $id;
        if (!$this->Group->exists()) {
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
        $this->Group->recursive = false;
        $group = $this->Group->find('first', $options);

        $list = array();
        foreach ($group['RequestType'] as $requestType) {
            $list[] = array(
                'id' => $requestType['id'],
                'name' => $requestType['name']
            );
        }

        $this->response->disableCache();
        $this->set(compact('list'));
        $this->set('_serialize', array('list'));
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
            } else {
                $this->Session->setFlash(__('The group could not be saved. Please, try again.'));
            }
        }
        $groupTypes = $this->Group->GroupType->find('list');
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

    public function isAuthorized($user)
    {
        switch ($this->request->getParam('action')) {
            case 'listRequestTypes':
                return true;
                break;
            default:
                return $this->Permissions->IsAdmin();
        }
    }
}
