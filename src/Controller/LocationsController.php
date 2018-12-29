<?php

namespace App\Controller;

use App\Controller\Component\PermissionsComponent;
use App\Model\Entity\Location;
use App\Model\Table\LocationsTable;
use Cake\Event\Event;
use Cake\Utility\Text;

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
    public function beforeFilter(Event $event)
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
     * @return \Cake\Http\Response|void
     */
    public function save()
    {
        $location = $this->Locations->newEntity();
        if ($this->getRequest()->is('post')) {
            if($this->getRequest()->getData('id')) {
                // get entity for update
                $location = $this->Locations->get($this->getRequest()->getData('id'));
            } else {
                // set mandatory minimum values
                $location->location_name = '';
                $location->location_rules = '';
                $location->is_private = false;
                $location->is_active = true;
                $location->location_type_id = 1;
                $location->created_by_id = $this->Auth->user('user_id');
            }

            $location = $this->Locations->patchEntity($location, $this->getRequest()->getData(), ['validate' => false]);
            $location->location_name = $location->name;
            $location->location_description = $location->description;
            $location->point = json_encode($this->getRequest()->getData('point'));
            $location->updated_by_id = $this->Auth->user('user_id');

            if (!$this->Locations->save($location)) {
                $location->error = $location->getErrors();
            }
        }
        $this->set(compact('location'));
        $this->set('_serialize', ['location']);
    }
    /**
     * Add method
     *
     * @return \Cake\Http\Response|void Redirects on successful add, renders view otherwise.
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
     * @return \Cake\Http\Response|void Redirects on successful edit, renders view otherwise.
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
     * @return \Cake\Http\Response|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->getRequest()->allowMethod(['post', 'delete']);
        $location = $this->Locations->get($id);
        $message = '';
        $success = true;
        if ($this->Locations->delete($location)) {
            $this->Flash->success(__('The location has been deleted.'));
        } else {
            $this->Flash->error(__('The location could not be deleted. Please, try again.'));
            $success = false;
            $message = 'The location could not be deleted. Please, try again.';
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
