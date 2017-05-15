<?php

namespace App\Controller;

use App\Controller\Component\PermissionsComponent;
use App\Model\Table\RolesTable;
use Cake\Controller\Component\PaginatorComponent;
use Cake\Event\Event;
use Cake\Network\Exception\NotFoundException;
use Cake\Network\Exception\NotImplementedException;

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
        $role = $this->Roles->newEntity();

        if ($this->request->is(array('post', 'put'))) {
            if ($this->request->getData('action') == 'Cancel') {
                $this->redirect(['action' => 'index']);
            } else {
                $data = $this->request->getData();
                $permissions = array_values($data['permissions']);
                $data['permissions']['_ids'] = $permissions;
                $role = $this->Roles->patchEntity($role, $data, ['associated' => 'Permissions']);
                if ($this->Roles->save($role)) {
                    $this->Flash->set(__('The role has been saved.'));
                    $this->redirect(array('action' => 'index'));
                } else {
                    $this->Flash->set(__('The role could not be saved. Please, try again.'));
                }
            }
        }
        $permissions = $this->Roles->Permissions->find('list', [
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
        $role = $this->Roles->get($id, [
            'contain' => [
                'Permissions'
            ]
        ]);
        if ($this->request->is(array('post', 'put'))) {
            if ($this->request->getData('action') == 'Cancel') {
                $this->redirect(['action' => 'index']);
            } else {
                $data = $this->request->getData();
                $permissions = array_values($data['permissions']);
                $data['permissions']['_ids'] = $permissions;
                $role = $this->Roles->patchEntity($role, $data, ['associated' => 'Permissions']);
                if ($this->Roles->save($role)) {
                    $this->Flash->set(__('The role has been saved.'));
                    $this->redirect(array('action' => 'index'));
                } else {
                    $this->Flash->set(__('The role could not be saved. Please, try again.'));
                }
            }
        }
        $permissions = $this->Roles->Permissions->find('list', [
            'order' => 'permission_name'
        ])->toArray();
        $this->set(compact('role', 'permissions'));
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
        throw new NotImplementedException('This Route does not exist');
    }

    public function isAuthorized($action)
    {
        return $this->Permissions->IsHead();
    }
}
