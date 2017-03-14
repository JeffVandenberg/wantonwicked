<?php
namespace app\Controller;

App::uses('AppController', 'Controller');

/**
 * Conditions Controller
 *
 * @property Condition $Condition
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 * @property FlashComponent $Flash
 * @property PermissionsComponent Permissions
 */
class ConditionsController extends AppController
{

    /**
     * Components
     *
     * @var array
     */
    public $components = array('Paginator', 'Session', 'Flash');

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->set('mayEdit', $this->Permissions->CheckSitePermission(
            $this->Auth->user('user_id'),
            SitePermission::$ManageDatabase
        ));
        $this->Auth->allow(['index', 'view']);
    }

    /**
     * index method
     *
     * @return void
     */
    public function index()
    {
        $this->Condition->recursive = 0;
        $this->Paginator->settings = [
            'limit' => 1,
            'order' => [
                'Condition.name',
            ],
            'contain' => [
                'CreatedBy' => ['username'],
                'UpdatedBy' => ['username']
            ]
        ];
        $this->set('conditions', $this->Paginator->paginate());
        App::uses('SitePermission', 'Model');
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
        $conditionCount = $this->Condition->findCondition($slug, 'count');
        if (!$conditionCount) {
            throw new NotFoundException(__('Invalid condition'));
        }
        $options = [
            'contain' => [
                'CreatedBy' => ['username'],
                'UpdatedBy' => ['username'],
                'ConditionType'
            ]
        ];
        $this->set('condition', $this->Condition->findCondition($slug, 'first', $options));
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
                $this->redirect('/conditions/view/'.$slug);
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
        $this->Condition->id = $id;
        if (!$this->Condition->exists()) {
            throw new NotFoundException(__('Invalid condition'));
        }
        $this->request->allowMethod('post', 'delete');
        if ($this->Condition->delete()) {
            $this->Flash->success(__('The condition has been deleted.'));
        } else {
            $this->Flash->error(__('The condition could not be deleted. Please, try again.'));
        }
        return $this->redirect(array('action' => 'index'));
    }

    public function isAuthorized($user)
    {
        switch(strtolower($this->request->params['action'])) {
            case 'edit':
            case 'delete':
            case 'add':
                return $this->Permissions->CheckSitePermission(
                    $user['user_id'],
                    SitePermission::$ManageDatabase
                );
                break;
        }
        return false;
    }
}
