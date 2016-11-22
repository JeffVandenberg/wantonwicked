<?php
App::uses('AppController', 'Controller');

/**
 * Conditions Controller
 *
 * @property Condition $Condition
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 * @property FlashComponent $Flash
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
            'limit' => 25,
            'order' => [
                'Condition.name',
            ],
            'contain' => [
                'CreatedBy' => ['username'],
                'UpdatedBy' => ['username']
            ]
        ];
        $this->set('conditions', $this->Paginator->paginate());
        $this->set('mayEdit', true);
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
                'CreatedBy',
                'UpdatedBy'
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
    }

    /**
     * edit method
     *
     * @throws NotFoundException
     * @param string $slug
     */
    public function edit($slug = null)
    {
        $conditionCount = $this->Condition->findCondition($slug, 'count');
        if (!$conditionCount) {
            throw new NotFoundException(__('Invalid condition'));
        }

        if ($this->request->is(array('post', 'put'))) {
            $condition = $this->request->data;
            $condition['Condition']['updated_by'] = $this->Auth->user('user_id');

            if ($this->Condition->saveCondition($condition)) {
                $this->Flash->success(__('The condition has been saved.'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Flash->error(__('The condition could not be saved. Please, try again.'));
            }
        } else {
            $this->request->data = $this->Condition->findCondition($slug);
        }
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

    public function isAuthorized($request)
    {
        return true;
    }
}
