<?php

namespace app\Controller;

use App\Controller\Component\PermissionsComponent;
use App\Model\Table\ConditionsTable;
use Cake\Cache\Cache;
use Cake\Controller\Component\FlashComponent;
use Cake\Controller\Component\PaginatorComponent;
use Cake\Event\Event;
use Cake\Network\Exception\NotFoundException;

/**
 * Conditions Controller
 *
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 * @property PermissionsComponent Permissions
 * @property ConditionsTable Conditions
 */
class ConditionsController extends AppController
{

    /**
     * Components
     *
     * @var array
     */
    public $components = array('Paginator', 'Session', 'Flash');

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->set('mayEdit', $this->Permissions->MayManageDatabase());
        $this->Auth->allow(['index', 'view']);
    }

    /**
     * index method
     *
     * @return void
     */
    public function index()
    {
        $this->set('conditions', $this->Paginator->paginate($this->Conditions, [
            'order' => [
                'Condition.name'
            ],
        ]));
    }

    /**
     * view method
     *
     * @throws NotFoundException
     * @param string $slug
     * @return void
     */
    public function view($slug = null)
    {
        $options = [
            'where' => [
                'Conditions.slug' => $slug
            ],
            'contain' => [
                'CreatedBy' => ['username'],
                'UpdatedBy' => ['username'],
                'ConditionType'
            ]
        ];
        $this->set('condition', $this->Conditions->find('first', $options));
    }

    /**
     * add method
     */
    public function add()
    {
        if ($this->request->is('post')) {
            if ($this->request->data['action'] == 'Cancel') {
                $this->redirect('/conditions');
            }
            if ($this->request->data['action'] == 'Submit') {
                $this->Condition->create();
                $condition = $this->request->data;

                $condition['Condition']['created_by'] = $this->Auth->user('user_id');
                $condition['Condition']['updated_by'] = $this->Auth->user('user_id');

                if ($this->Condition->saveCondition($condition)) {
                    $this->Flash->success(__('The condition has been saved.'));
                    $this->redirect(array('action' => 'index'));
                } else {
                    $this->Flash->error(__('The condition could not be saved. Please, try again.'));
                }
            }
        }
        $this->set('conditionTypes', $this->Condition->ConditionType->find('list'));
    }

    /**
     * edit method
     *
     * @throws NotFoundException
     * @param string $slug
     */
    public function edit($slug = null)
    {
        Cache::read('characters');
        $conditionCount = $this->Condition->findCondition($slug, 'count');
        if (!$conditionCount) {
            throw new NotFoundException(__('Invalid condition'));
        }

        if ($this->request->is(array('post', 'put'))) {
            if ($this->request->data['action'] == 'Cancel') {
                $this->redirect('/conditions/view/' . $slug);
            }
            if ($this->request->data['action'] == 'Submit') {
                $condition = $this->request->data;
                $condition['Condition']['updated_by'] = $this->Auth->user('user_id');

                if ($this->Condition->saveCondition($condition)) {
                    $this->Flash->success(__('The condition has been saved.'));
                    $this->redirect(array('action' => 'view', $slug));
                } else {
                    $this->Flash->error(__('The condition could not be saved. Please, try again.'));
                }
            }
        } else {
            $this->request->data = $this->Condition->findCondition($slug);
        }
        $this->set('conditionTypes', $this->Condition->ConditionType->find('list'));
    }

    /**
     * delete method
     *
     * @return mixed
     * @throws NotFoundException
     * @param string $id
     */
    public function delete($id = null)
    {
        return $this->redirect(array('action' => 'index'));
    }

    public function isAuthorized($user)
    {
        switch (strtolower($this->request->getParam('action'))) {
            case 'edit':
            case 'delete':
            case 'add':
                return $this->Permissions->MayManageDatabase();
                break;
        }
        return false;
    }
}
