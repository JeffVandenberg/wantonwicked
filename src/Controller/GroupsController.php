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
                'GroupTypes',
                'Users'
            ],
            'order' => [
                'Groups.name'
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
     * add method
     *
     * @return \Cake\Network\Response
     */
    public function add()
    {
        if ($this->request->is('post')) {
            $group = $this->Groups->newEntity($this->request->getData());
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

        $this->response->disableCache();
        header('Content-Type: application/json');
        echo json_encode(compact('list'));
        die();
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
