<?php
namespace App\Controller;

use App\Controller\Component\PermissionsComponent;
use Cake\Event\Event;

/**
 * Icons Controller
 *
 * @property \App\Model\Table\IconsTable $Icons
 * @property PermissionsComponent Permissions
 *
 * @method \App\Model\Entity\Icon[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class IconsController extends AppController
{
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow();
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $icons = $this->paginate($this->Icons);
        $this->set(compact('icons'));
        $this->set('_serialize', ['icons']);
    }

    /**
     * View method
     *
     * @param string|null $id Icon id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $icon = $this->Icons->get($id, [
            'contain' => []
        ]);

        $this->set('icon', $icon);
        $this->set('_serialize', ['icon']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $icon = $this->Icons->newEntity();
        if ($this->getRequest()->is('post')) {
            if($this->getRequest()->getData('action') == 'cancel') {
                return $this->redirect(['action' => 'view', $icon->id]);
            }

            // manually populate the object. Further Legacy Refactor work is here.
            $this->populateIcon($icon);

            if ($this->Icons->save($icon)) {
                $this->Flash->success(__('The icon has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The icon could not be saved. Please, try again.'));
        }
        $this->set(compact('icon'));
        $this->set('_serialize', ['icon']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Icon id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $icon = $this->Icons->get($id, [
            'contain' => []
        ]);
        if ($this->getRequest()->is(['patch', 'post', 'put'])) {
            if($this->getRequest()->getData('action') == 'cancel') {
                return $this->redirect(['action' => 'view', $icon->id]);
            }

            // manually populate the object. Further Legacy Refactor work is here.
            $this->populateIcon($icon);

            if ($this->Icons->save($icon)) {
                $this->Flash->success(__('The icon has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The icon could not be saved. Please, try again.'));
        }
        $this->set(compact('icon'));
        $this->set('_serialize', ['icon']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Icon id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->getRequest()->allowMethod(['post', 'delete']);
        $icon = $this->Icons->get($id);
        if ($this->Icons->delete($icon)) {
            $this->Flash->success(__('The icon has been deleted.'));
        } else {
            $this->Flash->error(__('The icon could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function isAuthorized($user)
    {
        return $this->Permissions->isAdmin();
    }

    /**
     * @param $icon
     */
    private function populateIcon($icon): void
    {
        $icon->icon_name = $this->getRequest()->getData('icon_name');
        $icon->icon_id = $this->getRequest()->getData('icon_id');
        $icon->player_viewable = $this->getRequest()->getData('player_viewable') ? 'Y' : 'N';
        $icon->staff_viewable = $this->getRequest()->getData('staff_viewable') ? 'Y' : 'N';
        $icon->admin_viewable = $this->getRequest()->getData('admin_viewable') ? 'Y' : 'N';
    }
}
