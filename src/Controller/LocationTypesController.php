<?php

namespace App\Controller;

use App\Controller\Component\PermissionsComponent;
use App\Model\Entity\LocationType;
use App\Model\Table\LocationTypesTable;
use Cake\Event\Event;
use function dirname;
use function file_exists;
use const ROOT;
use RuntimeException;
use const WEBROOT;
use const WWW_ROOT;

/**
 * LocationTypes Controller
 *
 * @property \App\Model\Table\LocationTypesTable $LocationTypes
 * @property PermissionsComponent Permissions
 *
 * @method \App\Model\Entity\LocationType[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class LocationTypesController extends AppController
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
        $locationTypes = $this->paginate($this->LocationTypes);

        $this->set(compact('locationTypes'));
        $this->set('_serialize', ['locationTypes']);
    }

    /**
     * View method
     *
     * @param string|null $slug Location Type id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($slug = null)
    {
        $locationType = $this->LocationTypes->find('slugged', [
            'slug' => $slug,
            'contain' => false
        ])->firstOrFail();

        $this->set('locationType', $locationType);
        $this->set('_serialize', ['locationType']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $locationType = $this->LocationTypes->newEntity();
        if ($this->getRequest()->is('post')) {
            if ($this->getRequest()->getData('action') === 'cancel') {
                return $this->redirect(['action' => 'index']);
            }

            if ($this->saveLocationType($locationType)) {
                $this->Flash->success(__('The location type has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The location type could not be saved. Please, try again.'));
        }
        $this->set(compact('locationType'));
        $this->set('_serialize', ['locationType']);
    }

    /**
     * Edit method
     *
     * @param string|null $slug Location Type id.
     * @return \Cake\Http\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($slug = null)
    {
        $locationType = $this->LocationTypes->find('slugged', [
            'slug' => $slug,
            'contain' => []
        ])->firstOrFail();

        if ($this->getRequest()->is(['patch', 'post', 'put'])) {
            if ($this->saveLocationType($locationType)) {
                $this->Flash->success(__('The location type has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The location type could not be saved. Please, try again.'));
        }
        $this->set(compact('locationType'));
        $this->set('_serialize', ['locationType']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Location Type id.
     * @return \Cake\Http\Response|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->getRequest()->allowMethod(['post', 'delete']);
        $locationType = $this->LocationTypes->get($id);
        if ($this->LocationTypes->delete($locationType)) {
            $this->Flash->success(__('The location type has been deleted.'));
        } else {
            $this->Flash->error(__('The location type could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function isAuthorized($user)
    {
        return $this->Permissions->isMapAdmin();
    }

    private function saveLocationType(LocationType $locationType)
    {
        $icon = $this->getRequest()->getUploadedFile('icon');
        if(!$icon) {
            return false;
        }

        if (!$locationType->id && !$icon->getSize()) {
            $this->Flash->set('No Icon uploaded.');
            // show error that there is no icon
            return false;
        }

        // try to save icon
        if ($icon->getSize()) {
            $directory = '/images/map/locations/';
            $imagePath = $directory . $icon->getClientFilename();
            $fileName = WWW_ROOT . $imagePath;

            if (!is_dir(WWW_ROOT . $directory) &&
                !mkdir(WWW_ROOT . $directory, 0755, true)
                && !is_dir(WWW_ROOT . $directory)) {
                // error making directory
                $this->Flash->set('Unable to create directory to save images.');
                return false;
            }

            try {
                $icon->moveTo($fileName);
            } catch (RuntimeException $e) {
                $this->Flash->set('Error saving file to site. ' . $e->getMessage());
                // error moving file
                return false;
            }

            $locationType->icon = $imagePath;
        }

        $locationType->name = $this->getRequest()->getData('name');
        $locationType->description = $this->getRequest()->getData('description');

        return $this->LocationTypes->save($locationType);
    }
}
