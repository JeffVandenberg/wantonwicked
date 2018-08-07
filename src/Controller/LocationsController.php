<?php

namespace App\Controller;

use App\Controller\Component\PermissionsComponent;
use App\Model\Entity\Location;
use App\Model\Table\LocationsTable;

/**
 * Locations Controller
 *
 *
 * @method \App\Model\Entity\Location[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 * @property \App\Model\Table\LocationsTable $Locations
 * @property PermissionsComponent Permissions
 */
class LocationsController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->Auth->allow([
            'index'
        ]);
    }


    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $locations = $this->paginate($this->Locations);

        $this->set(compact('locations'));
        $this->set('_serialize', ['locations']);
    }

    /**
     * View method
     *
     * @param string|null $id Location id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $location = $this->Locations->get($id, [
            'contain' => []
        ]);

        $this->set('location', $location);
        $this->set('_serialize', ['location']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $location = $this->Locations->newEntity();
        if ($this->getRequest()->is('post')) {
            $location = $this->Locations->patchEntity($location, $this->getRequest()->getData());
            if ($this->Locations->save($location)) {
                $this->Flash->success(__('The location has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The location could not be saved. Please, try again.'));
        }
        $this->set(compact('location'));
        $this->set('_serialize', ['location']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Location id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $location = $this->Locations->get($id, [
            'contain' => []
        ]);
        if ($this->getRequest()->is(['patch', 'post', 'put'])) {
            $location = $this->Locations->patchEntity($location, $this->getRequest()->getData());
            if ($this->Locations->save($location)) {
                $this->Flash->success(__('The location has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The location could not be saved. Please, try again.'));
        }
        $this->set(compact('location'));
        $this->set('_serialize', ['location']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Location id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->getRequest()->allowMethod(['post', 'delete']);
        $location = $this->Locations->get($id);
        if ($this->Locations->delete($location)) {
            $this->Flash->success(__('The location has been deleted.'));
        } else {
            $this->Flash->error(__('The location could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function isAuthorized($user)
    {
        return $this->Permissions->isMapAdmin();
    }
}
