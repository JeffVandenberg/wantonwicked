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

    /**
     * index method
     *
     * @return void
     */
    public function index()
    {
        $this->Condition->recursive = 0;
        $this->set('conditions', $this->Paginator->paginate());
        $this->set('mayEdit', true);
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
        if (!$this->Condition->exists($id)) {
            throw new NotFoundException(__('Invalid condition'));
        }
        $options = array('conditions' => array('Condition.' . $this->Condition->primaryKey => $id));
        $this->set('condition', $this->Condition->find('first', $options));
    }

    /**
     * add method
     *
     * @return void
     */
    public function add()
    {
        if ($this->request->is('post')) {
            $this->Condition->create();
            if ($this->Condition->save($this->request->data)) {
                $this->Flash->success(__('The condition has been saved.'));
                return $this->redirect(array('action' => 'index'));
            } else {
                $this->Flash->error(__('The condition could not be saved. Please, try again.'));
            }
        }
        $createdBies = $this->Condition->CreatedBy->find('list');
        $updatedBies = $this->Condition->UpdatedBy->find('list');
        $this->set(compact('createdBies', 'updatedBies'));
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
        if (!$this->Condition->exists($id)) {
            throw new NotFoundException(__('Invalid condition'));
        }
        if ($this->request->is(array('post', 'put'))) {
            if ($this->Condition->save($this->request->data)) {
                $this->Flash->success(__('The condition has been saved.'));
                return $this->redirect(array('action' => 'index'));
            } else {
                $this->Flash->error(__('The condition could not be saved. Please, try again.'));
            }
        } else {
            $options = array('conditions' => array('Condition.' . $this->Condition->primaryKey => $id));
            $this->request->data = $this->Condition->find('first', $options);
        }
        $createdBies = $this->Condition->CreatedBy->find('list');
        $updatedBies = $this->Condition->UpdatedBy->find('list');
        $this->set(compact('createdBies', 'updatedBies'));
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
