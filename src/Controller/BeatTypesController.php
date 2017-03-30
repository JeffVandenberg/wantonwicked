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
        $storytellerMenu = $this->Menu->createStorytellerMenu();
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
        $this->set('beatType', $this->BeatTypes->get($id));
    }

    /**
     * add method
     *
     */
    public function add()
    {
        if ($this->request->is('post')) {
            if ($this->request->getData('action') == 'cancel') {
                $this->redirect(['action' => 'index']);
                return;
            }
            $item = $this->BeatTypes->newEntity();
            $item = $this->BeatTypes->patchEntity($item, $this->request->getData());
            $item->created_by_id = $this->Auth->user('user_id');
            $item->updated_by_id = $this->Auth->user('user_id');
            if ($this->BeatTypes->save($item)) {
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
        $beatType = $this->BeatTypes->get($id, [
            'contain' => []
        ]);

        if ($this->request->is(array('post', 'put', 'patch'))) {
            if ($this->request->getData('action') == 'cancel') {
                $this->redirect(['action' => 'index']);
                return;
            }

            $beatType = $this->BeatTypes->patchEntity($beatType, $this->request->getData());
            $beatType->updated_by_id;
            if ($this->BeatTypes->save($beatType)) {
                $this->Flash->set(__('The beat type has been saved.'));
                $this->redirect(array('action' => 'index'));
                return;
            } else {
                $this->Flash->set(__('The beat type could not be saved. Please, try again.'));
            }
        }

        $this->set(compact('beatType'));
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
        return $this->redirect(array('action' => 'index'));
    }
}
