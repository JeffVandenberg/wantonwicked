<?php

namespace App\Controller;

use App\Controller\Component\PermissionsComponent;
use App\Model\Table\RolesTable;
use Cake\Controller\Component\PaginatorComponent;
use Cake\Event\Event;
use Cake\Network\Exception\NotFoundException;

/**
 * Roles Controller
 *
 * @property RolesTable $Roles
 * @property PaginatorComponent $Paginator
 * @property PermissionsComponent Permissions
 */
class RolesController extends AppController
{

    /**
     * Components
     *
     * @var array
     */
    public $components = [
        'Paginator'
    ];

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(array(
            'index',
            'view'
        ));
    }

    /**
     * index method
     *
     * @return void
     */
    public function index()
    {
        $this->set('roles', $this->Paginator->paginate($this->Roles, [
            'order' => [
                'Roles.name'
            ],
            'limit' => 20
        ]));
        $this->set('mayEdit', $this->Permissions->IsHead());
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
        $this->set('role', $this->Roles->get($id, [
            'contain' => [
                'Permissions'
            ]
        ]));
        $this->set('mayEdit', $this->Permissions->IsHead());
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
                $this->redirect(['action' => 'index']);
            } else {
                $this->Role->create();
                if ($this->Role->save($this->request->data)) {
                    $this->Session->setFlash(__('The role has been saved.'));
                    $this->redirect(array('action' => 'index'));
                } else {
                    $this->Session->setFlash(__('The role could not be saved. Please, try again.'));
                }
            }
        }
        $permissions = $this->Role->Permission->find('list', [
            'order' => 'permission_name'
        ]);
        $this->set(compact('permissions'));
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
        if (!$this->Role->exists($id)) {
            throw new NotFoundException(__('Invalid role'));
        }
        if ($this->request->is(array('post', 'put'))) {
            if ($this->request->data['action'] == 'Cancel') {
                $this->redirect(['action' => 'index']);
            } else {
                if ($this->Role->save($this->request->data)) {
                    $this->Session->setFlash(__('The role has been saved.'));
                    $this->redirect(array('action' => 'index'));
                } else {
                    $this->Session->setFlash(__('The role could not be saved. Please, try again.'));
                }
            }
        } else {
            $options = array('conditions' => array('Role.' . $this->Role->primaryKey => $id));
            $this->request->data = $this->Role->find('first', $options);
        }
        $permissions = $this->Role->Permission->find('list', [
            'order' => 'permission_name'
        ]);
        $this->set(compact('permissions'));
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
        $this->Role->id = $id;
        if (!$this->Role->exists()) {
            throw new NotFoundException(__('Invalid role'));
        }
        $this->request->onlyAllow('post', 'delete');
        if ($this->Role->delete()) {
            $this->Session->setFlash(__('The role has been deleted.'));
        } else {
            $this->Session->setFlash(__('The role could not be deleted. Please, try again.'));
        }
        $this->redirect(array('action' => 'index'));
    }

    public function isAuthorized($action)
    {
        return $this->Permissions->IsHead();
    }
}
