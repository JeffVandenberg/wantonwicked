<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

/**
 * DistrictTypes Controller
 *
 * @property \App\Model\Table\DistrictTypesTable $DistrictTypes
 *
 * @method \App\Model\Entity\DistrictType[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class DistrictTypesController extends AppController
{
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow([
            'index',
            'view'
        ]);
        $this->set('isMapAdmin', $this->Permissions->isMapAdmin());
    }


    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $districtTypes = $this->paginate($this->DistrictTypes, [
            'order' => [
                'DistrictTypes.name'
            ]
        ]);

        $this->set(compact('districtTypes'));
    }

    /**
     * View method
     *
     * @param string|null $slug District Type slug.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($slug = null)
    {
        $districtType = $this->DistrictTypes->find('slugged', [
            'slug' => $slug,
            'contain' => ['Districts']
        ])->firstOrFail();

        $this->set('districtType', $districtType);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $districtType = $this->DistrictTypes->newEntity();
        if ($this->request->is('post')) {
            if ($this->getRequest()->getData('action') === 'cancel') {
                return $this->redirect(['action' => 'index']);
            }

            $districtType = $this->DistrictTypes->patchEntity($districtType, $this->request->getData());
            if ($this->DistrictTypes->save($districtType)) {
                $this->Flash->success(__('The district type has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The district type could not be saved. Please, try again.'));
        }
        $this->set(compact('districtType'));
    }

    /**
     * Edit method
     *
     * @param string|null $slug District Type slug.
     * @return \Cake\Http\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($slug = null)
    {
        $districtType = $this->DistrictTypes->find('slugged', [
            'slug' => $slug,
            'contain' => []
        ])->firstOrFail();
        if ($this->request->is(['patch', 'post', 'put'])) {
            if ($this->getRequest()->getData('action') === 'cancel') {
                return $this->redirect(['action' => 'index']);
            }

            $districtType = $this->DistrictTypes->patchEntity($districtType, $this->request->getData());
            if ($this->DistrictTypes->save($districtType)) {
                $this->Flash->success(__('The district type has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The district type could not be saved. Please, try again.'));
        }
        $this->set(compact('districtType'));
    }

    /**
     * Delete method
     *
     * @param string|null $id District Type id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null): ?\Cake\Http\Response
    {
        $this->request->allowMethod(['post', 'delete']);
        $districtType = $this->DistrictTypes->get($id);
        if ($this->DistrictTypes->delete($districtType)) {
            $this->Flash->success(__('The district type has been deleted.'));
        } else {
            $this->Flash->error(__('The district type could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function isAuthorized($user)
    {
        return $this->Permissions->isMapAdmin();
    }
}
