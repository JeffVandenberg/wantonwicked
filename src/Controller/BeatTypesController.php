<?php

namespace App\Controller;

use App\Controller\Component\PermissionsComponent;
use App\Model\Table\BeatTypesTable;
use Cake\Event\Event;
use Cake\Network\Exception\NotFoundException;

/**
 * BeatTypes Controller
 *
 * @property BeatTypesTable $BeatTypes
 * @property PermissionsComponent Permissions
 */
class BeatTypesController extends AppController
{
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow([

        ]);
    }

    public function isAuthorized($user)
    {
        switch ($this->request->getParam('action')) {
            case 'listTypes':
                return true;
        }
        return $this->Permissions->IsST();
    }

    /**
     * Components
     *
     * @var array
     */
    public $components = [
        'Paginator'
    ];

    /**
     * index method
     *
     * @return void
     */
    public function index()
    {
        $this->set('beatTypes', $this->Paginator->paginate($this->BeatTypes, [
            'contain' => [
                'CreatedBy' => [
                    'fields' => [
                        'username'
                    ],
                ],
                'UpdatedBy' => [
                    'fields' => [
                        'username'
                    ]
                ]
            ],
            'order' => [
                'BeatTypes.name'
            ],
            'limit' => 20
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
        $this->set('beatType', $this->BeatTypes->get($id));
    }

    /**
     * add method
     *
     */
    public function add()
    {
        if ($this->request->is('post')) {
            if ($this->request->data['action'] == 'cancel') {
                $this->redirect(['action' => 'index']);
                return;
            }
            $this->BeatType->create();
            $data = $this->request->data;
            $data['BeatType']['created_by_id'] = $this->Auth->user('user_id');
            $data['BeatType']['updated_by_id'] = $this->Auth->user('user_id');
            if ($this->BeatType->save($data)) {
                $this->Flash->set(__('The beat type has been saved.'));
                $this->redirect(array('action' => 'index'));
                return;
            } else {
                $this->Flash->set(__('The beat type could not be saved. Please, try again.'));
            }
        }
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
        if (!$this->BeatType->exists($id)) {
            throw new NotFoundException(__('Invalid beat type'));
        }
        if ($this->request->is(array('post', 'put'))) {
            if ($this->request->data['action'] == 'cancel') {
                $this->redirect(['action' => 'index']);
                return;
            }

            $data = $this->request->data;
            $data['BeatType']['updated_by_id'];
            if ($this->BeatType->save($data)) {
                $this->Flash->set(__('The beat type has been saved.'));
                $this->redirect(array('action' => 'index'));
                return;
            } else {
                $this->Flash->set(__('The beat type could not be saved. Please, try again.'));
            }
        } else {
            $options = array('conditions' => array('BeatType.' . $this->BeatType->primaryKey => $id));
            $this->request->data = $this->BeatType->find('first', $options);
        }
    }

    /**
     * delete method
     *
     * @throws NotFoundException
     * @param string $id
     * @return mixed
     */
    public function delete($id = null)
    {
        $this->BeatType->id = $id;
        if (!$this->BeatType->exists()) {
            throw new NotFoundException(__('Invalid beat type'));
        }
        $this->request->allowMethod('post', 'delete');
        if ($this->BeatType->delete()) {
            $this->Flash->set(__('The beat type has been deleted.'));
        } else {
            $this->Flash->set(__('The beat type could not be deleted. Please, try again.'));
        }
        return $this->redirect(array('action' => 'index'));
    }
}
