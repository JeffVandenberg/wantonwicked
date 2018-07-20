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
                    ],
                    'conditions' => [
                        'is_deleted' => false
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
     * @return \Cake\Network\Response|null|void
     */
    public function add()
    {
        $requestType = $this->RequestTypes->newEntity();
        if ($this->getRequest()->is('post')) {
            $requestType = $this->RequestTypes->patchEntity($requestType, $this->getRequest()->getData());
            if ($this->RequestTypes->save($requestType)) {
                $this->Flash->success(__('The request type has been saved.'));

                $this->redirect(['action' => 'index']);
                return;
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
     */
    public function edit($id = null)
    {
        $requestType = $this->RequestTypes->get($id, [
            'contain' => [
                'Groups'
            ]
        ]);
        if ($this->getRequest()->is(['patch', 'post', 'put'])) {
            $requestType = $this->RequestTypes->patchEntity($requestType, $this->getRequest()->getData());
            if ($this->RequestTypes->save($requestType)) {
                $this->Flash->success(__('The request type has been saved.'));

                $this->redirect(['action' => 'index']);
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

    public function isAuthorized($user)
    {
        return $this->Permissions->isAdmin();
    }
}
