<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Districts Controller
 *
 * @property \App\Model\Table\DistrictsTable $Districts
 *
 * @method \App\Model\Entity\District[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class DistrictsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Cities', 'CreatedBies', 'UpdatedBies', 'Realities', 'DistrictTypes']
        ];
        $districts = $this->paginate($this->Districts);

        $this->set(compact('districts'));
    }

    /**
     * View method
     *
     * @param string|null $id District id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $district = $this->Districts->get($id, [
            'contain' => ['Cities', 'CreatedBies', 'UpdatedBies', 'Realities', 'DistrictTypes', 'Locations']
        ]);

        $this->set('district', $district);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $district = $this->Districts->newEntity();
        if ($this->request->is('post')) {
            $district = $this->Districts->patchEntity($district, $this->request->getData());
            if ($this->Districts->save($district)) {
                $this->Flash->success(__('The district has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The district could not be saved. Please, try again.'));
        }
        $cities = $this->Districts->Cities->find('list', ['limit' => 200]);
        $createdBies = $this->Districts->CreatedBies->find('list', ['limit' => 200]);
        $updatedBies = $this->Districts->UpdatedBies->find('list', ['limit' => 200]);
        $realities = $this->Districts->Realities->find('list', ['limit' => 200]);
        $districtTypes = $this->Districts->DistrictTypes->find('list', ['limit' => 200]);
        $this->set(compact('district', 'cities', 'createdBies', 'updatedBies', 'realities', 'districtTypes'));
    }

    /**
     * Edit method
     *
     * @param string|null $id District id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $district = $this->Districts->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $district = $this->Districts->patchEntity($district, $this->request->getData());
            if ($this->Districts->save($district)) {
                $this->Flash->success(__('The district has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The district could not be saved. Please, try again.'));
        }
        $cities = $this->Districts->Cities->find('list', ['limit' => 200]);
        $createdBies = $this->Districts->CreatedBies->find('list', ['limit' => 200]);
        $updatedBies = $this->Districts->UpdatedBies->find('list', ['limit' => 200]);
        $realities = $this->Districts->Realities->find('list', ['limit' => 200]);
        $districtTypes = $this->Districts->DistrictTypes->find('list', ['limit' => 200]);
        $this->set(compact('district', 'cities', 'createdBies', 'updatedBies', 'realities', 'districtTypes'));
    }

    /**
     * Delete method
     *
     * @param string|null $id District id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $district = $this->Districts->get($id);
        if ($this->Districts->delete($district)) {
            $this->Flash->success(__('The district has been deleted.'));
        } else {
            $this->Flash->error(__('The district could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
