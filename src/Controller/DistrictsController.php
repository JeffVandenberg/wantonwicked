<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

/**
 * Districts Controller
 *
 * @property \App\Model\Table\DistrictsTable $Districts
 *
 * @method \App\Model\Entity\District[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class DistrictsController extends AppController
{
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow([
            'index',
            'view'
        ]);
    }

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
     * @return \Cake\Http\Response|void Redirects on successful add, renders view otherwise.
     */
    public function save()
    {
        $district = $this->Districts->newEntity();
        if ($this->getRequest()->is('post')) {
            $data = $this->getRequest()->getData();
            if($data['id']) {
                $district = $this->Districts->get($data['id']);
            } else {
                // set up defaults
                $district->city_id = 0;
                $district->reality_id = 0;
                $district->is_active = true;
                $district->created_by_id = $this->Auth->user('user_id');
            }

            // update
            $district->district_name = $data['name'];
            $district->district_description = $data['description'];
            $district->district_type_id = $data['district_type_id'];
            $district->points = json_encode($data['points']);
            $district->updated_by_id = $this->Auth->user('user_id');

            if (!$this->Districts->save($district)) {
                $district->errors = $district->getErrors();
            }
        }
        $this->set(compact('district'));
        $this->set('_serialize', ['district']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|void Redirects on successful add, renders view otherwise.
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
     * @return \Cake\Http\Response|void Redirects on successful edit, renders view otherwise.
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
     * @return \Cake\Http\Response|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $district = $this->Districts->get($id);
        $message = '';
        $success = true;

        if ($this->Districts->delete($district)) {
            $this->Flash->success(__('The district has been deleted.'));
        } else {
            $this->Flash->error(__('The district could not be deleted. Please, try again.'));
            $success = false;
            $message = 'The district could not be deleted. Please, try again.';
        }

        if($this->getRequest()->is('ajax')) {
            $data = compact('success', 'message');
            $this->set(compact('data'));
            $this->set('_serialize', ['data']);
        } else {
            return $this->redirect(['action' => 'index']);
        }
    }

    public function isAuthorized($user)
    {
        return $this->Permissions->isMapAdmin();
    }
}
