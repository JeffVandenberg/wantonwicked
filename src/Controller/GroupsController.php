<?php

namespace App\Controller;

use App\Controller\Component\MenuComponent;
use App\Controller\Component\PermissionsComponent;
use App\Model\Entity\Group;
use App\Model\Table\GroupsTable;
use Cake\Network\Exception\NotFoundException;

/**
 * Groups Controller
 *
 * @property \App\Model\Table\GroupsTable $Groups
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
                'GroupTypes',
                'Users' => [
                    'fields' => ['username']
                ]
            ],
            'order' => [
                'Groups.name'
            ],
            'conditions' => [
                'Groups.is_deleted' => false
            ]
        ]));

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
                'GroupTypes',
                'Users'
            ]
        ]));

        $storytellerMenu = $this->Menu->createStorytellerMenu();
        $this->set('submenu', $storytellerMenu);
    }

    /**
     * add method
     *
     * @return \Cake\Network\Response
     */
    public function add()
    {
        if ($this->getRequest()->is('post')) {
            $group = $this->Groups->newEntity($this->getRequest()->getData());
            $group->is_deleted = false;
            $group->created_by = $this->Auth->user('user_id');
            debug($group);
            if ($this->Groups->save($group)) {
                $this->Flash->set(__('The group has been saved.'));

                return $this->redirect(array('action' => 'index'));
            } else {
                $this->Flash->set(__('The group could not be saved. Please, try again.'));
                debug($group->getErrors());
            }
        }
        $groupTypes = $this->Groups->GroupTypes->find('list');
        $this->set(compact('groupTypes'));

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
        if ($this->getRequest()->is(['patch', 'post', 'put'])) {
            $group = $this->Groups->patchEntity($group, $this->getRequest()->getData());
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
     * @param string $id
     * @return \Cake\Network\Response|null
     */
    public function delete($id = null)
    {
        $this->getRequest()->allowMethod(['post', 'delete']);
        $group = $this->Groups->get($id);
        $group->is_deleted = true;
        if ($this->Groups->save($group)) {
            $this->Flash->success(__($group->name . ' has been deleted.'));
        } else {
            $this->Flash->error(__($group->name . ' could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function listRequestTypes($id = null)
    {
        $group = $this->Groups
            ->find()
            ->where([
                'Groups.id' => $id
            ])
            ->contain([
                'RequestTypes' => [
                    'sort' => [
                        'RequestTypes.name'
                    ]
                ]
            ])
            ->first();
        /* @var Group $group */

        $list = array();
        foreach ($group->request_types as $request_type) {
            $list[] = array(
                'id' => $request_type->id,
                'name' => $request_type->name
            );
        }

        $this->set(compact('list'));
        $this->set('_serialize', ['list']);
    }

    public function isAuthorized($user)
    {
        switch ($this->getRequest()->getParam('action')) {
            case 'listRequestTypes':
                return true;
                break;
            default:
                return $this->Permissions->isAdmin();
        }
    }
}
