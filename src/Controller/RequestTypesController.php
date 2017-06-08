<?php
namespace App\Controller;
use App\Controller\Component\MenuComponent;
use App\Controller\Component\PermissionsComponent;
use Cake\Network\Exception\NotFoundException;

/**
 * RequestTypes Controller
 *
 * @property \App\Model\Table\RequestTypesTable $RequestTypes
 * @property PermissionsComponent Permissions
 * @property MenuComponent Menu
 */
class RequestTypesController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Network\Response|null|void
     */
    public function index()
    {
        $requestTypes = $this->paginate($this->RequestTypes, [
            'order' => [
                'RequestTypes.name'
            ]
        ]);

        $this->set(compact('requestTypes'));
        $this->set('_serialize', ['requestTypes']);
        $storytellerMenu = $this->Menu->createStorytellerMenu();
        $storytellerMenu['Actions'] = array(
            'link' => '#',
            'submenu' => array(
                'New Request Type' => array(
                    'link' => array(
                        'action' => 'add'
                    )
                )
            )
        );
        $this->set('submenu', $storytellerMenu);
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
        $requestType = $this->RequestTypes->get($id, [
            'contain' => [
                'Groups' => [
                    'sort' => [
                        'Groups.name'
                    ]
                ]
            ]
        ]);

        $this->set('requestType', $requestType);
        $this->set('_serialize', ['requestType']);

        $storytellerMenu = $this->Menu->createStorytellerMenu();
        $storytellerMenu['Actions'] = array(
            'link' => '#',
            'submenu' => array(
                'List' => array(
                    'link' => array(
                        'action' => 'index'
                    )
                ),
                'Edit' => array(
                    'link' => array(
                        'action' => 'edit',
                        $id
                    )
                ),
                'New Request Type' => array(
                    'link' => array(
                        'action' => 'add'
                    )
                ),
            )
        );
        $this->set('submenu', $storytellerMenu);
    }

    /**
     * add method
     *
     * @return \Cake\Network\Response|null|void
     */
    public function add()
    {
        $requestType = $this->RequestTypes->newEntity();
        if ($this->request->is('post')) {
            $requestType = $this->RequestTypes->patchEntity($requestType, $this->request->getData());
            if ($this->RequestTypes->save($requestType)) {
                $this->Flash->success(__('The request type has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The request type could not be saved. Please, try again.'));
        }
        $this->set(compact('requestType'));
        $this->set('_serialize', ['requestType']);

        $groups = $this->RequestTypes->Groups->find('list', [
            'order' => [
                'Groups.name'
            ]
        ]);
        $this->set(compact('groups'));

        $storytellerMenu = $this->Menu->createStorytellerMenu();
        $storytellerMenu['Actions'] = array(
            'link' => '#',
            'submenu' => array(
                'List' => array(
                    'link' => array(
                        'action' => 'index'
                    )
                ),
            )
        );
        $this->set('submenu', $storytellerMenu);
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
        $requestType = $this->RequestTypes->get($id, [
            'contain' => [
                'Groups'
            ]
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $requestType = $this->RequestTypes->patchEntity($requestType, $this->request->getData());
            if ($this->RequestTypes->save($requestType)) {
                $this->Flash->success(__('The request type has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The request type could not be saved. Please, try again.'));
        }
        $this->set(compact('requestType'));
        $this->set('_serialize', ['requestType']);

        $groups = $this->RequestTypes->Groups->find('list', [
            'order' => [
                'Groups.name'
            ]
        ]);
        $this->set(compact('groups'));
        $storytellerMenu = $this->Menu->createStorytellerMenu();
        $storytellerMenu['Actions'] = array(
            'link' => '#',
            'submenu' => array(
                'List' => array(
                    'link' => array(
                        'action' => 'index'
                    )
                ),
            )
        );
        $this->set('submenu', $storytellerMenu);
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
        $this->RequestType->id = $id;
        if (!$this->RequestType->exists()) {
            throw new NotFoundException(__('Invalid request type'));
        }
        $this->request->onlyAllow('post', 'delete');
        if ($this->RequestType->delete()) {
            $this->Session->setFlash(__('The request type has been deleted.'));
        } else {
            $this->Session->setFlash(__('The request type could not be deleted. Please, try again.'));
        }

        return $this->redirect(array('action' => 'index'));
    }

    public function isAuthorized($user)
    {
        return $this->Permissions->IsAdmin();
    }
}
